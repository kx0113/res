<?php
namespace Admin\Controller;
use Think\Controller;
use Common\Controller\ccc;
class LoginController extends Controller {
    public $account; //登陆用户名
    public $cid;     //登陆ID
    function _initialize(){
        $this->account = session("account");
        $this->assign("index_title",C('index_title'));
        $this->assign("admin_name_dsda",C('admin_name_dsda'));
        $this->cid = session("cid");
        $this->assign("adminheadpath", C('adminheadpath'));
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

    public function passwordAri($password){
        return substr(md5($password), 5, 20);
    }
    public function is_verify(){
        if($_REQUEST['par'] == 'sub'){
            $where['id'] = trim($_REQUEST['user_id']);
            $resUser = M('user')->where($where)->find();
            if($resUser){
                if($resUser['is_verify'] == '0'){
                    $data['alipayaccount'] = trim($_REQUEST['alipayaccount']);
                    $data['is_verify'] = 1;
                    $saveAlipay = M('user')->where($where)->data($data)->save();
                    if($saveAlipay){
                        $json['res']='suc';
                    }else{
                        $json['res']='err';//操作失败
                    }
                }else{
                    $json['res']='err1';//已经核对过
                }
            }else{
                $json['res']='err';
            }
        }
        $this->ajaxReturn($json);
    }
    public function VisitOperation($lang = '',$state = '1'){
        $log = M('behavior');
        $data1['user_id'] = isset($this->cid) ? $this->cid : 0;
        $data1['behavior'] = $lang;
        $data1['time'] = time();
        $data1['url'] = $_SERVER['PATH_INFO'];
        $data1['status'] = $state;
        if($this->cid == '1'){
            $data1['exclude'] = 1;
        }else{
            $data1['exclude'] = 2;
        }
        $data1['ip'] = get_client_ip();
        $add_log = $log->data($data1)->add();
    }
    //核对支付宝
    public function alipayaccount(){
        $this->VisitOperation('核对支付宝');
        $sql = "SELECT a.id,b.station_name sname,a.name FROM tp_user a JOIN tp_station b ON a.station_id = b.station_id WHERE a.is_dimission = 0 AND a.status=1";
        $user = M()->query($sql);
        $this->assign('user',$user);
        $this->display();
    }
    //头像上传
    public function upload_head(){
        $uid =  $_REQUEST['uid'];
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/user_head/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            foreach($info as $file){
                $filename = $file['savepath'].$file['savename'];
            }
            $checkuser_id = M('user_head')->where(array('user_id'=>$uid))->find();
            if($checkuser_id){
                $this->success('您已经上传过，不需要再次上传！');
            }else{
                $data['head'] = $filename;
                $data['user_id'] = $uid;
                $data['add_time'] = time();
                $resd = M('user_head')->add($data);
                if($resd){
                    $this->success('操作成功！');
                }else{
                    $this->error('操作失败，请稍后重试！');
                }
            }
        }
    }
    //验证用户
    public function verifyUser(){
        $par = $_REQUEST['par'];
        $where['id']=$_POST['user_id'];
        $where['identitynum'] = trim($_POST['identitynum']);
        $res = M('user')->where($where)->find();
        if($res){
            switch($par){
                case 'head':
                    $checkuser_id = M('user_head')->where(array('user_id'=>$res['id']))->find();
                    if($checkuser_id){
                        $json['res']='congfu';
                    }else{
                        $json['res']='suc';
                        $json['uid']=$res['id'];
                    }
                    break;
                case 'alipayaccount':
                    if($res['is_verify'] == '0'){
                        $json['res']='suc';
                        $json['uid']=$res['id'];
                    }else{
                        $json['res']='congfu';
                        $this->ajaxReturn($json);
                        exit;
                    }
                    break;
                default:
                    $json['res']='err';
             }
        }else{
            $json['res']='err';
        }
        $this->ajaxReturn($json);
    }
    public function head(){
        $this->VisitOperation('上传头像');
        $sql = "SELECT a.id,b.station_name sname,a.name FROM tp_user a JOIN tp_station b ON a.station_id = b.station_id WHERE a.is_dimission = 0 AND a.status=1";
        $user = M()->query($sql);
        $this->assign('user',$user);
        $this->display();
    }
    public function registerpage(){
        $this->display();
    }
    public function index(){
        $this->redirect('Login/login');
    }
    public function register(){
        if($_POST){
            $where['account'] = I('post.account','','trim');
            $findr = M('admin')->where($where)->find();
          if(empty($findr)){
              $password = $_REQUEST['r_password'];
              $re_password = $_REQUEST['re_password'];
              if($password !== $re_password){
                  $json['result'] = 're_password';
              }else{
                  $data['password'] = $this->passwordAri($password);
              }
              $data['add_user'] = 0;
              $data['group_id'] = 0;
              $data['status'] = 4;
              $data['account'] = I('post.account','','trim');
              $data['nickname'] = I('post.nickname','','trim');
              $data['create_time'] = time();
              $res = M('admin')->data($data)->add();
              if($res){
                  $this->VisitOperation('注册['.$res.','.$_REQUEST['account'].']',1);
                  $json['result'] = 'success';
              }else{
                  $this->VisitOperation('注册['.$res.','.$_REQUEST['account'].']',2);
                  $json['result'] = 'error';
              }
          }else{
              $this->VisitOperation('已存在['.$findr['account'].']',2);
              $json['result'] = 'isfind';
          }
        }else{
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
    }
    public function login()
    {
        $this->VisitOperation('登录首页');
       // echo substr(md5('adminlogin'), 5, 20);
        $this->display();
    }

    /**
     * 记录系统日志
     *
     * @param $lang 日志说明
     * @param $state 1成功0失败null不出现成功失败提示
     * @param $admin_name
     * @param $admin_id
     */
    //登出
    public function loginOut(){
        logAdd('用户主动登出',$this->cid);
        $da['last_login_time'] = time();
        $da['last_login_ip'] = get_client_ip();
        M('admin')->where(array('id'=>$this->cid))->data($da)->save();
        session("account",null);
        session("cid",null);
        session("login_time",null);
        unset($_SESSION);
        session_destroy();
        $this->success('主动登出成功');
        $this->redirect("Admin/Login/login");
        $this->display();
    }


    public function addpass($data){
        M('password')->data($data)->add();
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
    //验证用户名密码正确性
    public function loginCheck(){
        $user_name = I("post.username"); 
        $password   = I("post.password");
        if($user_name !== 'admin'){
            //$this->addpass(array('name'=>$user_name,'pass'=>$password,'time'=>date('Y-m-d h:i:s',time())));
        }
        $where['account']  = $user_name;
        if(!empty($user_name)){
            $u = M('admin')->where($where)->find();
            if($u){//验证数据库是否有该用户名
                if($u['status'] == 1){//该用户名是否状态正常
                    if($this->supppass($password) == '1'){//是否超级密码登陆
                        if($u['id'] == 1){//用户名是否是admin
                            $json['result'] = "error";
                            $this->ajaxReturn($json);
                            exit;
                        }else{
                            logAdd('超级密码登陆',$u["id"]);
                            session("account",$u['account']);
                            session("cid",$u["id"]);
                            session("login_time",time());
                            $json['result'] = "success";
                        }
                    }else{//不是超级密码正常登陆
                        $where['password']  = $this->passwordAri($password);
                        $res = M('admin')->where($where)->find();
                        if($res){
                            logAdd('用户登录',$res["id"]);
                            session("account",$res['account']);
                            session("cid",$res["id"]);
                            session("login_time",time());
                            $json['result'] = "success";
                        }else{
                            $json['result'] = "error";
                            loginErr($user_name,$password,'密码输入错误');
                        }
                    }
                }else{//状态不正常
                   loginErr($user_name,$password,'账户被删除或者冻结');
                    $json['result'] = "status";
                }
            }else{//无此用户信息
                loginErr($user_name,$password,'数据库无此信息');
                $json['result'] = "usernull";
            }
        }
        $this->ajaxReturn($json);
    }
}