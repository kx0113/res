<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

/**
 * 中联短信接口类
 */ 
class Sms {
    
  function __construct() {
  
     // 短信接口URL
    $this->url = rtrim(C('api_sms'),'/') . "/Service/WebService.asmx?wsdl";
    
    // 短信接口用户名 $account
    $this->account = 'weixin';
    
    // 短信接口密码 $password  
    $this->password = 'zlzk_157';
  }
  
  
  /**
   * 发送短信
   */
  public function sendSMS($mobile,$msg) {
    $client = new \SoapClient($this->url);
      
    $pack = new \StdClass();
    $pack->sendType     = 0;
    $pack->bizType      = 0;
    $pack->scheduleTime = 0;
    $pack->deadline     = 0;
    $pack->msgType      = 1;
    $pack->replyFlag    = false;
    $pack->reportFlag   = false;
    $pack->distinctFlag = false;
    $pack->batchID      = 'zlzk_wechat_batchID_' . time() . '_' . rand(1000,2000);
    $pack->uuid         = 'zlzk_wechat_uuid_' . time() . '_' . rand(1000,2000);

    $pack->msgs = new \StdClass();
    $pack->msgs->MessageData = new \StdClass();
    $pack->msgs->MessageData->Phone   = $mobile; //服务器验证部门信息
    $pack->msgs->MessageData->Content = $msg; // urlencode(mb_convert_encoding($_POST["msg"], 'utf-8', 'gb2312'));
    $pack->msgs->MessageData->vipFlag = false;
    $pack->msgs->MessageData->resend  = false;
    
    $params = new \StdClass();
    $params->account  = $this->account;
    $params->password = $this->password;
    $params->mtpack   = $pack;
    
    // print_r($params); exit;
    
    $result = $client->Post($params);
    return $result;
  }
}
?> 