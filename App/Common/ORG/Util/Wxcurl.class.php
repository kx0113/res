<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

use Think\Log;

/**
 * 微信类
 */
class Wxcurl {

    /**
     * 获取访问令牌
     */
    public function get_access_token($token,$update = 0) {
        $log_destination = C('LOG_PATH').'wxcurl.log';
        // 获取appid和secret
        $api = M('Wxuser')->where(array('token'=>$token))->find();
        if ($api['appid'] == '' || $api['appsecret'] == '' ){
            return false;
        }else{
            $appid   = $api['appid'];
            $secret  = $api['appsecret'];
        }

        // 获取数据库中的access_token
        $where = array('appid'=>$appid, 'secret'=>$secret);
        $wxaccess_token = M('WxaccessToken')->where($where)->find();

        // 有效时间大于现在时间，返回数据库中的access_token
        if($wxaccess_token != false && $wxaccess_token['time'] > time() && $update == 0){
            Log::write('Wxcurl-get_access_token  in DB access_token = '.$wxaccess_token['access_token'], 'INFO','',$log_destination);
            return $wxaccess_token['access_token'];
        }

        // 发送请求获取新的access_token
        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . $appid . '&secret=' . $secret;
        $result = file_get_contents($url);

        if ( empty($result) ) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        $result = json_decode($result);
        if ($result->access_token == ''){
            Log::write('Wxcurl-get_access_token errcode = '.$result->errcode, 'INFO','',$log_destination);
            return false;
        }
        // 将新access_token存入数据库
        $add_data['access_token'] = $result->access_token;
        // access_token有效时间改为2小时（7200秒）
        $add_data['time'] = (time() + 7200);
        if ($wxaccess_token == false){
            $add_data['appid'] = $appid;
            $add_data['secret'] = $secret;
            M('WxaccessToken')->add($add_data);

        }else{
            M('WxaccessToken')->where(array('id'=>$wxaccess_token['id']))->save($add_data);
        }
        Log::write('Wxcurl-get_access_token re_get access_token = '.$result->access_token, 'INFO','',$log_destination);
        return $result->access_token;
    }

    /**
     * 获取微信js访问令牌
     */
    public function get_jsapi_ticket($token,$update = 0){
        // 获取数据库中的jsapi_ticket
        $jsapi_ticket = M('JsapiTicket')->where(array('token' => $token))->find();

        // 有效时间大于现在时间，返回数据库中的access_token
        if ($jsapi_ticket != false && $jsapi_ticket['time'] > time() && $update == 0) {
            return $jsapi_ticket['jsapi_ticket'];
        }

        $access_token = $this->get_access_token($token);
        $url = 'https://api.weixin.qq.com/cgi-bin/ticket/getticket?access_token='.$access_token.'&type=jsapi';
        $result = file_get_contents($url);
        if ( empty($result) ) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $result = curl_exec($ch);
            curl_close($ch);
        }
        $result = json_decode($result);
        $data['token'] = $token;
        $data['jsapi_ticket'] = $result->ticket;
        $data['time'] = time() + 7200;
        if($jsapi_ticket){
            M('JsapiTicket')->where(array('token'=>$token))->save($data);
        } else {
            M('JsapiTicket')->add($data);
        }
        return $result->ticket;
    }

    /**
     * 获取用户(公众号粉丝)列表
     *
     * @param $access_token 访问令牌
     * @param $next_openid  第一个拉取的OPENID凭证
     *
     * 
     * @return object
     */
    public function get_fans_list($access_token,$next_openid = '') {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/get?access_token=' . $access_token . '&next_openid=' . $next_openid;

        $result = file_get_contents($url);
        if (empty($result)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);
            curl_close($ch);
        }
        return json_decode($result);
    }

    /**
     * 获取用户(公众号粉丝)分组列表
     *
     * @param $access_token 访问令牌
     *
     * 
     * @return object
     */
    public function get_fans_groups ($access_token) {
        $url = 'https://api.weixin.qq.com/cgi-bin/groups/get?access_token=' . $access_token;

        $result = file_get_contents($url);
        if (empty($result)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);
            curl_close($ch);
        }
        return json_decode($result);
    }

    /**
     * 获取用户(公众号粉丝)详情
     *
     * @param string $access_token 访问令牌
     * @param string $openid       粉丝凭证
     * @param string $lang         粉丝语言，默认为简体中文（zh_CN）
     *
     * 
     * @return object
     */
    public function get_fans_details ($access_token,$openid,$lang = 'zh_CN') {
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid . '&lang=' . $lang;

        $result = file_get_contents($url);
        if (empty($result)) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

            $result = curl_exec($ch);
            curl_close($ch);
        }
        return json_decode($result);
    }

    /**
     * 批量获取用户基本信息
     * @param $token
     * @param $openids
     * @return array
     */
    public function user_info_batchget ($token,$openids) {
        $access_token = $this->get_access_token($token);
        $data['user_list'] = $openids;
        $url = 'https://api.weixin.qq.com/cgi-bin/user/info/batchget?access_token=' . $access_token;
        $result = $this->object_array($this->https_post($url,json_encode($data)));
        return $result;
    }

    /**
     * 创建粉丝分组
     *
     * @param string $access_token 访问令牌
     * @param json   $data         包含分组信息的json数据
     *
     * 
     * @return object
     */
    public function create_fans_group ($access_token,$data) {
        $url = 'https://api.weixin.qq.com/cgi-bin/groups/create?access_token=' . $access_token;

        return $this->https_post($url,$data);
    }

    /**
     * 编辑粉丝分组
     *
     * @param string $access_token 访问令牌
     * @param json   $data         包含分组信息的json数据
     *
     * 
     * @return object
     */
    public function edit_fans_group ($access_token,$data) {
        $url = 'https://api.weixin.qq.com/cgi-bin/groups/update?access_token=' . $access_token;
        return $this->https_post($url,$data);
    }

    /**
     * 获取用户的分组ID
     *
     * @param string $access_token 访问令牌
     * @param json   $data         包含分组信息的json数据
     *
     * 
     * @return object
     */
    public function get_fans_groupid ($access_token,$data) {
        $url = 'https://api.weixin.qq.com/cgi-bin/groups/getid?access_token=' . $access_token;

        return $this->https_post($url,$data);
    }

    /**
     * 编辑用户的分组ID
     *
     * @param string $access_token 访问令牌
     * @param json   $data         包含分组信息的json数据
     *
     * @author
     * @return object
     */
    public function edit_fans_groupid ($access_token,$data) {
        $url= 'https://api.weixin.qq.com/cgi-bin/groups/members/update?access_token=' . $access_token;
        return $this->https_post($url,$data);
    }

    /**
     * 上传图文消息素材
     */
    public function uploadnews ($access_token,$data) {
        $url= 'https://api.weixin.qq.com/cgi-bin/media/uploadnews?access_token=' . $access_token;
        return $this->https_post($url,$data);
    }

    /**
     * 上传多媒体文件
     */
    public function upload_media($access_token, $filepath, $type) {
        $filedata = array('media' => "@".$filepath);
        $url = 'http://file.api.weixin.qq.com/cgi-bin/media/upload?access_token='.$access_token.'&type='.$type;
        $result = $this->https_request($url, $filedata);
        return $this->https_post($url, array());
    }

    /**
     * 下载素材
     * @param $token
     * @param $media_id
     * @return json
     */
    public function media_get($token,$media_id){
        $access_token = $this->get_access_token($token);
        $url= 'http://file.api.weixin.qq.com/cgi-bin/media/get?access_token='.$access_token.'&media_id='.$media_id;
        $result = $this->https_post($url, array(), false);
        return $result;
    }


    /**
     *发送粉丝
     */
    public function fansending ($access_token,$data) {
        $url  ='https://api.weixin.qq.com/cgi-bin/message/mass/send?access_token='. $access_token;
        return $this->https_post($url,$data);
    }
    /**
     * 根据分组进行群发
     */
    public function according_to_group_sendall ($access_token,$data) {
        $url= 'https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=' . $access_token;
        return $this->https_post($url,$data);
    }

    /**
     * 发送客服消息
     */
    public function message_custom_send ($access_token,$data) {
        $url= 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;
        return $this->https_post($url,$data);
    }

    /**
     * 发送文本消息
     * $token 公众号token
     * $openid 接收者openid
     * $content 发送的文本内容
     */
    public function message_custom_send_text ($token, $openid, $content) {
        $access_token = $this->get_access_token($token);
        $message_data = '{ "touser":"'. $openid.'",';
        $message_data .= '"msgtype":"text",';
        $message_data .= '"text":{ "content":"'. $content .'"}}';
        return $this->message_custom_send($access_token, $message_data);
    }

    /**
     * 发送图文消息
     * $token 公众号token
     * $openid 接收者openid
     * $content 发送的文本内容
     */
    public function message_custom_send_news($token, $openid, $data = '') {
        if (is_array($data)){
            $content = json_encode($data) ;
        } else {
            $content = $data;
        }
        $access_token = $this->get_access_token($token);
        $message_data = '{ "touser":"'. $openid.'",';
        $message_data .= '"msgtype":"news",';
        $message_data .= '"news":{ "articles":['. $content .']}}';
        return $this->message_custom_send($access_token, $message_data);
    }

    /**
     * 发送模版消息
     */
    public function message_template_send ($token,$data) {
        if (is_array($data)){
            $data = json_encode($data) ;
        }
        $access_token = $this->get_access_token($token);
        $url= 'https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=' . $access_token;
        return $this->object_array($this->https_post($url,$data));
    }


    /**
     * 网页授权
     */
    public function oauth2 ($appid,$secret,$code) {
        $url= 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$appid.'&secret='.$secret.'&code='.$code.'&grant_type=authorization_code';
        $data = array();
        return $this->https_post($url,$data);
    }

    /**
     * 网页授权接口
     *
     * @author lulu Song <songlulu@vshoutao.com>
     */
    public function get_wecha_id_by_code($token,$code){
        $api = M('Wxuser')->where(array('token'=>$token))->find();
        $appid   = $api['appid'];
        $secret  = $api['appsecret'];
        return $this->oauth2($appid,$secret,$code);
    }

    /**
     * 网页授权获取用户资料
     */
    public function userinfo ($oauth_access_token,$openid) {
        $url= 'https://api.weixin.qq.com/sns/userinfo?access_token='.$oauth_access_token.'&openid='.$openid.'&lang=zh_CN';
        $data = array();
        return $this->https_post($url,$data);
    }

    /**
     * CURL请求数据
     */
    function https_request($url, $data = null) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }

    /**
     * POST提交数据
     *
     * @param string $url   获取数据的URL
     * @param string $data  POST提交的数据
     *
     * 
     * @return json
     */
    public function https_post($url ,$data = null, $json_decode = true) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($curl);
        if (curl_errno($curl)) {
            return 'Errno:'.curl_error($curl);
        }
        curl_close($curl);
        if ($json_decode){
            return json_decode($result);
        }else{
            return $result;
        }
    }

    /**
     * 对象类型转化成数组
     */
    function object_array($array){
        if (is_object($array)) {
            $array = (array)$array;
        }
        if (is_array($array)) {
            foreach ($array as $key=>$value) {
                $array[$key] = $this->object_array($value);
            }
        }
        return $array;
    }


    public function downloadPicture($access_token, $media_id) {
        $log_destination = C('LOG_PATH').'wxcurl.log';
        Log::write('downloadPicture into', 'INFO','',$log_destination);

        $url = "http://file.api.weixin.qq.com/cgi-bin/media/get?access_token=".$access_token."&media_id=".$media_id;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_NOBODY, 0);    //对body进行输出。
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $package = curl_exec($ch);
        $httpinfo = curl_getinfo($ch);

        curl_close($ch);
        $media = array_merge(array('mediaBody' => $package), $httpinfo);

        //求出文件格式
        preg_match('/\w\/(\w+)/i', $media["content_type"], $extmatches);
        $fileExt = $extmatches[1];
        $filename = time().rand(100,999).".{$fileExt}";
        $dirname = rtrim(C('up_path'), '/').'/weixin/';
        if(!file_exists($dirname)){
            mkdir($dirname,0777,true);
        }
        file_put_contents($dirname.$filename,$media['mediaBody']);
        $picture = array(
            'dirname' => $dirname,
            'filename'=> $filename
        );
        return $picture;
    }
}