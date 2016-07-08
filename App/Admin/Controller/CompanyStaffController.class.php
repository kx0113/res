<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class CompanyStaffController extends BaseController {
    public $model;
    public function _initialize(){
        parent::_initialize();
        $this->model = D("CompanyStaff");
    }
    public function dimission(){
        $res = M('company_staff')->where(array('status'=>1,'is_dimission'=>1))->count();
        $this->display();
    }
    //首页
    public function index(){
        $fdata = readPost($_REQUEST);
        $now_Y = date("Y", time());
        $now_m = date("m", time());
        $now_d = date("d", time());
        $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
        $now_low = strtotime($now_time);
        $time1 = $now_low - 86400 * 15;
        $time2 = $now_low + 86400 * 0;
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

        $where = array();
        if(!empty($_GET['par'])){
            $where["is_dimission"] = 1;
            $this->assign('dimission_con',1);
        }
        $where["add_time"] = $xtime;
        $where['status'] = 1;
        if (!empty($fdata["name"])) {
            if ($fdata["name"] != "") {
                $where["name"] = array("like", "%" . trim($fdata["name"]) . "%");
            }
        }
        $count = $this->model->where($where)->count();
        if(!empty($_GET['par'])){
            $this->assign('dimission_count',$count);
        }
        /*if($name){
            if($count == 0){
                $this->assign('not_found',2);
            }
        }else{
            if($count == 0){
                $this->assign('not_found',1);
            }
        }*/
        $page = new Page($count,20);
        $res = $this->model->where($where)
        ->order('id DESC')
        ->limit($page->firstRow.','.$page->listRows)
        ->select();
        $this->assign('page', $page->show());
        $this->assign('list',$res);
        $this->display();
    }
    //分类删除
    public function delete_data(){
        $list = $_REQUEST['list'];
        if($list){
            $var=explode(",",$list);
            foreach ($var as $k=>$v)
            {
                $del_id[] = $v;
            }
            $where['id'] = array('in',$del_id);
        }else{
            $where['id'] = $_REQUEST['id'];
        }
        $data['status'] = $_REQUEST['status'];
        $res = $this->model->SaveData($where,$data);
        if($res !== false){
            $json['result'] = 'success';
        }
        else{
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
    }
    //修改
    public function save_data() {
        if(empty($_REQUEST['id'])){ exit; }
        if(IS_POST !== false){
            $where['id'] = I('id');
            $data['name'] = trim($_REQUEST['name']);
            $data['sex'] = intval($_REQUEST['sex']);
            $data['birthday'] = strtotime($_REQUEST['birthday']);
            $data['native_place'] = trim($_REQUEST['native_place']);  //籍贯
            $data['nation'] = trim($_REQUEST['nation']); //民族
            $data['identity_card'] = trim($_REQUEST['identity_card']); //身份证
            $data['in_addressing'] = trim($_REQUEST['in_addressing']); //现居址
            $data['section_id'] = $_REQUEST['section_id']; //部门
            $data['station_id'] = $_REQUEST['station_id']; //职位
            $data['tel'] = trim($_REQUEST['tel']);
            $data['workqq'] = trim($_REQUEST['workqq']);
            $data['entry_time'] = strtotime($_REQUEST['entry_time']); //入职时间
            $data['predict_insure'] = strtotime($_REQUEST['predict_insure']); //预计投保时间
            $data['insure_situation'] = intval($_REQUEST['insure_situation']); //投保情况
            $data['remark'] = trim($_REQUEST['remark']); //备注
            $data['is_dimission'] = intval($_REQUEST['is_dimission']); //是否离职
            $data['last_edit'] = time();
            if($this->model->create($data)){
                if($this->model->SaveData($where,$data)){
                    $this->resMsg(C("operation_succ_status"),'/index',1);
                    exit;
                }else{
                    $this->resMsg(C('operation_error_status'),'/add',1);
                    exit;
                }
            } else{
                $error = $this->model->getError();
                $this->resMsg($error,'',2);
                exit;
            }
        }
        $where['id'] = $_REQUEST['id'];
        $res = $this->model->where($where)->find();
        //职位
        $this->assign('station_list',$this->GetStation());
        //部门
        $this->UserGrade();
        $this->assign('list',$res);
        $this->display('add');
    }
    //分类修改状态
    public function show_change(){
        //$res = $this->user
        //    ->where(array('id' => $_REQUEST['id']))
        //    ->save(array('status'=>$_REQUEST['status']));
    }
    //分类添加
    public function add(){
        if(IS_POST !== false){
            $data['name'] = trim($_REQUEST['name']);
            $data['sex'] = intval($_REQUEST['sex']);
            $data['birthday'] = strtotime($_REQUEST['birthday']);
            $data['native_place'] = trim($_REQUEST['native_place']);  //籍贯
            $data['nation'] = trim($_REQUEST['nation']); //民族
            $data['identity_card'] = trim($_REQUEST['identity_card']); //身份证
            $data['in_addressing'] = trim($_REQUEST['in_addressing']); //现居址
            $data['section_id'] = $_REQUEST['section_id']; //部门
            $data['station_id'] = $_REQUEST['station_id']; //职位
            $data['tel'] = trim($_REQUEST['tel']);
            $data['workqq'] = trim($_REQUEST['workqq']);
            $data['entry_time'] = strtotime($_REQUEST['entry_time']); //入职时间
            $data['predict_insure'] = strtotime($_REQUEST['predict_insure']); //预计投保时间
            $data['insure_situation'] = intval($_REQUEST['insure_situation']); //投保情况
            $data['remark'] = trim($_REQUEST['remark']); //备注
            $data['is_dimission'] = intval($_REQUEST['is_dimission']); //是否离职
            $data['add_time'] = time();
            if($this->model->create($data)){
                if($this->model->AddData($data)){
                    $this->resMsg(C("operation_succ_status"),'/index',1);
                    exit;
                }else{
                    $this->resMsg(C('operation_error_status'),'/add',1);
                    exit;
                }
            } else{
                $error = $this->model->getError();
                $this->resMsg($error,'',2);
                exit;
            }
        }
        //职位

        $this->assign('station_list',$this->GetStation());
        //部门
        $this->UserGrade();
        $this->display();
    }
    //查询所属部门
    public function UserGrade(){
        $user_grade = M('UserGrade')->order('sort DESC')->where(array("status" => 1))->select();
        $this->assign("UserGrade", $user_grade);
    }
    /**
     * 获取职位列表
     */
    public function GetStation(){
        $station_where['station_status'] = 1;
        $station_order = 'station_sort DESC';
        $station_field = 'station_id,station_name';
        $station_list = D('Station')->where($station_where)->order($station_order)->field($station_field)->select();
        return $station_list;
    }






















}