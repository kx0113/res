<?php

/**
 * 命名空间
 */
namespace Common\ORG\Util;

/**
 * 脚本类
 * 
 * 用于执行一些预设置操作
 * 
 * @category    ORG
 * @package     ORG
 * @author      sj
 */
class Script {
  
  public $uid;
  public $token;
  
  public function __construct() {
    $this->uid = session('uid');
  }
  
  /**
   * 添加微信账号时执行
   * 
   * @access public
   * @return void
   */
  public function add_weixin_account($wxname, $token) {
    //首次关注
    $this->first_areply($wxname, $token, '欢迎来到');
  }
  
  /**
   * 首次回复
   * 
   * @access private
   * @return void
   */
  private function first_areply($wxname, $token, $text) {
    $data = array(
      'keyword' => '首次关注公众号' . $wxname,
      'content' => $text . $wxname,
      'uid' => $this->uid,
      'createtime' => time(),
      'token' => $token,
      'home' => 0
    );
    M('areply')->add($data);
  }
}