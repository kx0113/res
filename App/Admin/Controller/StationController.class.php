<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class StationController extends BaseController {
    public $station;
    public function _initialize(){
        parent::_initialize();
        //模型初始化
        $this->station = D('Station');
    }
    //首页
    public function index(){
        $this->VisitOperation('职位列表');
        $where['station_status'] = 1;
        $name = I('name');
        //搜索
        if($name){
            $where['station_name'] = array("like","%".trim($name)."%");
        }
        $count = $this->station->where($where)->count();
        if($name){
            if($count == 0){
                $this->assign('not_found',2);
            }
        }else{
            if($count == 0){
                $this->assign('not_found',1);
            }
        }
        $page = new Page($count,20);
        $res = $this->station->where($where)
            ->order('station_id DESC')
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
             foreach ($var as $k=>$v){$del_id[] = $v;}
            $where['station_id'] = array('in',$del_id) ;
        }else{
            $where['station_id'] = $_REQUEST['station_id'];
         }
        $data['station_status'] = $_REQUEST['station_status'];
        $res = $this->station->SaveData($where,$data);
        if($res !== false){
            $json['result'] = 'success';
        }
        else{
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
     }
    //修改
    public function EditData() {
        if(empty($_REQUEST['station_id'])){ exit; }
      if($_POST){
          $where['station_id'] = I('station_id');
          $data['station_name'] = $_REQUEST['station_name'];
          $data['station_sort'] = $_REQUEST['station_sort'];
          $data['station_time'] = time();
          if($this->station->create()){
              $addstation = $this->station->SaveData($where,$data);
              if($addstation){
                  $this->VisitOperation('修改['.$_REQUEST['station_id'].','.$_REQUEST['station_name'].']',1);
                  $this->resMsg(C("operation_succ_status"),'/index',1);
                  exit;
              }else{
                  $this->VisitOperation('修改['.$_REQUEST['station_id'].','.$_REQUEST['station_name'].']',2);
                  $this->resMsg(C("operation_error_status"),'/index',1);
                  exit;
              }
          }
          else{
              $error = $this->station->getError();
              $this->resMsg($error,'',2);
          }
      }
        $where['station_id'] = $_REQUEST['station_id'];
        $res = $this->station->where($where)->find();
        $this->VisitOperation('查看['.$res['station_id'].','.$res['station_name'].']',1);
        $this->assign('list',$res);
        $this->display('add');
    }
    //修改状态
    public function show_change()
    {
        $where['station_id'] = $_REQUEST['station_id'];
        $data['station_status'] = $_REQUEST['station_status'];
        $res = $this->station->where($where)->data($data)->save();
        if($res){
            $this->VisitOperation('删除['.$_REQUEST['station_id'].','.$_REQUEST['station_status'].']',1);
            $json['res'] = 'suc';
        }else{
            $this->VisitOperation('删除['.$_REQUEST['station_id'].','.$_REQUEST['station_status'].']',2);
            $json['res'] = 'err';
        }
        $this->ajaxReturn($json);
    }
    //添加
    public function add(){
        if($_POST){
            $data['station_name'] = trim($_REQUEST['station_name']);
            $data['station_sort'] = $_REQUEST['station_sort'];
            $data['station_time'] = time();
            if($this->station->create($data)){
                $addstatio  = $this->station->AddData($data);
                if($addstatio){
                    $this->VisitOperation('添加['.$addstatio.','.$_REQUEST['station_name'].']',1);
                    $this->resMsg(C("operation_succ_status"),'/index',1);
                    exit;
                }else{
                    $this->VisitOperation('添加['.$addstatio.','.$_REQUEST['station_name'].']',2);
                    $this->resMsg(C("operation_error_status"),'/add',1);
                   exit;
                }
            }
            else{
                 $error = $this->station->getError();
                $this->resMsg($error,'',2);

            }
        }
        $this->display();
    }
}