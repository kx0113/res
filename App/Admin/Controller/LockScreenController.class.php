<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/20
 * Time: 15:11
 */
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\ccc;
class LockScreenController extends Controller
{
    function _initialize()
    {
        $this->account = session("account");
        $this->assign("index_title", C('index_title'));
        $this->assign("admin_name_dsda", C('admin_name_dsda'));
        $this->cid = session("cid");
        $this->assign("adminheadpath", C('adminheadpath'));
        // echo session('lock');
    }
    //超级密码验证
    public function supppass($password){
        foreach(C('passwordsup') as $k1=>$v1){
            if($password == $v1){
                $res = '1';
                break;
            }else{
                $res = '2';
            }
        }
        return $res;
    }
    /*
     * 注销登陆
     */
    public function _logout()
    {
        session(NULL);
        session_unset();
        session_destroy();
    }

    public function LockPassword()
    {
        if (IS_POST) {
            $password = $_REQUEST['password'];
            if (!empty($password)) {
                $where['password'] = $this->passwordAri($password);
                $res = M('admin')->where($where)->find();
                if ($res) {
                    logAdd('屏幕锁定验证成功',$this->cid);
                    session("lock", null);
                    session("lock_time", null);
                    $this->redirect("Admin/Index/index");
                    exit;
                } else {
                    $this->error('密码错误');
                    exit;
                }
            } else {
                $this->error('请输入密码');
            }
        }
    }
    public function index()
    {
        get_client_ip();
        if (session('lock') == 'locked') {
            $this->_logout();
            $this->error('屏幕锁定，请重新登陆！',U('Admin/Login/login'));
            exit;
        }
        logAdd('主动锁定屏幕',$this->cid);
        $where['id'] = $this->cid;
        $res = M('admin')->where($where)->find();
        $this->assign('list', $res);
        session("lock", 'locked');
        $this->display();
    }
    public function passwordAri($password){
        return substr(md5($password), 5, 20);
    }
}
