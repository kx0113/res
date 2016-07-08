<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

use Think\Log;

/**
 * 物联网接口类
 */
class InternetOfThings {
  /**
   * 物联网接口数据
   */
  private $data = array();
  private $logfile = '';
  
  /**
   * 物联网接口构造函数
   */
  public function __construct(){
    // 日志文件
    $this->logfile = C('LOG_PATH') . 'internet_of_things.log';
    // 物联网接口数据
    $this->data = M('InternetOfThingsInterface')->where('id = 1')->find();
  }

  /**
   * 物联网登录功能
   */
  public function login() {
    $data['uname'] = $this->data['uname'];
    $data['upwd'] = md5($this->data['upwd']);
    $params = json_encode($data);
    $url = $this->data['login'].'?params='.urlencode($params);
    $result = $this->https_post($url);
    // 转成数组
    $result = $this->object_array($result);
    return $result;
  }
  

  /**
   * 获取登授权请求动态识别码录
   */
  public function get_requestkey() {
    // requestkey有效时间
    $valid_time = $this->data['updated'] + $this->data['valid_time'];
    if ($valid_time > time()){
      return $this->data['requestkey'];
    } else {
      $loginResult = $this->login();
      $requestkey = $loginResult['requestkey'];
      $InterfaceDB = D('InternetOfThingsInterface');
      if ($InterfaceDB->create(array('requestkey'=>$requestkey))){
        if ($InterfaceDB->where('id = 1')->save()){
          return $requestkey;
        }
      }
      return false;
    }
  }
  
  /**
   * 物联网登出功能
   */
  public function logout() {
    $requestkey = $this->get_requestkey();
    $data['uname'] = $this->data['uname'];
    $params = json_encode($data);
    $url = $this->data['logout'].'?requestkey='.$requestkey.'&params='.urlencode($params);
    $result = $this->https_post($url);
    // 转成数组
    $result = $this->object_array($result);
    return $result;
  }
  
  /**
   * 获取设备类型
   */
  public function devices_vehicletype() {
    $requestkey = $this->get_requestkey();
    $url = $this->data['devices_vehicletype'].'?requestkey='.$requestkey;
    $result = $this->https_post($url);
    // 转成数组
    $result = $this->object_array($result);
    // 数组去重
    $vehicletype = array();
    foreach ($result['vehicletype'] as $result_vehicletype_v) {
      if (!in_array($result_vehicletype_v, $vehicletype)){
        $vehicletype[] = $result_vehicletype_v;
      }
    }
    return $vehicletype;
  }
  
  /**
   * 获取设备分布/位置
   */
  public function devices_position($data = array()) {
    $requestkey = $this->get_requestkey();
    $data['uname'] = $this->data['uname'];
    $params =  json_encode($data);
    $url = $this->data['devices_position'].'?requestkey='.$requestkey.'&params='.urlencode($params);
    $result = $this->https_post($url);
    // 转成数组
    $result = $this->object_array($result);
    if ($result['result'] == '200'){
      unset($result['result']);
      unset($result['uname']);
      return $result;
    } else {
      return false;
    }
  }

  /**
   * 获取设备列表/查询
   */
  public function devices_info($data = array()) {
    $requestkey = $this->get_requestkey();
    $data['uname'] = $this->data['uname'];
    $params =  json_encode($data);
    $url = $this->data['devices_info'].'?requestkey='.$requestkey.'&params='.urlencode($params);
    $result = $this->https_post($url);
    // 转成数组
    $result = $this->object_array($result);
    if ($result['result'] == '200'){
      unset($result['result']);
      unset($result['uname']);
      return $result;
    } else {
      return false;
    }
  }
  
  /**
   * 指令 待修复
   */
  public function devices_command($data = array()) {
    $requestkey = $this->get_requestkey();
    $params = json_encode($data);
    $url = $this->data['devices_command'].'?requestkey='.$requestkey.'&params='.urlencode($params);

    $result = $this->https_post($url);
    // 转成数组
    $result = $this->object_array($result);

    if ($result['result'] == '200'){
      unset($result['result']);
      unset($result['uname']);
      return $result;
    } else {
      return false;
    }
  }
  
  /**
   * 获取设备轨迹
   */
  public function devices_track($data = array()) {
    $requestkey = $this->get_requestkey();
    $params =  json_encode($data);
    $url = $this->data['devices_track'].'?requestkey='.$requestkey.'&params='.urlencode($params);
    $result = $this->https_post($url);
    // 转成数组
    $result = $this->object_array($result);
    return $result;
  }
  
  /**
   * 运营统计
   * 注意：工作时间为0的不返回。
   */
  public function devices_work($data = array()) {
    $requestkey = $this->get_requestkey();
    $params =  json_encode($data);
    $url = $this->data['devices_work'].'?requestkey='.$requestkey.'&params='.urlencode($params);
    $result = $this->https_post($url);
    // 转成数组
    $result = $this->object_array($result);
    if ($result['result'] == '200'){
      unset($result['result']);
      return $result;
    } else {
      return false;
    }
  }
  
  /**
   * 修改车牌号 有问题
   */
  public function devices_vnum() {
    $requestkey = $this->get_requestkey();
//  $url = $this->data['devices_vnum'].'?requestkey='.$requestkey;
    $data['vid'] = '018153A1140058';
    $data['vnum'] = 'AU0011';
    $params =  json_encode($data);
    $url = 'http://113.240.239.163:8899/IZooMobile/devices/vnum?requestkey='.$requestkey.'&params='.urlencode($params);
    $result = $this->https_post($url);
    // 转成数组
    $result = $this->object_array($result);
    if ($result['result'] == '200'){
      unset($result['result']);
      unset($result['uname']);
      return $result;
    } else {
      return false;
    }
  }

  /**
   * POST提交数据
   *
   * @param string $url   获取数据的URL
   * @param string $data  POST提交的数据
   */
  function https_post($url, $data) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    curl_close($curl);
    if (curl_errno($curl)) {
      return 'error:'.curl_error($curl);
    }
    return json_decode($result);
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
}