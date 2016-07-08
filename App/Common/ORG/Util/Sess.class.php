<?php
/**
 * 命名空间
 */
namespace Common\ORG\Util;

// define('Sess_TIME',3600); //SESSION 生存时长

define('SESSION_DB_HOST',C('DB_HOST'));
define('SESSION_DB_USER',C('DB_USER'));
define('SESSION_DB_PASS',C('DB_PWD'));
define('SESSION_DB_DATABASE',C('DB_NAME'));

/**
 * 将Session存入数据库
 */
class Sess {

  /**
   * a database connection resource
   * @var resource
   */
  private $_db_link;
  
  /**
   * 存session的表
   * @var string
   */
  private $_table;
  
  /**
   * 这个是SESSION的回收周期(秒)
   */
  private $_gc_lifetime = 300;
  
  /**
   * 初始化
   */
  public function __construct($sessionName = '', $cookieLife = 0) {
    $this->_db_link = mysql_connect(SESSION_DB_HOST,SESSION_DB_USER,SESSION_DB_PASS);
    if (! $this->_db_link) {
      return False;
    }
    if (mysql_query("USE " . SESSION_DB_DATABASE)) {
      $this->_table = 'st_sessions';
      // session_set_save_handler (array (&$this, 'open' ), array (&$this, 'close' ), array (&$this, 'read' ), array (&$this, 'write' ), array (&$this, 'destroy' ), array (&$this, 'gc' ) );
      session_set_save_handler(array(&$this, 'open'),
                               array(&$this, 'close'),
                               array(&$this, 'read'),
                               array(&$this, 'write'),
                               array(&$this, 'destroy'),
                               array(&$this, 'gc'));
      //周期
      $cookieLife = intval($cookieLife);
      if ($cookieLife > 0) {
        session_set_cookie_params ( $cookieLife );
      }
      if ($this->_gc_lifetime > 0) {
        ini_set ( 'session.gc_maxlifetime', $this->_gc_lifetime );
      } 
      else {
        $this->_gc_lifetime = ini_get ( 'session.gc_maxlifetime' );
      }
      //名称
      if (! empty ( $sessionName )) {
        ini_set ( 'session.name', $sessionName );
      }
      return session_start ();
    }
    return False;
  }

  /**
   * Open the session
   * @return bool
   */
  public function open($save_path, $session_name) {
    return true;
  }

  /**
   * Close the session
   * @return bool
   */
  public function close() {
    //删除过期的SESSION
    $this->gc($this->_gc_lifetime);
    //关闭数据库
    //(注意如果系统其它功能和SESSION共用一个数据库，此处关闭可能会影响到其它功能，根据实际情况而定)
    return mysql_close($this->_db_link);
  }
  
  /**
   * Read the session
   * @param int session id
   * @return string string of the sessoin
   */
  public function read($id) {
    $id   = mysql_real_escape_string($id);
    // $sql  = "select `data` from `$this->_table` where `sid`='$id' limit 1";
    $sql = sprintf("SELECT `data` FROM `$this->_table` WHERE sid = '%s' LIMIT 1", $id);
    if ($result = mysql_fetch_assoc(mysql_query($sql, $this->_db_link))) {
      return $result ['data'];
    }
    return '';
  }

  /**
   * Write the session
   * @param int session id
   * @param string data of the session
   */
  public function write($id, $data) {
    $sql = sprintf("REPLACE INTO `$this->_table` VALUES('%s', '%s', '%s')",
                   mysql_real_escape_string($id),
                   mysql_real_escape_string($data),
                   mysql_real_escape_string(time()));
    return mysql_query($sql, $this->_db_link);
    // return mysql_query($sql, $this->_sess_db);
  }

  /**
   * Destoroy the session
   * @param int session id
   * @return bool
   */
  public function destroy($sid) {
    $sql = sprintf("DELETE FROM `$this->_table` WHERE `sid` = '%s'", $sid);
    return mysql_query($sql, $this->_db_link);
  }

  /**
   * Garbage Collector
   * @param int life time (sec.)
   * @return bool
   * @see session.gc_divisor      100
   * @see session.gc_maxlifetime 1440
   * @see session.gc_probability    1
   * @usage execution rate 1/100
   *        (session.gc_probability/session.gc_divisor)
   */
  public function gc($max) {
    $sql = sprintf("DELETE FROM `$this->_table` WHERE `expiry` < '%s'",
                   mysql_real_escape_string(time() - $max));
    return mysql_query($sql, $this->_db_link);
  }
}

?>