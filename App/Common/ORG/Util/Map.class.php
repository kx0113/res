<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

/**
 * 地图
 */
class Map
{
    private $api_server_url;
    private $auth_params;

    public function __construct() {
        $this->api_server_url         = "http://api.map.baidu.com/";
        $this->auth_params            = array();
        $this->auth_params['key']     = C('baidu_map_api');
        $this->auth_params['output']  = "json";
    }

    /**
     * 计算两点之间的距离
     */
    function getDistance_map($lat_a, $lng_a, $lat_b, $lng_b) {
        //R是地球半径（米）
        $R = 6371004;
        $pk = doubleval(180 / 3.1416926539798);

        $a1 = doubleval($lat_a / $pk);
        $a2 = doubleval($lng_a / $pk);
        $b1 = doubleval($lat_b / $pk);
        $b2 = doubleval($lng_b / $pk);

        $t1 = doubleval(cos($a1) * cos($a2) * cos($b1) * cos($b2));
        $t2 = doubleval(cos($a1) * sin($a2) * cos($b1) * sin($b2));
        $t3 = doubleval(sin($a1) * sin($b1));
        $tt = doubleval(acos($t1 + $t2 + $t3));

        return round($R * $tt);
    }

    public function Place_search($query, $location, $radius) {
        return $this->call("place/search", array("query" => $query, "location" => $location, "radius" => $radius));
    }

    protected function call($method, $params = array()) {
        $headers = array(
            "User-Agent: Mozilla/5.0 (Windows NT 5.1; rv:14.0) Gecko/20100101 Firefox/14.0.1",
            "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8",
            "Accept-Language: en-us,en;q=0.5",
            //"Accept-Encoding: gzip, deflate",
            "Referer: http://developer.baidu.com/"
        );
        $params = array_merge($this->auth_params, $params);
        $url = $this->api_server_url . "$method?".http_build_query($params);
        if (DEBUG_MODE){echo "REQUEST: $url" . "\n";}
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $data = curl_exec($ch);
        curl_close($ch);
        $result = null;
        if (!empty($data)){
            if (DEBUG_MODE){
                echo "RETURN: " . $data . "\n";
            }
            $result = json_decode($data);
        }
        else{
            echo "cURL Error:". curl_error($ch);
        }
        //var_dump($result);
        return $result;
    }

    /**
     * 获得距离
     */
    public function _getDistance($distance) {
        if ($distance>1000){
            $distanceStr = (round($distance/1000,2)).'km';
        }
        else {
            $distanceStr = $distance.'m';
        }
        return $distanceStr;
    }

    /**
     * 获得时间
     */
    public function _getTime($duration) {
        $duration = $duration/60;
        if ($duration > 60){
            $durationStr = intval($duration/60) . '小时';
            if ($duration%60 > 0){
                $durationStr .= ($duration%60) . '分钟';
            }
        } else {
            $durationStr = intval($duration) . '分钟';
        }
        return $durationStr;
    }

    /**
     * 附近汽车服务商
     */
    public function nearest_provider_list ($x, $y, $ldistance = 5000, $where = array(), $grade=0, $type=0,$cardtype=0) {
        $provider_model  = M('Carserviceprovider');
        $providers = $provider_model->where($where)->order('weight DESC, id DESC')->select();

        $index          = 0;
        $return         = array();
        $nearest_provider = array();
        if ($providers){
            foreach ($providers as $providers_v) {
                $distance = $this->getDistance_map($x,$y,$providers_v['latitude'],$providers_v['longitude']);
                if ($distance < $ldistance) {
                    // 附近规定范围内的门店全部放在一个数组里，并且加上距离字段
                    $nearest_provider[$index] = $providers_v;
                    $nearest_provider[$index]['distance'] = $distance;
                    $index++;
                }
            }

            // 根据距离将门店升序排序
            if (!empty($nearest_provider)) {
                for ($k=0; $k<count($nearest_provider); $k++) {
                    for ($j=$k+1; $j<count($nearest_provider); $j++) {
                        if ($nearest_provider[$k]['distance'] > $nearest_provider[$j]['distance']){
                            $temp_company = $nearest_provider[$k];
                            $nearest_provider[$k] = $nearest_provider[$j];
                            $nearest_provider[$j] = $temp_company;
                        }
                    }
                }
            }

            // 生成需要返回的结果集
            foreach ($nearest_provider as $nearest_k => $nearest_v) {
                $nearest_provider[$nearest_k]['distance'] = $this->_getDistance($nearest_v['distance']);
            }
            return $nearest_provider;
        } else {
            return false;
        }
    }

    /**
     * 坐标转换APIWeb服务API
     */
    public function geoconv ($longitude,$latitude) {
        $url= 'http://api.map.baidu.com/geoconv/v1/?coords='.$longitude.','.$latitude.'&from=1&to=5&ak='.C('baidu_map_api').'&output=json';
        return $this->https_post($url);
    }

    /**
     * POST提交数据
     *
     * @param string $url   获取数据的URL
     * @param string $data  POST提交的数据
     * @return json
     */
    function https_post($url,$data = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'Errno:'.curl_error($curl);
        }
        curl_close($curl);
        return json_decode($result);
    }
}

?>