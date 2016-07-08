<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class GoodsClassController extends BaseController {
    public $station;
    public function _initialize(){
        parent::_initialize();
        //模型初始化
        $this->station = D('GoodsClass');
    }
    //首页
    public function index(){
        /*$url =  $_SERVER['PATH_INFO'] ;
        $arr =  explode('/',$url) ;
        echo $arr[0].'/'.$arr[1];*/
        if(empty($_REQUEST['par'])){exit;}
        $where['class'] = $_REQUEST['par'];  //是面试通过人员
        switch ( $_REQUEST['par']){
            case 1:
                $this->VisitOperation('固定资产-物品分类');
                break;
            case 2:
                $this->VisitOperation('手机管理-手机型号');
                break;
            case 3:
                $this->VisitOperation('手机管理-产品分类');
                break;
            case 4:
                $this->VisitOperation('手机充值-运营商');
                break;
            case 5:
                $this->VisitOperation('手机充值-充值金额');
                break;
            case 6:
                $this->VisitOperation('推广手机登记册-产品分类');
                break;
            case 7:
                $this->VisitOperation('手机管理-部门分类');
                break;
            default:
                $this->VisitOperation('非法操作');
        }
        $this->assign('par',$_REQUEST['par']);
        $where['status'] = 1;
        $name = $_REQUEST['name'];
        //搜索
        if($name){
            $where['name'] = array("like","%".trim($name)."%");
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
            ->order('id DESC')
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
            $where['id'] = array('in',$del_id) ;
        }else{
            $where['id'] = $_REQUEST['id'];
         }
        $data['status'] = $_REQUEST['status'];
        $res = $this->station->SaveData($where,$data);
        if($res !== false){
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',1);
            $json['result'] = 'success';
        }
        else{
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',2);
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
     }
    //修改
    public function EditData() {
        if(empty($_REQUEST['id'])){ exit; }
        if(empty($_REQUEST['par'])){ exit; }
      if($_POST){
          $where['id'] = I('id');
          if($_REQUEST['par'] == '121218'){
              $data['value'] = trim($_REQUEST['value']);
          }else{
              $data['value'] = 0;
          }
          $data['name'] = $_REQUEST['name'];
          $data['sort'] = $_REQUEST['sort'];
          $data['time'] = time();
          if($this->station->create()){
              if($this->station->SaveData($where,$data)){
                  $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['name'].']',1);
                  $this->resMsg(C("operation_succ_status"),'/index/par/'.$_REQUEST['par'],1);
                  exit;
              }else{
                  $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['name'].']',2);
                  $this->resMsg(C("operation_error_status"),'/index/par/'.$_REQUEST['par'],1);
                  exit;
              }
          }
          else{
              $error = $this->station->getError();
              $this->resMsg($error,'',2);
              exit;
          }
      }
        $where['id'] = $_REQUEST['id'];
        if($_REQUEST['par'] == '8') {
            $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(7));
            $this->assign('par', $_REQUEST['par']);
        }
        $res = $this->station->where($where)->find();
        $this->assign('list',$res);
        $this->VisitOperation('查看['.$res['id'].','.$res['name'].']',1);
        $this->display('add');
    }
    //添加
    public function add(){
        if(empty($_REQUEST['par'])){exit;}
        $this->assign('par',$_REQUEST['par']);
        if($_POST){
            if($_REQUEST['par'] == '61212' || $_REQUEST['par'] == '12128'){
                $data['value'] = trim($_REQUEST['value']);
            }else{
                $data['value'] = 0;
            }
            $data['name'] = trim($_REQUEST['name']);
            $data['class'] = trim($_REQUEST['par']);
            $data['sort'] = $_REQUEST['sort'];
            $data['time'] = time();
            if($this->station->create($data)){
                $addgoodsclass = $this->station->AddData($data);
                if($addgoodsclass){
                    $this->VisitOperation('添加['.$addgoodsclass.','.$_REQUEST['name'].']',1);
                    $this->resMsg(C("operation_succ_status"),'/index/par/'.$_REQUEST['par'],1);
                    exit;
                }else{
                    $this->VisitOperation('添加['.$addgoodsclass.','.$_REQUEST['name'].']',2);
                    $this->resMsg(C("operation_error_status"),'/add/par/'.$_REQUEST['par'],1);
                   exit;
                }
            }
            else{
                 $error = $this->station->getError();
                $this->resMsg($error,'',2);
                exit;

            }
        }
        if($_REQUEST['par'] == 8){
            $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(7));
        }
        $this->display();
    }
}