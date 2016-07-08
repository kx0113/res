<?php
namespace Admin\Controller;

use Common\ORG\Util\Page;

class UserController extends BaseController
{
    public $user;

    public function _initialize()
    {
        parent::_initialize();
        $this->user = M('company');
    }
    //支付宝核对
    public function alipay(){
        $this->VisitOperation('支付宝核对');
        $fdata = readPost($_REQUEST);
        $this->assign("fdata", $fdata);
        if (!empty($fdata["section_id"])) {
            if ($fdata["section_id"] != "0") {
                $where["section_id"] = array("like", "%" . trim($fdata["section_id"]) . "%");
            }
        }
        if (!empty($fdata["station_id"])) {
            if ($fdata["station_id"] != "0") {
                $where["station_id"] = array("like", "%" . trim($fdata["station_id"]) . "%");
            }
        }
        if (!empty($fdata["name"])) {
            if ($fdata["name"] != "") {
                $where["name"] = array("like","%".trim($fdata["name"])."%");
            }
        }
        if (!empty($fdata["alipayaccount"])) {
            if ($fdata["alipayaccount"] != "") {
                $where["alipayaccount"] = array("like","%".trim($fdata["alipayaccount"])."%");
            }
        }
        $count =M('user')->where($where)->count();
        $page = new Page($count, 21);
        $res = M('user')->where($where)->order('is_verify DESC')->field('id,name,is_verify,alipayaccount,section_id,station_id,sex')->limit($page->firstRow.','.$page->listRows)->select();
       // $res = M('user')->order('is_verify DESC')->select();
        $this->assign('list',$res);
        $zverify = M('user')->where(array('status'=>1))->count();
        $yverify = M('user')->where(array('is_verify'=>1))->count();
        $wverify = M('user')->where(array('is_verify'=>0))->count();
        $this->assign('zcount',$zverify);
        $this->assign('yverify',$yverify);
        $this->assign('page', $page->show());
        $this->assign('wverify',$wverify);
        $this->UserGrade();
        $this->assign('station_list',$this->GetStation());
        $this->display();
    }
    public function headimg(){
        $where = '';
        $this->VisitOperation('员工照片');
        $count =M('user_head')->where($where)->count();
        $page = new Page($count, 21);
        $res = M('user_head')->order('id DESC')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$res);
        $this->assign('page', $page->show());
        $this->assign('userheadpath',C('userheadpath'));
        $this->display();
    }
    public function ajaxverifyname(){
        $name= $_REQUEST['name'];
        $res = M('user')->where(array('name'=>trim($name)))->find();
        if($res){
            $json['res']='suc';
        }else{
            $json['res']='err';
        }
        $this->ajaxReturn($json);
    }
    //首页列表，条件搜索
    public function index()
    {
        $fdata = readPost($_REQUEST);
        $now_Y = date("Y", time());
        $now_m = date("m", time());
        $now_d = date("d", time());
        $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
        $now_low = strtotime($now_time);
        $time1 = $now_low - 86400 * C("start_time");
        $time2 = $now_low + 86400 * 1;
        if (empty($fdata["start_time"])) {
            $fdata["start_time"] = date("Y-m-d", $time1);
        }
        if (empty($fdata["end_time"])) {
            $fdata["end_time"] = date("Y-m-d", $time2);
        }
        $this->assign("fdata", $fdata);

        $start_time = strtotime($fdata["start_time"]);
        $end_time = strtotime($fdata["end_time"]);

        $xtime = array(array("gt", $start_time), array("lt", $end_time));
      //  $where["addtime"] = $xtime;
        if (!empty($fdata["section_id"])) {
            if ($fdata["section_id"] != "0") {
                $where["section_id"] = array("like", "%" . trim($fdata["section_id"]) . "%");
            }
        }
        if (!empty($fdata["station_id"])) {
            if ($fdata["station_id"] != "0") {
                $where["station_id"] = array("like", "%" . trim($fdata["station_id"]) . "%");
            }
        }
        if (!empty($fdata["name"])) {
            if ($fdata["name"] != "") {
                $where["name"] = array("like","%".trim($fdata["name"])."%");
            }
        }
        $where['status'] = '1';
        $count = $this->user->where($where)->count();
        $page = new Page($count, 20);
        $res = $this->user->where($where)
            ->order('add_user DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $this->assign('page', $page->show());
        $this->assign('list', $res);
        $this->display();
    }
    /**
     * 删除
     */
    public function delete_data()
    {
        $where['id'] = $_REQUEST['id'];
        $data['status'] = $_REQUEST['status'];
        $del = $_REQUEST['par'];
        switch ($del) {
            case 'index':
                $res = M('company')->where($where)->data($data)->save();
                break;
            default:
                exit;
        }
        if ($res !== false) {
            $json['result'] = 'success';
        } else {
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
    }
    //删除数据
    public function alterStatus()
    {
        if(I('param') == '1'){
            $where['id'] = $_REQUEST['id'];
        }elseif(I('param') == '2'){
            $list = $_REQUEST['list'];
            $var = explode(",", $list);
            foreach ($var as $k => $v) {
                $delID[] = $v;
            }
            $where = array('id' => array('in',$delID));
        }
        $data['status'] =  $_REQUEST['status'];
        $res = $this->user->Saveuser($where,$data);
        if ($res) {
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',1);
            $json['result'] = 'success';
        } else {
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',2);
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
    }
    //修改数据
    public function editData()
    {
        $where['id']=$_REQUEST['id'];
        if (IS_POST) {
            $data = readPost($_POST);
           // $data['add_time'] = time();
            $data['entry_time'] = strtotime($data['entry_time']);
          //  $data['payoff_time'] = $data['payoff_time'];
            $data['become_time'] = strtotime($data['become_time']);
            $data['dimission_time'] = strtotime($data['dimission_time']);
            $data['deliversalarytime'] = strtotime($data['deliversalarytime']);
           // $data['add_user'] = $this->cid;
            $adduser = $this->user->where($where)->data($data)->save();
            if($adduser){
                $this->resMsg(C("operation_succ_status"),'/index',1);exit;
            }else{
                $this->resMsg(C("operation_error_status"),'/add',1);exit;
            }
        }
        $this->editLookCommon();
    }
    //编辑跟查看公共函数
    public function editLookCommon(){
        $where['id'] = $_REQUEST['id'];
        $res = $this->user->where($where)->find();
        $this->assign('list', $res);
        $this->display('add');
    }
    //查看详细信息
    public function look(){
        $par = $_GET['par'];
        if(empty($par)){
            exit('非法操作!');
        }else{
            if ($_REQUEST['id'] == '') {
                exit('非法操作!');
            }
            $this->assign('par',$par);
        }
        $this->assign('look',I('get.look'));
        $where = array();
        $where['id'] = $_REQUEST['id'];
        $this->UserGrade();
        $this->assign('station_list',$this->GetStation());
        $res = $this->user->Getone($where);
        $this->assign('list', $res);
        $this->display('add');
        $this->VisitOperation('查看['.$res['id'].','.$res['name'].']',1);
    }
    //离职判定
    public function show_change()
    {
        $where['id'] = $_REQUEST['id'];
        switch ($_REQUEST['par'])
        {
            case 'is_dimission':
                $data['is_dimission'] = $_REQUEST['data'];
                $data['dimissiontime'] = time();
                $this->VisitOperation('修改离职['.$_REQUEST['id'].','.$_REQUEST['data'].']',1);
                break;
            case 'is_newpeople':
                $data['is_newpeople'] = $_REQUEST['data'];
                $this->VisitOperation('修改新员工['.$_REQUEST['id'].','.$_REQUEST['data'].']',1);
                break;
            default:
                exit('非法操作！');
        }
        $res = M('user')->where($where)->data($data)->save();
        if($res){
            $json['res'] = 'suc';
        }else{
            $json['res'] = 'err';
        }
        $this->ajaxReturn($json);
    }
    //添加用户
    public function add()
    {
        if (IS_POST) {
            $data = readPost($_POST);
           // dump($data);exit;
            $data['add_time'] = time();

            if(empty($data['entry_time'])){
                $data['entry_time']=0;
            }else{
                $data['entry_time'] = strtotime($data['entry_time']);
            }

            if(empty($data['become_time'])){
                $data['become_time']=0;
            }else{
                $data['become_time'] = strtotime($data['become_time']);
            }

            if(empty($data['dimission_time'])){
                $data['dimission_time']=0;
            }else{
                $data['dimission_time'] = strtotime($data['dimission_time']);
            }

            if(empty($data['deliversalarytime'])){
                $data['deliversalarytime']=0;
            }else{
                $data['deliversalarytime'] = strtotime($data['deliversalarytime']);
            }

         //   $data['payoff_time'] = strtotime($data['payoff_time']);
           // $data['become_time'] = strtotime($data['become_time']);
       //     $data['dimission_time'] = strtotime($data['dimission_time']);
         //   $data['deliversalarytime'] = strtotime($data['deliversalarytime']);
            $data['add_user'] = $this->cid;
            $adduser = $this->user->data($data)->add();
            if($adduser > 0){
                $this->resMsg(C("operation_succ_status"),'/index',1);exit;
            }else{
                $this->resMsg(C("operation_error_status"),'/add',1);exit;
            }
        }
        $this->display();
    }
}