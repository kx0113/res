<?php

class WebServiceSoap {

  /**
   * API接口类型
   */
  const API_TYPE_CALLCENTER   = 1; // 呼叫中心
  const API_TYPE_SAP          = 2; // SAP接口
  const API_TYPE_SMS          = 3; // 短信接口
  const API_TYPE_CRM          = 4; // CRM接口
  
  public static $apiTypes = array(
    self::API_TYPE_CALLCENTER => '呼叫中心',
    self::API_TYPE_SAP        => 'SAP',
    self::API_TYPE_SMS        => '短信',
    self::API_TYPE_SMS        => 'CRM',
  );
  
  /**
   * 返回状态
   */
  const STATUS_SUCCESS   = 0; // 成功
  const STATUS_FAILED    = 1; // 失败
  
  public static $status = array(
    self::STATUS_SUCCESS => '成功',
    self::STATUS_FAILED  => '失败',
  );
  
  /**
   * 产品咨询状态更新回传
   *
   * 收到的推送数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <params>
   *   <orderId>5465746</orderId>
   *   <status>2</status>
   *   <callsTime>2330</callsTime>
   *   <checkCode>1E5418113B5C23A7F6DA2FE45E4FDB34</checkCode>
   * </params>
   *
   * 返回数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <results>
   *   <result>0</result>
   *   <message>成功</message>
   * </results>
   *
   */
  public function productConsultation($xml) {
    
    $return = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    $return .= "<results>";
    if (!empty($xml)) {
      $xml_object = new SimpleXMLElement($xml); //将XML中的数据,读取到数组对象中 
      $checkFlag = self::checkCodeVerify((string)$xml_object->orderId,(string)$xml_object->callsTime,(string)$xml_object->checkCode);
      
      if ($checkFlag) {
        $returnFalg = "0";
        $message    = '成功';
        $flag       = '成功';
      }
      else {
        $returnFalg = "1";
        $message    = 'checkCode验证失败';
        $flag       = '失败';
      }
    }
    else {
      $returnFalg = "1";
      $message    = '推送数据为空';
      $flag       = '失败';
    }
    $return .= "<result>" . $returnFalg . "</result>";
    $return .= "<message>" . $message . "</message>";
    $return .= "</results>";
    
    $api      = array();
    $apilogs  = array();
    $time     = time();
    
    $api['uniquefield']     = (string)$xml_object->orderId;
    $api['type']            = self::API_TYPE_CALLCENTER;
    $api['flag']            = $flag;
    
    $apilogs['message']     = $message;
    $apilogs['pushData']    = $xml;
    $apilogs['returnData']  = $return;
    $apilogs['updated']     = $time;
    $apilogs['created']     = $time;
    
    // api存入唯一键标示的接口。apilogs记录日志
    $checkExist = D('Api')->where(array('uniquefield'=>(string)$xml_object->orderId))->find();
    if ($checkExist && $checkExist['flag'] == '失败' && $checkExist['count'] < 5) {
      $api['updated']   = $time;
      $api['count']     = $checkExist['count'] + 1;
      $apilogs['apiid'] = $checkExist['id'];
      D('Api')->where('id=' . $checkExist['id'])->save($api);
      D('ApiLogs')->add($apilogs);
    }
    else if (!$checkExist) {
      $api['count']     = 1;
      $api['updated']   = $time;
      $api['created']   = $time;
      $apiid = D('Api')->add($api);
      $apilogs['apiid'] = $apiid;
      D('ApiLogs')->add($apilogs);
    }
    
    if ($returnFalg == '0') {
      // 提交信息到咨询日志表中
      $checkExist = D('ShopProductsConsultation')->field('id, openid, token, openid, created,')->where(array('orderid'=>(string)$xml_object->orderId))->find();
      $Log_data['consultationid'] = $checkExist['id'];
      $Log_data['source']   = 'callCenter';
      $Log_data['log']      = (string)$xml_object->status . ' (呼叫中心)';
      $Log_data['updated']  = $time;
      $Log_data['created']  = $time;
      D('ShopProductsConsultationLog')->add($Log_data);
      // 给客户发送状态提醒
      $this->message_custom_send_text($checkExist['token'], $checkExist['openid'], '您于'.date("Y-m-d h:i:s", $checkExist['created']).'提交的咨询单(咨询单号:'.$checkExist['orderid'].')进度已更新，请到 会员中心-咨询记录 中查看。');
    }
    
    return $return;
  }
  
  /**
   * 保修单状态更新接口
   *
   * 收到的推送数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <params>
   *   <openId>jMig8ght5KNTOb8hko9</openId>
   *   <orderId>3543642</orderId>
   *   <serviceId>7500100</ serviceId >
   *   <status>2</status>
   *   <artisanName>王五</artisanName>
   *   <cellphone>18923787002</cellphone>
   *   <orderremark>操作人员：2000。</orderremark>
   *   <ispaid>0</ispaid>
   *   <price></price>
   *   <callsTime>2330</callsTime>
   *   <checkCode>1E5418113B5C23A7F6DA2FE45E4FDB34</checkCode>
   * </params>
   *
   * 返回数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <results>
   *   <result>0</result>
   *   <message>成功</message>
   * </results>
   *
   */
  public function repairStatusUpdate($xml) {
    
    $return = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    $return .= "<results>";
    if (!empty($xml)) {
      $xml_object = new SimpleXMLElement($xml); //将XML中的数据,读取到数组对象中 
      $checkFlag = self::checkCodeVerify((string)$xml_object->orderId,(string)$xml_object->callsTime,(string)$xml_object->checkCode);
      
      if ($checkFlag) {
        $returnFalg = "0";
        $message    = '成功';
        $flag       = '成功';
      }
      else {
        $returnFalg = "1";
        $message    = 'checkCode验证失败';
        $flag       = '失败';
      }
    }
    else {
      $returnFalg = "1";
      $message    = '推送数据为空';
      $flag       = '失败';
    }
    $return .= "<result>" . $returnFalg . "</result>";
    $return .= "<message>" . $message . "</message>";
    $return .= "</results>";
    
    $api      = array();
    $apilogs  = array();
    $time     = time();
    
    $api['uniquefield']     = (string)$xml_object->orderId;
    $api['type']            = self::API_TYPE_CALLCENTER;
    $api['flag']            = $flag;
    
    $apilogs['message']     = $message;
    $apilogs['pushData']    = $xml;
    $apilogs['returnData']  = $return;
    $apilogs['updated']     = $time;
    $apilogs['created']     = $time;
    
    // api存入唯一键标示的接口。apilogs记录日志
    $checkExist = D('Api')->where(array('uniquefield'=>(string)$xml_object->orderId))->find();
    if ($checkExist && $checkExist['flag'] == '失败' && $checkExist['count'] < 5) {
      $api['updated']   = $time;
      $api['count']     = $checkExist['count'] + 1;
      $apilogs['apiid'] = $checkExist['id'];
      D('Api')->where('id=' . $checkExist['id'])->save($api);
      D('ApiLogs')->add($apilogs);
    }
    else if (!$checkExist) {
      $api['count']     = 1;
      $api['updated']   = $time;
      $api['created']   = $time;
      $apiid = D('Api')->add($api);
      $apilogs['apiid'] = $apiid;
      D('ApiLogs')->add($apilogs);
    }
    
    if ($returnFalg == '0') {
      // 提交信息到维修日志表中
      $checkExistRepair = D('Repair')->field('id, openid, token, openid, created')->where(array('orderid'=>(string)$xml_object->orderId))->find();
      $RepairLog_data['repairid'] = $checkExistRepair['id'];
      $RepairLog_data['source']   = 'callCenter';
      $RepairLog_data['log']      = (string)$xml_object->status . ' (呼叫中心)';
      $RepairLog_data['updated']  = $time;
      $RepairLog_data['created']  = $time;
      D('RepairLog')->add($RepairLog_data);
      // 给客户发送状态提醒
      $this->message_custom_send_text($checkExistRepair['token'], $checkExistRepair['openid'], '您于'.date("Y-m-d h:i:s", $checkExistRepair['created']).'提交的报修单(报修单号:'.$checkExistRepair['orderid'].')进度已更新，请到 会员中心-报修记录 中查看。');
    }
    
    return $return;
  }
  
  /**
   * 会员解绑接口
   *
   * 收到的推送数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <params>
   *   <pernr>00701798</pernr>
   *   <realName>莫高勇</realName>
   *   <callsTime>2330</callsTime>
   *   <checkCode>1E5418113B5C23A7F6DA2FE45E4FDB34</checkCode>
   * </params>
   *
   * 返回数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <results>
   *   <result>0</result>
   *   <message>成功</message>
   * </results>
   *
   */
  public function unbindMember($xml) {
    
    $return = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    $return .= "<results>";
    
    if (!empty($xml)) {
      $xml_object = new SimpleXMLElement($xml); //将XML中的数据,读取到数组对象中 
      $pernr      = sprintf("%08d", trim((string)$xml_object->pernr));
      $checkFlag  = self::checkCodeVerify($pernr,(string)$xml_object->callsTime,(string)$xml_object->checkCode);
      
      if ($checkFlag) {
        $memberExist = D('Channel')->where(array('employeeid'=>$pernr,'employeename'=>(string)$xml_object->realName))->find();
        if (!$memberExist) {
          $returnFalg = "2";
          $message    = '微信平台中没有该员工信息';
          $flag       = '失败';
        }
        else if (empty($memberExist['openid'])) {
          $returnFalg = "2";
          $message    = '该用户已经解绑，不能重复解绑';
          $flag       = '失败';
        }
        else {
          $returnFalg = "0";
          $message    = '成功';
          $flag       = '成功';
        }
      }
      else {
        $returnFalg = "1";
        $message    = 'checkCode验证失败';
        $flag       = '失败';
      }
    }
    else {
      $returnFalg = "1";
      $message    = '推送数据为空';
      $flag       = '失败';
    }
    
    // 执行解绑过程
    if ($returnFalg == '0') {
      // 将channel中openid置空完成解绑
      $update = D('Channel')->where(array('employeeid'=>$pernr))->save(array('openid'=>'')); 
      if ($update) {
        $channel = D('Channel')->where(array('employeeid'=>$pernr))->find();
        if (!empty($channel['openid'])) { // 内部员工删除时，同步更新粉丝表的粉丝类型字段
          $existed = M('Fans')->where(array('token'=>$channel['token'],'openid'=>$channel['openid']))->find();
          if ($existed && $existed['fanstype'] != 1) {
            M('Fans')->where(array('token'=>$channel['token'],'openid'=>$channel['openid']))->save(array('fanstype'=>1));
          }
        }
        // 删除后台用户
        M('Users')->where(array('token'=>$this->token,'username'=>$channel['employeeid']))->delete();
        $returnFalg = "0";
        $message    = '微信平台解绑用户成功';
        $flag       = '成功';
        // 给客户发送状态提醒
        $this->message_custom_send_text($memberExist['token'], $memberExist['openid'], '您已与中联重科微信平台解绑！');
      }
      else {
        $returnFalg = "1";
        $message    = '微信平台解绑用户失败';
        $flag       = '失败';
      }
    }
    $return .= "<result>" . $returnFalg . "</result>";
    $return .= "<message>" . $message . "</message>";
    $return .= "</results>";
    
    $api      = array();
    $apilogs  = array();
    $time     = time();
    
    // $data['token']       = '';
    $api['uniquefield']     = $pernr;
    $api['type']            = self::API_TYPE_SAP;
    $api['flag']            = $flag;
    
    $apilogs['message']     = $message;
    $apilogs['pushData']    = $xml;
    $apilogs['returnData']  = $return;
    $apilogs['updated']     = $time;
    $apilogs['created']     = $time;
    
    // api存入唯一键标示的接口。apilogs记录日志
    $checkExist = D('Api')->where(array('uniquefield'=>$pernr))->find();
    if ($checkExist && $checkExist['flag'] == '失败' && $checkExist['count'] < 5) {
      $api['updated']   = $time;
      $api['count']     = $checkExist['count'] + 1;
      $apilogs['apiid'] = $checkExist['id'];
      D('Api')->where('id=' . $checkExist['id'])->save($api);
      D('ApiLogs')->add($apilogs);
    }
    else if (!$checkExist) {
      $api['count']     = 1;
      $api['updated']   = $time;
      $api['created']   = $time;
      $apiid = D('Api')->add($api);
      $apilogs['apiid'] = $apiid;
      D('ApiLogs')->add($apilogs);
    }
    
    return $return;
  }
  
  /**
   * 服务工程师或第三方物流CRM解绑接口
   *
   * 收到的推送数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <params>
   *   <userid>00701798</userid>
   *   <weixinid>oak5ytzgRxe7W9ZtWdZSw50GOuAE</weixinid>
   *   <callsTime>2330</callsTime>
   *   <checkCode>1E5418113B5C23A7F6DA2FE45E4FDB34</checkCode>
   * </params>
   *
   * 返回数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <results>
   *   <result>0</result>
   *   <message>成功</message>
   * </results>
   *
   */
  public function unbindPduser($xml) {

    $return = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    $return .= "<results>";
    
    if (!empty($xml)) {
      $xml_object   = new SimpleXMLElement($xml); //将XML中的数据,读取到数组对象中 
      $userid       = trim((string)$xml_object->userid);
      $openid       = trim((string)$xml_object->weixinid);
      $checkFlag    = self::checkCodeVerify($openid,(string)$xml_object->callsTime,(string)$xml_object->checkCode);
      
      if ($checkFlag) {
        $pduserExist = D('Pduser')->where(array('userid'=>$userid,'openid'=>$openid))->find();
        if ($pduserExist == false) {
          $returnFalg = "2";
          $message    = '微信平台中没有该员工信息';
          $flag       = '失败';
        } else {
          D('Pduser')->where(array('userid'=>$userid,'openid'=>$openid))->delete();
          M('Fans')->where(array('token'=>$pduserExist['token'], 'openid'=>$openid ))->save(array('fanstype'=>1));
          // 给客户发送状态提醒
          $this->message_custom_send_text($pduserExist['token'], $openid, '您已与中联重科微信平台解绑！');
          
          $returnFalg = "0";
          $message    = '解绑成功';
          $flag       = '成功';
        }
      }
      else {
        $returnFalg = "1";
        $message    = 'checkCode验证失败';
        $flag       = '失败';
      }
    }
    else {
      $returnFalg = "1";
      $message    = '推送数据为空';
      $flag       = '失败';
    }
    $return .= "<result>" . $returnFalg . "</result>";
    $return .= "<message>" . $message . "</message>";
    $return .= "</results>";
    
    $api      = array();
    $apilogs  = array();
    $time     = time();
    
    $api['uniquefield']     = $pernr;
    $api['type']            = self::API_TYPE_SAP;
    $api['flag']            = $flag;
    
    $apilogs['message']     = $message;
    $apilogs['pushData']    = $xml;
    $apilogs['returnData']  = $return;
    $apilogs['updated']     = $time;
    $apilogs['created']     = $time;
    
    // api存入唯一键标示的接口。apilogs记录日志
    $checkExist = D('Api')->where(array('uniquefield'=>$pernr))->find();
    if ($checkExist && $checkExist['flag'] == '失败' && $checkExist['count'] < 5) {
      $api['updated']   = $time;
      $api['count']     = $checkExist['count'] + 1;
      $apilogs['apiid'] = $checkExist['id'];
      D('Api')->where('id=' . $checkExist['id'])->save($api);
      D('ApiLogs')->add($apilogs);
    }
    else if (!$checkExist) {
      $api['count']     = 1;
      $api['updated']   = $time;
      $api['created']   = $time;
      $apiid = D('Api')->add($api);
      $apilogs['apiid'] = $apiid;
      D('ApiLogs')->add($apilogs);
    }
    
    return $return;
  }
  
  /**
   * 给粉丝发消息接口
   *
   * 收到的推送数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <params>
   *   <token>wkrrex1406427478</token>
   *   <weixinid>oak5ytzgRxe7W9ZtWdZSw50GOuAE</weixinid>
   *   <messageInfo>消息内容</messageInfo>
   *   <callsTime>2330</callsTime>
   *   <checkCode>1E5418113B5C23A7F6DA2FE45E4FDB34</checkCode>
   * </params>
   *
   * 返回数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <results>
   *   <result>0</result>
   *   <message>成功</message>
   * </results>
   *
   */
  public function messageSendText($xml) {
    
    $return = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>";
    $return .= "<results>";
    
    if (!empty($xml)) {
      $xml_object     = new SimpleXMLElement($xml); //将XML中的数据,读取到数组对象中 
      $token          = trim((string)$xml_object->token);
      $openid         = trim((string)$xml_object->weixinid);
      $messageInfo    = trim((string)$xml_object->messageInfo);
      $checkFlag  = self::checkCodeVerify($openid,(string)$xml_object->callsTime,(string)$xml_object->checkCode);
      if ($checkFlag) {
        $send_result = $this->message_custom_send_text($token, $openid, $messageInfo);
        if ($send_result->errcode == 0){
          $returnFalg = "0";
          $message    = '微信平台发送消息成功';
          $flag       = '成功';
        } else {
          $returnFalg = $send_result->errcode;
          $message    = '微信平台发送消息失败';
          $flag       = '失败';
        }
      }
      else {
        $returnFalg = "1";
        $message    = 'checkCode验证失败';
        $flag       = '失败';
      }
    }
    else {
      $returnFalg = "1";
      $message    = '推送数据为空';
      $flag       = '失败';
    }
    $return .= "<result>" . $returnFalg . "</result>";
    $return .= "<message>" . $message . "</message>";
    $return .= "</results>";
    
    $api      = array();
    $apilogs  = array();
    $time     = time();
    
    $api['uniquefield']     = $openid;
    $api['type']            = self::API_TYPE_CRM;
    $api['flag']            = $flag;
    
    $apilogs['message']     = $message;
    $apilogs['pushData']    = $xml;
    $apilogs['returnData']  = $return;
    $apilogs['updated']     = $time;
    $apilogs['created']     = $time;
    
    // api存入唯一键标示的接口。apilogs记录日志
    $checkExist = D('Api')->where(array('uniquefield'=>$openid))->find();
    if ($checkExist && $checkExist['flag'] == '失败' && $checkExist['count'] < 5) {
      $api['updated']   = $time;
      $api['count']     = $checkExist['count'] + 1;
      $apilogs['apiid'] = $checkExist['id'];
      D('Api')->where('id=' . $checkExist['id'])->save($api);
      D('ApiLogs')->add($apilogs);
    }
    else if (!$checkExist) {
      $api['count']     = 1;
      $api['updated']   = $time;
      $api['created']   = $time;
      $apiid            = D('Api')->add($api);
      $apilogs['apiid'] = $apiid;
      D('ApiLogs')->add($apilogs);
    }
    return $return;
  }
  
  /**
   * 验证checkCode是否有效
   */
  private function checkCodeVerify ($first,$callsTime,$checkCode) {
    $third        = substr($callsTime,2,2)%2;
    $strCandidate = $first . '_' . $callsTime . '_' . $third;
    if ($checkCode == strtoupper(md5($strCandidate))) {
      return true;
    }
    else {
      return false;
    }
  }
  
private function get_access_token($token) {
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
    $wxaccess_token = M('wxaccess_token')->where($where)->find();
    // 有效时间大于现在时间，返回数据库中的access_token
    if($wxaccess_token != false && $wxaccess_token['time'] > time()){
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
      return false;
    }
    
    // 将新access_token存入数据库
    if ($wxaccess_token == false){
      $add_data['appid'] = $appid;
      $add_data['secret'] = $secret;
      $add_data['access_token'] = $result->access_token;
      $add_data['time'] = (time() + 1800);
      M('wxaccess_token')->add($add_data);
    }else{
      $save_data['access_token'] = $result->access_token;
      $add_data['time'] = (time() + 1800);
      M('wxaccess_token')->where(array('id'=>$wxaccess_token['id']))->save($save_data);
    }
    return $result->access_token;
  }
  
  /**
   * 发送客服消息
   */
  private function message_custom_send ($access_token,$data) {
    $url= 'https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=' . $access_token;  
    return $this->https_post($url,$data);
  }
  
  /**
   * 发送文本消息
   * $token 公众号token
   * $openid 接收者openid
   * $content 发送的文本内容
   */
  private function message_custom_send_text ($token, $openid, $content) {
    $access_token = $this->get_access_token($token);
    $message_data = '{ "touser":"'. $openid.'",';
    $message_data .= '"msgtype":"text",';
    $message_data .= '"text":{ "content":"'. $content .'"}}';
    return $this->message_custom_send($access_token, $message_data);
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
  private function https_post($url,$data) {
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url); 
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $result = curl_exec($curl);
    if (curl_errno($curl)) {
      return 'Errno'.curl_error($curl);
    }
    curl_close($curl);
    return json_decode($result);
  }
  
  /**
   * 获取客户资料
   *
   * 收到的推送数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <params>
   *   <token>wkrrex1406427478</token>
   *   <startid>2478</startid>
   *   <endid>2480</endid>
   *   <callsTime>2330</callsTime>
   *   <checkCode>4324CEED8D0437E27C54FC76E2FEB2CE</checkCode>
   * </params>
   *
   * 返回数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <results>
   *   <result>0</result>
   *   <message>成功</message>
   *   <endid>2488</endid>
   *   <customers>
   *     <customer>
   *       <id>2478</id>
   *       <openid>oak5yt-q-ZKPw1oFHjurAtsg8Yaw</openid>
   *       <nickname>咕咚来了</nickname>
   *       <gender>2</gender>
   *       <headimgurl>http://wx.qlogo.cn/mmopen/wprMnqDUJH4bK4qRHYib64pviaaY28gEmicYsaUs6ianSMZXicAg1hKytYFI3ABicC3V2SjxUgmibmM4nWF4kA104dfLyFQwY2ibdiboH/64</headimgurl>
   *       <country>中国</country>
   *       <province>新疆</province>
   *       <city>乌鲁木齐</city>
   *       <truename></truename>
   *       <tel></tel>
   *       <intention></intention>
   *       <sex></sex>
   *       <email></email>
   *       <qq></qq>
   *       <structurename>中联重科总部</structurename>
   *       <clientname></clientname>
   *       <phone></phone>
   *       <icnum></icnum>
   *       <productcode></productcode>
   *       <remark></remark>
   *       <tag></tag>
   *     </customer>
   *     <customer>
   *       <openid>oak5ytzQbjOh3dXSNJRumrhDDXAo</openid>
   *       <nickname>平凡的人生</nickname>
   *       <gender>0</gender>
   *       <headimgurl>http://wx.qlogo.cn/mmopen/0G5yMGtIyofTibYuncR2r2LapTHer028Ky51mKehIjBIerbX7DUPfR7NiaAbn1ARsLd35icCnw7EYurUuPMC9kfSzZwoyqeMDFC/64</headimgurl>
   *       <country></country>
   *       <province></province>
   *       <city></city>
   *       <truename></truename>
   *       <tel></tel>
   *       <intention></intention>
   *       <sex></sex>
   *       <email></email>
   *       <qq></qq>
   *       <structurename>中联重科总部</structurename>
   *       <clientname></clientname>
   *       <phone></phone>
   *       <icnum></icnum>
   *       <productcode></productcode>
   *       <remark></remark>
   *       <tag></tag>
   *     </customer>
   *     ...
   *   </customers>
   * </results>
   *
   */
  public function getCustomerInfo($xml) {

    $dom = new DOMDocument("1.0");
    $dom->encoding = 'UTF-8';
    $dom_results = $dom->createElement("results");
    $dom->appendChild($dom_results);
    if (!empty($xml)) {
      $xml_object   = new SimpleXMLElement($xml); //将XML中的数据,读取到数组对象中 
      $token       = trim((string)$xml_object->token);
      $startid       = trim((string)$xml_object->startid);
      $endid       = trim((string)$xml_object->endid);
      $checkFlag    = self::checkCodeVerify($startid,(string)$xml_object->callsTime,(string)$xml_object->checkCode);
      if ($checkFlag) {
        $where ='fans.token= "'.$token.'"';
        $where .=' and fans.fanstype = 1';
        $where .=' and fans.id >= '.$startid;
        $where .=' and fans.id <= '.$endid;
        $customer_field = '';
        $customer_field .= " fans.id id,";
        $customer_field .= " fans.openid openid,";
        $customer_field .= " fans.nickname nickname,";
        $customer_field .= " fans.gender gender,";
        $customer_field .= " fans.headimgurl headimgurl,";
        $customer_field .= " fans.country country,";
        $customer_field .= " fans.province province,";
        $customer_field .= " fans.city city,";
        $customer_field .= " userinfo.truename truename,";
        $customer_field .= " userinfo.tel tel,";
        $customer_field .= " userinfo.intention intention,";
        $customer_field .= " userinfo.sex sex,";
        $customer_field .= " userinfo.email email,";
        $customer_field .= " userinfo.qq qq,";
        $customer_field .= " structure.structurename structurename,";
        $customer_field .= " fans_d.clientname clientname,";
        $customer_field .= " fans_d.phone phone,";
        $customer_field .= " fans_d.icnum icnum,";
        $customer_field .= " fans_d.productcode productcode,";
        $customer_field .= " fans_d.remark remark,";
        $customer_field .= " tag.tag tag";
        $FansDB = M('Fans fans');
        $customer_info = $FansDB
          ->field($customer_field)
          ->join('left join st_fans_details fans_d on fans.openid = fans_d.openid and fans_d.token = "'.$token.'" ')
          ->join('left join st_userinfo userinfo on fans.openid = userinfo.wecha_id and userinfo.token = "'.$token.'" ')
          ->join('left join (
              select ft.openid openid, group_concat(name) tag 
              from st_fans_tag ft 
              join st_tag tag on tag.id = ft.tagid and tag.token = "'.$token.'" 
              where ft.token = "'.$token.'" ) tag on tag.openid = fans.openid')
          ->join('left join (
              select id structureid, name structurename 
              from st_structure) structure on structure.structureid = fans.structureid')
          ->order('fans.id asc')
          ->where($where)
          ->select();
        if ($customer_info == false) {
          $returnFalg = "2";
          $message    = '没有客户信息';
          $flag       = '失败';
        } else {
          $returnFalg = "0";
          $message    = '成功';
          $flag       = '成功';
        }
      }
      else {
        $returnFalg = "1";
        $message    = 'checkCode验证失败';
        $flag       = '失败';
      }
    }
    else {
      $returnFalg = "1";
      $message    = '推送数据为空';
      $flag       = '失败';
    }
    $dom_result = $dom->createElement("result"); 
    $dom_result->appendChild($dom->createTextNode($returnFalg));
    $dom_results->appendChild($dom_result);
    $dom_message = $dom->createElement("message"); 
    $dom_message->appendChild($dom->createTextNode($message));
    $dom_results->appendChild($dom_message);
    if ($returnFalg == '0'){
      $dom_customers = $dom->createElement("customers"); 
      $dom_results->appendChild($dom_customers);
      $dom = $this->customerInfoXml($customer_info, $dom, $dom_customers);
      
//      $end_customer_info = end($customer_info);
      $end_customer_info = $FansDB->where(array('token'=>$token))->order('fans.id desc')->find();
      $dom_endid = $dom->createElement("endid"); 
      $dom_endid->appendChild($dom->createTextNode($end_customer_info['id']));
      $dom_results->appendChild($dom_endid);
    }
    
    $api      = array();
    $apilogs  = array();
    $time     = time();
    
    $api['uniquefield']     = $pernr;
    $api['type']            = self::API_TYPE_SAP;
    $api['flag']            = $flag;
    
    $apilogs['message']     = $message;
    $apilogs['pushData']    = $xml;
    $apilogs['returnData']  = $return;
    $apilogs['updated']     = $time;
    $apilogs['created']     = $time;
    
    // api存入唯一键标示的接口。apilogs记录日志
    $checkExist = D('Api')->where(array('uniquefield'=>$pernr))->find();
    if ($checkExist && $checkExist['flag'] == '失败' && $checkExist['count'] < 5) {
      $api['updated']   = $time;
      $api['count']     = $checkExist['count'] + 1;
      $apilogs['apiid'] = $checkExist['id'];
      D('Api')->where('id=' . $checkExist['id'])->save($api);
      D('ApiLogs')->add($apilogs);
    }
    else if (!$checkExist) {
      $api['count']     = 1;
      $api['updated']   = $time;
      $api['created']   = $time;
      $apiid = D('Api')->add($api);
      $apilogs['apiid'] = $apiid;
      D('ApiLogs')->add($apilogs);
    }
    
    return $dom->saveXML();
  }
  
  /**
   * 递归生成客户资料xml
   */
  private function customerInfoXml($arr,$dom=0,$item=0){
    if (!$dom){
        $dom = new DOMDocument("1.0");
    }
    if (!$item) {
        $item = $dom->createElement("customers"); 
        $dom->appendChild($item);
    }
    foreach ($arr as $key=>$val){
        $itemx = $dom->createElement(is_string($key)?$key:"customer");
        $item->appendChild($itemx);
        if (!is_array($val)){
            $text = $dom->createTextNode($val);
            $itemx->appendChild($text);
        }else {
            $this->customerInfoXml($val,$dom,$itemx);
        }
    }
    return $dom;
  }


  /**
   * 获取咨询列表
   *
   * 收到的推送数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <params>
   *   <token>wkrrex1406427478</token>
   *   <startid>1</startid>
   *   <endid>5</endid>
   *   <callsTime>2330</callsTime>
   *   <checkCode>0D21956BA104BF820F7A7CA1319FD185</checkCode>
   * </params>
   *
   * 返回数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <results>
   *   <result>0</result>
   *   <message>成功</message>
   *   <endid>2488</endid>
   *   <customers>
   *     <customer>
   *       <id>2478</id>
   *       <openid>oak5yt-q-ZKPw1oFHjurAtsg8Yaw</openid>
   *       <nickname>咕咚来了</nickname>
   *       <gender>2</gender>
   *       <headimgurl>http://wx.qlogo.cn/mmopen/wprMnqDUJH4bK4qRHYib64pviaaY28gEmicYsaUs6ianSMZXicAg1hKytYFI3ABicC3V2SjxUgmibmM4nWF4kA104dfLyFQwY2ibdiboH/64</headimgurl>
   *       <country>中国</country>
   *       <province>新疆</province>
   *       <city>乌鲁木齐</city>
   *       <truename></truename>
   *       <tel></tel>
   *       <intention></intention>
   *       <sex></sex>
   *       <email></email>
   *       <qq></qq>
   *       <structurename>中联重科总部</structurename>
   *       <clientname></clientname>
   *       <phone></phone>
   *       <icnum></icnum>
   *       <productcode></productcode>
   *       <remark></remark>
   *       <tag></tag>
   *     </customer>
   *     <customer>
   *       <openid>oak5ytzQbjOh3dXSNJRumrhDDXAo</openid>
   *       <nickname>平凡的人生</nickname>
   *       <gender>0</gender>
   *       <headimgurl>http://wx.qlogo.cn/mmopen/0G5yMGtIyofTibYuncR2r2LapTHer028Ky51mKehIjBIerbX7DUPfR7NiaAbn1ARsLd35icCnw7EYurUuPMC9kfSzZwoyqeMDFC/64</headimgurl>
   *       <country></country>
   *       <province></province>
   *       <city></city>
   *       <truename></truename>
   *       <tel></tel>
   *       <intention></intention>
   *       <sex></sex>
   *       <email></email>
   *       <qq></qq>
   *       <structurename>中联重科总部</structurename>
   *       <clientname></clientname>
   *       <phone></phone>
   *       <icnum></icnum>
   *       <productcode></productcode>
   *       <remark></remark>
   *       <tag></tag>
   *     </customer>
   *     ...
   *   </customers>
   * </results>
   *
   */
  public function getConsultationInfo($xml) {
    $xml_object = new SimpleXMLElement($xml); //将XML中的数据,读取到数组对象中 
    $token      = trim((string)$xml_object->token);
    $startid    = trim((string)$xml_object->startid);
    $endid      = trim((string)$xml_object->endid);
    $checkFlag  = self::checkCodeVerify($startid,(string)$xml_object->callsTime,(string)$xml_object->checkCode);
    
    $return  = '<?xml version="1.0" encoding="UTF-8"?>';
    $return .= '<results>';

    if($checkFlag) {
      
      $return .= '<result>0</result>';
      $return .= '<message>成功</message>';
      $endId   = M('Shop_product_consultation')->where(array('token'=>'wkrrex1406427478'))->order('id DESC')->getField('id');
      $return .= '<endid>'. $endId .'</endid>';
      $return .= '<consultations>';

      // 查询咨询信息
      $where['token'] = 'wkrrex1406427478';
      $where['id']    = array('between', array($startid, $endid));
      $consultations  = M('Shop_product_consultation')->where($where)->select();
      
      foreach ($consultations as $v) {
        $return .= '<consultation>';
        $return .= '<id>'               . $v['id']                . '</id>';
        $return .= '<productname>'      . $v['productname']       . '</productname>';
        //$return .= '<productnospec>'    . $v['productnospec']     . '</productnospec>';
        $return .= '<openid>'           . $v['openid']            . '</openid>';
        $return .= '<username>'         . $v['username']          . '</username>';
        $return .= '<usertel>'          . $v['usertel']           . '</usertel>';
        $return .= '<consultationinfo>' . $v['consultationinfo']  . '</consultationinfo>';
        //$return .= '<created>'          . $v['created']           . '</created>';
        //$return .= '<updated>'          . $v['updated']           . '</updated>';
        $return .= '</consultation>';
      }
      $return .= '</consultations>';
    } else {
      $return .= '<result>1</result>';
      $return .= '<message>验证码无效</message>';
    }
    $return .= '</results>';
    return $return;
  }
  
  /**
   * 获取推荐列表
   *
   * 收到的推送数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <params>
   *    <token>wkrrex1406427478</token>
   *    <startid>2</startid>
   *    <endid>5</endid>
   *    <callsTime>2330</callsTime>
   *    <checkCode>0D21956BA104BF820F7A7CA1319FD185</checkCode>
   * </params>
   *
   * 返回数据
   * <?xml version="1.0" encoding="UTF-8"?>
   * <results>
   *   <result>0</result>
   *   <message>成功</message>
   *   <endid>2488</endid>
   *   <customers>
   *     <customer>
   *       <id>2478</id>
   *       <openid>oak5yt-q-ZKPw1oFHjurAtsg8Yaw</openid>
   *       <nickname>咕咚来了</nickname>
   *       <gender>2</gender>
   *       <headimgurl>http://wx.qlogo.cn/mmopen/wprMnqDUJH4bK4qRHYib64pviaaY28gEmicYsaUs6ianSMZXicAg1hKytYFI3ABicC3V2SjxUgmibmM4nWF4kA104dfLyFQwY2ibdiboH/64</headimgurl>
   *       <country>中国</country>
   *       <province>新疆</province>
   *       <city>乌鲁木齐</city>
   *       <truename></truename>
   *       <tel></tel>
   *       <intention></intention>
   *       <sex></sex>
   *       <email></email>
   *       <qq></qq>
   *       <structurename>中联重科总部</structurename>
   *       <clientname></clientname>
   *       <phone></phone>
   *       <icnum></icnum>
   *       <productcode></productcode>
   *       <remark></remark>
   *       <tag></tag>
   *     </customer>
   *     <customer>
   *       <openid>oak5ytzQbjOh3dXSNJRumrhDDXAo</openid>
   *       <nickname>平凡的人生</nickname>
   *       <gender>0</gender>
   *       <headimgurl>http://wx.qlogo.cn/mmopen/0G5yMGtIyofTibYuncR2r2LapTHer028Ky51mKehIjBIerbX7DUPfR7NiaAbn1ARsLd35icCnw7EYurUuPMC9kfSzZwoyqeMDFC/64</headimgurl>
   *       <country></country>
   *       <province></province>
   *       <city></city>
   *       <truename></truename>
   *       <tel></tel>
   *       <intention></intention>
   *       <sex></sex>
   *       <email></email>
   *       <qq></qq>
   *       <structurename>中联重科总部</structurename>
   *       <clientname></clientname>
   *       <phone></phone>
   *       <icnum></icnum>
   *       <productcode></productcode>
   *       <remark></remark>
   *       <tag></tag>
   *     </customer>
   *     ...
   *   </customers>
   * </results>
   *
   */
  public function getRecommendInfo($xml) {
    
    $xml_object = new SimpleXMLElement($xml); //将XML中的数据,读取到数组对象中 
    $token      = trim((string)$xml_object->token);
    $startid    = trim((string)$xml_object->startid);
    $endid      = trim((string)$xml_object->endid);
    $checkFlag  = self::checkCodeVerify($startid,(string)$xml_object->callsTime,(string)$xml_object->checkCode);
    
    $return  = '<?xml version="1.0" encoding="UTF-8"?>';
    $return .= '<results>';

    if($checkFlag) {
      $return .= '<result>0</result>';
      $return .= '<message>成功</message>';
      $endId   = M('Recommendation')->where(array('token'=>'wkrrex1406427478'))->order('id DESC')->getField('id');
      $return .= '<endid>'. $endId .'</endid>';
      $return .= '<recommendations>';

      // 查询推荐信息
      $where['st_recommendation.token'] = 'wkrrex1406427478';
      $where['st_recommendation.id']    = array('between', array($startid, $endid));
      $recommendations= M('Recommendation')->where($where)->field('st_recommendation.*,brand.name')
      ->join('left join st_shop_product_brand brand on brand.id = st_recommendation.cateid')->select();
      
      foreach ($recommendations as $v) {
        $return .= '<recommendation>';
        $return .= '<id>'           . $v['id']          . '</id>';
        $return .= '<openid>'       . $v['openid']      . '</openid>';
//        $return .= '<orderid>'      . $v['orderid']     . '</orderid>';
        $return .= '<product_brand>'. $v['name']        . '</product_brand>';
        $return .= '<clientname>'   . $v['clientname']  . '</clientname>';
        $return .= '<content>'      . $v['content']     . '</content>';
        $return .= '<phone>'        . $v['phone']       . '</phone>';
        $return .= '<icnum>'        . $v['icnum']       . '</icnum>';
        $return .= '<qq>'           . $v['qq']          . '</qq>';
        $return .= '<email>'        . $v['email']       . '</email>';
        $return .= '<wechat>'       . $v['wechat']      . '</wechat>';
        $return .= '<address>'      . $v['address']     . '</address>';
        $return .= '<status>'       . $v['status']      . '</status>';
//        $return .= '<created>'      . $v['created']     . '</created>';
//        $return .= '<updated>'      . $v['updated']     . '</updated>';
        $return .= '</recommendation>';
      }
      $return .= '</recommendations>';
    } else {
      $return .= '<result>1</result>';
      $return .= '<message>验证码无效</message>';
    }
    $return .= '</results>';
    
    return $return;
  }
}

?>