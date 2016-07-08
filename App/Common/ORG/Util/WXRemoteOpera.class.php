<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

/**
 * 远程操控微信公众平台类
 */
class WXRemoteOpera {

  public $token;
  public $user;
  
  public $cookieFile;
  public $loginFile;
  public $lastTimeFile;
  
  public $expire = 3600;
  
  //初始化，登录微信平台
  public function init($user,$password){
    
    /*验证码
    $url = 'http://mp.weixin.qq.com/cgi-bin/verifycode?username=';
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    preg_match('/^Set-Cookie: (.*?);/m', curl_exec($ch), $m);
    // echo $m[1];
    // exit;
    curl_close($ch);
    */
    $this->user         = $user;
    $this->cookieFile   = getcwd() . ltrim(C('up_path'), '.') . '/data/cookies/weixin/cookie_' . $this->user . '.txt';
    $this->loginFile    = getcwd() . ltrim(C('up_path'), '.') . '/data/cookies/weixin/login_'  . $this->user . '.txt';
    $this->lastTimeFile = getcwd() . ltrim(C('up_path'), '.') . '/data/cookies/weixin/last_'   . $this->user . '.txt';
    
    

    if(!file_exists($this->cookieFile)){
      $fh = fopen($this->cookieFile,"w");
      fclose($fh);
    }
    
    if(!file_exists($this->loginFile)){
      $fh = fopen($this->loginFile,"w");
      fclose($fh);
    }

    if(!file_exists($this->lastTimeFile)){
      $fh = fopen($this->lastTimeFile,"w");
      fclose($fh);
    }

    $needLogin = true;
    $nowTime   = time();
    if ($lastTime = file_get_contents($this->lastTimeFile)){
      
    } else {
      $lastTime = 0;
    }
    
    if(($nowTime-$lastTime) <= $this->expire){
      $needLogin = false;
    }
   
    if ($needLogin == true) {
      // cookie已过期，需要重新登录
      $url              = "https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN";
      $ch               = curl_init($url);
      $post['username'] = $user;
      $post['pwd']      = md5($password);
      $post['f']        = 'json';
      $post['imgcode']  = '';
      
      curl_setopt($ch, CURLOPT_SSLVERSION, 3);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
      curl_setopt($ch, CURLOPT_HEADER,1);
      curl_setopt($ch, CURLOPT_REFERER,'https://mp.weixin.qq.com/cgi-bin/loginpage?t=wxm2-login&lang=zh_CN');
      curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
      curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,0);
      curl_setopt($ch, CURLOPT_POST,1);
      curl_setopt($ch, CURLOPT_POSTFIELDS,$post);
      curl_setopt($ch, CURLOPT_COOKIEJAR,$this->cookieFile);
      $html = curl_exec($ch);
      preg_match('/[\?\&]token=(\d+)"/',$html,$t);
      $token = $t[1];
      curl_close($ch);
      if($token){
        file_put_contents($this->lastTimeFile,$nowTime);
        file_put_contents($this->loginFile,$token);
        $this->token = $token;
        return $token;
      }
      else{
        return false;
      }
    } else {
      // cookie在有效期内，不需要重新登录
      if ($token = file_get_contents($this->loginFile)) {
        //file_put_contents($this->lastTimeFile,$nowTime);
        $this->token = $token;
        return $token;
      } else {
        return false;
      }    
    }
  }
  
  /**
   * 测试登录并获取token
   */
  public function test_login($user,$password){
    $url = "https://mp.weixin.qq.com/cgi-bin/login?lang=zh_CN";
    $ch               = curl_init($url);
    $post['username'] = $user;
    $post['pwd']      = md5($password);
    $post['f']        = 'json';
    $post['imgcode']  = '';

    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch,CURLOPT_HEADER,1);
    curl_setopt($ch,CURLOPT_REFERER,'https://mp.weixin.qq.com/cgi-bin/loginpage?t=wxm2-login&lang=zh_CN');
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,0);
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    curl_setopt($ch,CURLOPT_COOKIEJAR,getcwd() . ltrim(C('up_path'), '.') . '/data/cookies/weixin/cookie.txt');
    $html=curl_exec($ch);
    preg_match('/[\?\&]token=(\d+)"/',$html,$t);
    $token=$t[1];
    curl_close($ch);
    return $token;
  }

  /**
   * 获取公众号基本信息
   */
  public function get_account_info() {
    $url = "https://mp.weixin.qq.com/cgi-bin/settingpage?t=setting/index&action=index&token=".$this->token."&lang=zh_CN";
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$this->cookieFile);
    curl_setopt($ch,CURLOPT_REFERER,$url);
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $html=curl_exec($ch);
    curl_close($ch);
    
    $info = array();
    preg_match('/(\{"user_name.*\})/', $html, $match);
    $info = json_decode($match[1], true);
    
    preg_match('/uin.*?"([0-9]+?)"/', $html, $match);
    $info['fakeid'] = $match[1];
    
    preg_match_all('/<div[^>]*class="meta_content"[^>]*>(.*?)<\/div>/si',$html, $match);

    $info['nickname']   = trim(strip_tags($match[1][0]));
    $info['email']      = trim(strip_tags($match[1][2]));
    $info['wxid']       = trim(strip_tags($match[1][3]));
    $info['weixinhao']  = strip_tags(trim($match[1][4]));
    
    // 服务号，订阅号
    $info['wxtype']  = strip_tags(trim($match[1][5]));
    
    // 是否认证通过
    $info['wx_verify']  = strip_tags(trim($match[1][6]));
    
    // 公众号区域
    $info['wx_area']  = strip_tags(trim($match[1][8]));
    
    $fh = file_get_contents($this->cookieFile);
    preg_match('/(gh_[a-z0-9A-Z]+)/', $fh, $match);
    $info['ghid'] = $match[1];
    return $info;
  }
  
  /**
   * 发送消息给指定人
   */
  public function sendmsg($content,$fromfakeid,$token){
    $url="https://mp.weixin.qq.com/cgi-bin/singlesend";
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
    curl_setopt($ch,CURLOPT_REFERER,'https://mp.weixin.qq.com/cgi-bin/message?t=message/list&count=20&day=7&token='.$this->token.'&lang=zh_CN');
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $post['t']          = 'ajax-response';
    $post['imgcode']    = '';
    $post['mask']       = false;
    $post['lang']       = 'zh_CN';
    $post['tofakeid']   = $fromfakeid;
    $post['type']       = 1;
    $post['content']    = $content;
    $post['token']      = $this->token;
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    $html=curl_exec($ch);
    curl_close($ch);
  }
  
  /**
   * 获取联系人信息
   */
  public function getcontactinfo($fromfakeid){
    $url="https://mp.weixin.qq.com/cgi-bin/getcontactinfo";
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$this->cookieFile);
    curl_setopt($ch,CURLOPT_REFERER,$url);
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $post['ajax'] = '1';
    $post['f']    = 'json';
    $post['lang'] = 'zh_CN';
    $post['t']    = 'ajax-getcontactinfo';
    $post['fakeid'] = $fromfakeid;
    $post['token'] = $this->token;
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    $html=curl_exec($ch);
    curl_close($ch);
    $arr=json_decode($html,true);
    return $arr['contact_info'];
  }
  
  /**
   * 获取公众账号logo
   */
  public function getheadimg($fromfakeid){
    $url = "https://mp.weixin.qq.com/misc/getheadimg";
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$this->cookieFile);
    curl_setopt($ch,CURLOPT_REFERER,$url);
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $post['fakeid'] = $fromfakeid;
    $post['token']=$this->token;
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    $headimg=curl_exec($ch);
    curl_close($ch);
    
    // $PNG_SAVE_DIR = S_ROOT.'uploads'.DIRECTORY_SEPARATOR.'weixin_headimg'.DIRECTORY_SEPARATOR;
    $PNG_SAVE_DIR = C('up_path') . '/front/' . $_SESSION['wxid'];
    $file = fopen($PNG_SAVE_DIR.$fromfakeid.".png","w");//打开文件准备写入
    fwrite($file,$headimg);//写入
    fclose($file);//关闭
    return $PNG_SAVE_DIR.$fromfakeid.".png";
  }
  
  /**
   * 获取公众账号二维码
   */
  public function getqrcode($fromfakeid){
    $url = "https://mp.weixin.qq.com/misc/getqrcode";
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$this->cookieFile);
    curl_setopt($ch,CURLOPT_REFERER,$url);
    // curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1;WOW64;rv:20.0) Gecko/20100101 Firefox/20.0');
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $post['fakeid'] =$fromfakeid;
    $post['token'] = $this->token;
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    $headimg=curl_exec($ch);
    curl_close($ch);
    // $PNG_SAVE_DIR = S_ROOT.'uploads'.DIRECTORY_SEPARATOR.'weixin_qrcode'.DIRECTORY_SEPARATOR;
    $PNG_SAVE_DIR = C('up_path') . '/front/' . $_SESSION['wxid'];
    $file = fopen($PNG_SAVE_DIR.$fromfakeid."qr.png","w");//打开文件准备写入
    fwrite($file,$headimg);//写入
    fclose($file);//关闭
    return $PNG_SAVE_DIR.$fromfakeid . "qr.png";
  }
  
  /**
   * 获取微信联系人地址
   */
  public function getcontactlist($pagesize=10,$page=0){
    $url = "https://mp.weixin.qq.com/cgi-bin/contactmanage?t=user/index&pagesize=".$pagesize."&pageidx=".$page."&type=0&groupid=0&token=".$this->token."&lang=zh_CN";
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$this->cookieFile);
    curl_setopt($ch,CURLOPT_REFERER,$url);
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $html = curl_exec($ch);
    curl_close($ch);
    preg_match('%(?<=\"contacts\"\:)(.*)(?=}\)\.contacts)%', $html, $result);
    return json_decode($result[1],true);
  }

  /**
   * 获取信息列表
   */
  public function getmsglist($count=20) {
    $url = "https://mp.weixin.qq.com/cgi-bin/message?t=message/list&action=&keyword=&count=".$count."&day=7&filterivrmsg=0&token=".$this->token."&lang=zh_CN";
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$this->cookieFile);
    curl_setopt($ch,CURLOPT_REFERER,$url);
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $html = curl_exec($ch);
    preg_match('%(?<=\"msg_item\"\:)(.*)(?=}\)\.msg_item)%', $html, $result);
    curl_close($ch);
    return json_decode($result[1],true);
  }
  
  /**
   * 获取访问令牌
   */
  private function get_access_token($appid,$appsecret){
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appid."&secret=".$appsecret;
    $arr = json_decode(file_get_contents($url),1);
    return $arr;
  }
  
  /**
   * 创建自定义菜单
   */
  public function create_menu($appid,$appsecret,$data){
    $arr = $this->get_access_token($appid,$appsecret);
    if($arr['access_token']){
      $ACCESS_TOKEN=$arr['access_token'];
      $ch = curl_init();
      curl_setopt($ch, CURLOPT_URL, "https://api.weixin.qq.com/cgi-bin/menu/create?access_token={$ACCESS_TOKEN}");
      curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
      curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
      curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
      curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      $tmpInfo = curl_exec($ch);
      if (curl_errno($ch)) {
        echo 'Errno'.curl_error($ch);
      }
      curl_close($ch);
      return json_decode($tmpInfo,1);
    }else{    
      return $arr;
    }
  }
  
  /**
   * 获取自定义菜单
   */
  public function get_menu($appid,$appsecret){
    $arr = $this->get_access_token($appid,$appsecret);
    if($arr['access_token']){
           $ACCESS_TOKEN=$arr['access_token'];
       $url="https://api.weixin.qq.com/cgi-bin/menu/get?access_token=".$ACCESS_TOKEN;
       $arr = json_decode(file_get_contents($url),1);
       return $arr;
    }else{    
      return $arr;
    }
  }
  
  /**
   * 删除自定义菜单
   */
  public function del_menu($appid,$appsecret){
    $arr = $this->get_access_token($appid,$appsecret);
    if($arr['access_token']){
      $ACCESS_TOKEN=$arr['access_token'];
      $url="https://api.weixin.qq.com/cgi-bin/menu/delete?access_token=".$ACCESS_TOKEN;
      $arr = json_decode(file_get_contents($url),1);
      return $arr;
    }else{    
      return $arr;
    }
  }
  
  /**
   * 关闭编辑模式
   */
  public function close_editmode(){
    $url="https://mp.weixin.qq.com/misc/skeyform?form=advancedswitchform&lang=zh_CN";
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$this->cookieFile);
    curl_setopt($ch,CURLOPT_REFERER,$url);
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $post['flag']=0;
    $post['type']=1;
    $post['token']=$this->token;
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    $html=curl_exec($ch);
    curl_close($ch);
    return json_decode($html,true);
  }
  
  /**
   * 开启开发者模式
   */
  public function open_developmode(){
    $url="https://mp.weixin.qq.com/misc/skeyform?form=advancedswitchform&lang=zh_CN";
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$this->cookieFile);
    curl_setopt($ch,CURLOPT_REFERER,$url);
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $post['flag']=1;
    $post['type']=2;
    $post['token']=$this->token;
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    $html=curl_exec($ch);
    curl_close($ch);
    //preg_match('%(?<=\"contacts\"\:)(.*)(?=}\)\.contacts)%', $html, $result);
    return json_decode($html,true);
  }
  
  /**
   * 接口配置信息
   */
  public function set_api($api_token,$api_url){ 
    $url="https://mp.weixin.qq.com/advanced/callbackprofile?t=ajax-response&token=".$this->token."&lang=zh_CN";
    $ch=curl_init($url);
    curl_setopt($ch, CURLOPT_SSLVERSION, 3);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_COOKIEFILE,$this->cookieFile);
    curl_setopt($ch, CURLOPT_REFERER,$url);
    curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
    $post['callback_token']=$api_token;
    $post['url']=$api_url;
    curl_setopt($ch,CURLOPT_POST,1);
    curl_setopt($ch,CURLOPT_POSTFIELDS,$post);
    $html=curl_exec($ch);
    curl_close($ch);
    //preg_match('%(?<=\"contacts\"\:)(.*)(?=}\)\.contacts)%', $html, $result);
    return json_decode($html,true);
  }
  
  /**
   * 一键配置接口
   */
  public function quick_set_api($api_token,$api_url){
    $this->close_editmode();
    $this->open_developmode();
    return $this->set_api($api_token,$api_url);
  }
}


?>