<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class ReserveController extends BaseController {
    public $reserve;
    public function _initialize(){
        parent::_initialize();
        //模型初始化
        $this->reserve = D('Reserve');
    }
    //首页
    public function index(){

        $this->VisitOperation('储备人员列表');
        $where['reserve_status'] = 1;
        //搜索
        $fdata = readPost($_REQUEST);
        if (!empty($fdata["name"])) {
            if ($fdata["name"] != "") {
                $where["reserve_name"] = array("like", "%" . trim($fdata["name"]) . "%");
            }
        }
        $this->assign("fdata", $fdata);
        $count = $this->reserve->where($where)->count();
        if($fdata["name"]){
            if($count == 0){
                $this->assign('not_found',2);
            }
        }else{
            if($count == 0){
                $this->assign('not_found',1);
            }
        }
        $page = new Page($count,10);
        $res = $this->reserve->where($where)
            ->order('reserve_id DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        //视图展示数据集
        $this->assign('page', $page->show());
        $this->assign('name',$name);
        $this->assign('list',$res);
        $this->display();
    }
    //删除
    public function delete_data(){
        $list = $_REQUEST['list'];
        if($list){
            $var=explode(",",$list);
             foreach ($var as $k=>$v)
             {
                 $del_id[] = $v;
             }
            $where['reserve_id'] = array('in',$del_id);
        }else{
            $where['reserve_id'] = $_REQUEST['reserve_id'];
         }
        $data['reserve_status'] = $_REQUEST['reserve_status'];
        $res = $this->reserve->SaveData($where,$data);
        if($res !== false){
            $this->VisitOperation('删除['.$_REQUEST['reserve_id'].','.$_REQUEST['reserve_status'].']',1);
            $json['result'] = 'success';
        }
        else{
            $this->VisitOperation('删除['.$_REQUEST['reserve_id'].','.$_REQUEST['reserve_status'].']',2);
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
     }
    //修改
    public function EditData() {
        if(empty($_REQUEST['reserve_id'])){ exit; }
      if($_POST){
          $where['reserve_id'] = I('reserve_id');
          $data['reserve_name'] = trim($_REQUEST['reserve_name']);
          $data['reserve_sex'] = intval($_REQUEST['reserve_sex']);
          $data['reserve_birthday'] =  strtotime($_REQUEST['reserve_birthday']);
          $data['station_id'] = intval($_REQUEST['station_id']);
          $data['reserve_trace'] = trim($_REQUEST['reserve_trace']);
          $data['reserve_tel'] = trim($_REQUEST['reserve_tel']);
          $data['reserve_describe'] = trim($_REQUEST['reserve_describe']);
          $data['reserve_resumeurl'] = trim($_REQUEST['reserve_resumeurl']);
          $data['reserve_lastedit'] = time();
          if($this->reserve->create()){
              if($this->reserve->SaveData($where,$data)){
                  $this->VisitOperation('修改['.$_REQUEST['reserve_id'].','.$_REQUEST['reserve_name'].']',1);
                  $this->resMsg(C("operation_succ_status"),'/index',1);
                  exit;
              }else{
                  $this->VisitOperation('修改['.$_REQUEST['reserve_id'].','.$_REQUEST['reserve_name'].']',2);
                  $this->resMsg(C('operation_error_status'),'/index',1);
                  exit;
              }
          }
          else{
              $error = $this->reserve->getError();
              $this->resMsg($error,'',2);
              exit;
          }
      }
        $where['reserve_id'] = $_REQUEST['reserve_id'];
        $res = $this->reserve->where($where)->find();
        $this->assign('station_list',$this->GetStation());
        $this->assign('list',$res);
        $this->VisitOperation('查看['.$res['reserve_id'].','.$res['reserve_name'].']',1);
        $this->display('add');
    }
    //修改状态
    public function show_change(){  
         $res = $this->reserve
         ->where(array('id' => $_REQUEST['id']))
         ->save(array('status'=>$_REQUEST['status']));
    }
    //添加
    public function add(){
        if(IS_POST){
            $data['reserve_name'] = trim($_REQUEST['reserve_name']);
            $data['reserve_sex'] = intval($_REQUEST['reserve_sex']);
            $data['reserve_birthday'] =  strtotime($_REQUEST['reserve_birthday']);
            $data['station_id'] = intval($_REQUEST['station_id']);
            $data['reserve_trace'] = trim($_REQUEST['reserve_trace']);
            $data['reserve_tel'] = trim($_REQUEST['reserve_tel']);
            $data['reserve_describe'] = trim($_REQUEST['reserve_describe']);
            $data['reserve_resumeurl'] = trim($_REQUEST['reserve_resumeurl']);
            $data['reserve_addtime'] = time();
            if($this->reserve->create($data)){
                $addres = $this->reserve->AddData($data);
                if($addres){
                    $this->VisitOperation('添加['.$addres.','.$_REQUEST['reserve_name'].']',1);
                    $this->resMsg(C("operation_succ_status"),'/index',1);
                    exit;
                }else{
                    $this->VisitOperation('添加['.$addres.','.$_REQUEST['reserve_name'].']',2);
                    $this->resMsg(C('operation_error_status'),'/add',1);
                    exit;
                }
            }
            else{
                $error = $this->reserve->getError();
                $this->resMsg($error,'',2);
                exit;
            }
        }
        $this->assign('station_list',$this->GetStation());
        $this->display();
    }
    /**
     * 获取职位列表
     */
    public function GetStation(){
        $station_where['station_status'] = 1;
        $station_order = 'station_sort DESC';
        $station_field = 'station_id,station_name';
        $station_list = D('Station')->GetList1($station_where,$station_order,$station_field);
        return $station_list;
    }
}