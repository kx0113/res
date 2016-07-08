<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

use Think\Log;

/**
 * 微信类
 */
class Wechat {

  /**
   * 微信数据
   */
  private $data = array();
  
  /**
   * 微信类构造函数
   */
  public function __construct($token){
    // 验证授权
    $this->auth($token) || exit; 
    
    // 日志打印
    $log_destination = C('LOG_PATH') . 'xml.log';
    $this->logfile = $log_destination;
    
    if (IS_GET) {
      echo($_GET['echostr']);exit; 
    } 
    else {
      $xml = file_get_contents("php://input"); 
      
      if (I('get.openId')) {
        $this->authQcloud($xml) || exit;
      }
     
      $xml = new \SimpleXMLElement($xml); 
      $xml || exit;
      
      foreach ($xml as $key => $value) { 
        $this->data[$key] = strval($value); 
      }
    }
  }
  
  /**
   * 微信请求函数
   */
  public function request(){ 
    return $this->data; 
  } 
  
  /**
   * 微信响应函数
   * 
   * @param string $content 响应内容
   * @param string $type    响应消息类型
   * @param int    $flag    响应函数标记
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  public function response($content, $type = 'text', $flag = 0){ 
    $this->data = array( 
      'ToUserName'    => $this->data['FromUserName'], 
      'FromUserName'  => $this->data['ToUserName'], 
      'CreateTime'    => NOW_TIME, 
      'MsgType'       => $type,
    ); 
    
    $this->$type($content);
    $this->data['FuncFlag'] = $flag; 
    $xml = new \SimpleXMLElement('<xml></xml>'); 
    $this->data2xml($xml, $this->data); 
    
    // 去掉多余的xml头部，微信标准返回模版中没有xml头部
    $s = trim(str_replace("<?xml version=\"1.0\"?>", "",$xml->asXML()));
    
    Log::write('Wechat XML: ' . $s,'INFO','',$this->logfile);
    exit($s); 
    
  }
  
  /**
   * 微信响应函数
   * 
   * @param string $content 响应内容
   * @param string $type    响应消息类型
   * @param int    $flag    响应函数标记
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  public function response_advance($content, $type = 'text', $flag = 0){ 
    $this->data = array( 
      'ToUserName'    => $this->data['FromUserName'], 
      'FromUserName'  => $this->data['ToUserName'], 
      'CreateTime'    => NOW_TIME, 
      'MsgType'       => $type,
    ); 
    
    $this->$type($content);
    $this->data['FuncFlag'] = $flag; 
    $xml = new \SimpleXMLElement('<xml></xml>'); 
    $this->data2xml($xml, $this->data); 
    
    // 去掉多余的xml头部，微信标准返回模版中没有xml头部
    $s = trim(str_replace("<?xml version=\"1.0\"?>", "",$xml->asXML()));
    echo($s);
    //sprintf($s);
  }
  
  /**
   * 回复文本信息API函数
   *
   * XML数据模版样例：
   *  <xml>
   *    <ToUserName><![CDATA[toUser]]></ToUserName>
   *    <FromUserName><![CDATA[fromUser]]></FromUserName>
   *    <CreateTime>12345678</CreateTime>
   *    <MsgType><![CDATA[text]]></MsgType>
   *    <Content><![CDATA[你好]]></Content>
   *  </xml>
   * 
   * @param string $content 文本消息内容
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  private function text($content){
    $this->data['Content'] = $content; 
  }
  
  /**
   * 回复图片信息API函数
   * 
   * XML数据模版样例：
   * 
   *  <xml>
   *    <ToUserName><![CDATA[toUser]]></ToUserName>
   *    <FromUserName><![CDATA[fromUser]]></FromUserName>
   *    <CreateTime>12345678</CreateTime>
   *    <MsgType><![CDATA[image]]></MsgType>
   *    <Image>
   *      <MediaId><![CDATA[media_id]]></MediaId>
   *    </Image>
   *  </xml>
   * 
   * @param string $content 图片消息内容
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  private function image($content){
    list($content['MediaId']) = $content;
         
    $this->data['Image'] = $content; 
  }
  
  /**
   * 回复语音信息API函数
   * 
   * XML数据模版样例：
   * 
   *  <xml>
   *    <ToUserName><![CDATA[toUser]]></ToUserName>
   *    <FromUserName><![CDATA[fromUser]]></FromUserName>
   *    <CreateTime>12345678</CreateTime>
   *    <MsgType><![CDATA[voice]]></MsgType>
   *    <Voice>
   *      <MediaId><![CDATA[media_id]]></MediaId>
   *    </Voice>
   *  </xml>
   * 
   * @param string $content 语音消息内容
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  private function voice($content){
    list($content['MediaId']) = $content;
         
    $this->data['Voice'] = $content; 
  }
  
  /**
   * 回复视频信息API函数
   * 
   * XML数据模版样例：
   * 
   *  <xml>
   *    <ToUserName><![CDATA[toUser]]></ToUserName>
   *    <FromUserName><![CDATA[fromUser]]></FromUserName>
   *    <CreateTime>12345678</CreateTime>
   *    <MsgType><![CDATA[video]]></MsgType>
   *    <Video>
   *      <MediaId><![CDATA[media_id]]></MediaId>
   *      <Title><![CDATA[title]]></Title>
   *      <Description><![CDATA[description]]></Description>
   *    </Video> 
   *  </xml>
   * 
   * @param string $content 视频消息内容
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  private function video($content){
    list($content['MediaId'], 
         $content['Title'], 
         $content['Description']) = $content;
         
    $this->data['Video'] = $content; 
  }
  
  /**
   * 回复音乐信息API函数
   *
   * XML数据模版样例：
   * 
   *  <xml>
   *    <ToUserName><![CDATA[toUser]]></ToUserName>
   *    <FromUserName><![CDATA[fromUser]]></FromUserName>
   *    <CreateTime>12345678</CreateTime>
   *    <MsgType><![CDATA[music]]></MsgType>
   *    <Music>
   *      <Title><![CDATA[TITLE]]></Title>
   *      <Description><![CDATA[DESCRIPTION]]></Description>
   *      <MusicUrl><![CDATA[MUSIC_Url]]></MusicUrl>
   *      <HQMusicUrl><![CDATA[HQ_MUSIC_Url]]></HQMusicUrl>
   *      <ThumbMediaId><![CDATA[media_id]]></ThumbMediaId>
   *    </Music>
   *  </xml>
   * 
   * @param string $content 图片消息内容
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  private function music($content){
    list($content['Title'], 
         $content['Description'], 
         $content['MusicUrl'], 
         $content['HQMusicUrl'],
         $content['ThumbMediaId']) = $content;
    
    $this->data['Music'] = $content; 
  }
  
  /**
   * 回复图文信息API函数
   *
   * XML数据模版样例：
   * 
   *  <xml>
   *    <ToUserName><![CDATA[toUser]]></ToUserName>
   *    <FromUserName><![CDATA[fromUser]]></FromUserName>
   *    <CreateTime>12345678</CreateTime>
   *    <MsgType><![CDATA[news]]></MsgType>
   *    <ArticleCount>2</ArticleCount>
   *    <Articles>
   *      <item>
   *        <Title><![CDATA[title1]]></Title> 
   *        <Description><![CDATA[description1]]></Description>
   *        <PicUrl><![CDATA[picurl]]></PicUrl>
   *        <Url><![CDATA[url]]></Url>
   *      </item>
   *      <item>
   *        <Title><![CDATA[title]]></Title>
   *        <Description><![CDATA[description]]></Description>
   *        <PicUrl><![CDATA[picurl]]></PicUrl>
   *        <Url><![CDATA[url]]></Url>
   *      </item>
   *    </Articles>
   *  </xml>
   * 
   * @param array $content 图文消息内容
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  private function news($content){ 
    $articles = array(); 
    foreach ($content as $key => $value) { 
      list($articles[$key]['Title'], 
           $articles[$key]['Description'], 
           $articles[$key]['PicUrl'], 
           $articles[$key]['Url'] ) = $value; 
      if($key >= 9) { 
        break; 
      } 
    } 
    $this->data['ArticleCount'] = count($articles); 
    $this->data['Articles']     = $articles; 
  }
  
  /**
   * 回复位置信息API函数
   *
   * XML数据模版样例：
   * 
   *  <xml>
   *    <ToUserName><![CDATA[toUser]]></ToUserName>
   *    <FromUserName><![CDATA[fromUser]]></FromUserName>
   *    <CreateTime>12345678</CreateTime>
   *    <MsgType><![CDATA[news]]></MsgType>
   *    <Location_X>23.134521</Location_X>
   *    <Location_Y>113.358803</Location_Y>
   *    <Scale>20</Scale>
   *    <Label><![CDATA[位置信息]]></Label>
   *    <MsgId>1234567890123456</MsgId>
   *  </xml>
   * 
   * @param array $content 位置消息内容
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  private function location($content){
    list($content['Location_X'], 
         $content['Location_Y'], 
         $content['Scale'], 
         $content['Label']) = $content;
         
    $this->data = $content; 
  }
  
  /**
   * 回复链接信息API函数
   *
   * XML数据模版样例：
   * 
   *  <xml>
   *    <ToUserName><![CDATA[toUser]]></ToUserName>
   *    <FromUserName><![CDATA[fromUser]]></FromUserName>
   *    <CreateTime>12345678</CreateTime>
   *    <MsgType><![CDATA[news]]></MsgType>
   *    <Title><![CDATA[公众平台官网链接]]></Title>
   *    <Description><![CDATA[公众平台官网链接]]></Description>
   *    <Url><![CDATA[url]]></Url>
   *    <MsgId>1234567890123456</MsgId>
   *  </xml>
   * 
   * @param array $content 位置消息内容
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  private function link($content){
    list($content['Title'], 
         $content['Description'], 
         $content['Url']) = $content;
         
    $this->data = $content; 
  }
  

  /**
   * 将数据转换成微信可接受的xml形式
   * 
   * @param XML $xml XML文件
   * @param array $data 微信数据
   * @param string $item 
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   */
  private function data2xml($xml, $data, $item = 'item') { 
    foreach ($data as $key => $value) { 
      is_numeric($key) && $key = $item; 
      if(is_array($value) || is_object($value)){ 
        $child = $xml->addChild($key);
        // 递归转换
        $this->data2xml($child, $value, $item); 
      } 
      else { 
        if(is_numeric($value)){ 
          $child = $xml->addChild($key, $value); 
        } 
        else { 
          $child = $xml->addChild($key); 
          $node = dom_import_simplexml($child); 
          $node->appendChild($node->ownerDocument->createCDATASection($value)); 
        } 
      } 
    } 
  } 
  
  /**
   * 微信授权
   * 
   * @param string $token 微信公众号令牌
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   * @return boolean 是否验证通过
   */
  private function auth($token){ 
    if (I('get.openId')) {
      return true;
    }
    else {
      $data = array($_GET['timestamp'], $_GET['nonce'], $token); 
      // $data = array('1375435088', '1375562923', 'StTx0523'); 
      $sign = $_GET['signature']; 
      sort($data, SORT_STRING);
      $signature = sha1(implode($data)); 

      if( $signature == $sign ){
        return true;
      }
      else {
        return false;
      }
    }
  }
  
  /**
   * 微信授权
   * 
   * @param string $token 微信公众号令牌
   * 
   * @author Changtao Liu <yinzhiming@vshoutao.com>
   * @return boolean  是否验证通过
   */
  private function authQcloud($xml){
    // print_r($GLOBALS['HTTP_RAW_POST_DATA']);
    $msgsignkey = '2758fdc616c7def36466fe3879b22a7e';
    $target     = array($_GET['openId'],$_GET['timeStamp'],$xml,$msgsignkey);
    $sign       = $_GET['msgSign'];
    $str = implode($target);
    // print_r($str); exit;
    $signature  = md5($str); 

    if ($signature == $sign) {
      return true;
    }
    else {
      return false;
    }
  }
  
  /**
   * 多客服
   * 
   * XML数据模版样例：
   * 
   *  <xml>
   *    <ToUserName><![CDATA[touser]]></ToUserName>
   *    <FromUserName><![CDATA[fromuser]]></FromUserName>
   *    <CreateTime>1399197672</CreateTime>
   *    <MsgType><![CDATA[transfer_customer_service]]></MsgType>
   *  </xml>
   * 
   * @param string $content 转发客服系统的消息
   * 
   * @author Zhiming Yin <yinzhiming@vshoutao.com>
   */
  private function transfer_customer_service($content){
    if ($content != ''){
      $TransInfo = array('KfAccount'=>$content);
      $this->data['TransInfo'] = $TransInfo;
    }
  }
}

?>