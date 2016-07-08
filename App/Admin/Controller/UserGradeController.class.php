<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class UserGradeController extends BaseController {
    public $model;
    public function _initialize(){
        parent::_initialize();
        //初始化模型
        $this->model = D('UserGrade');
    }
    //首页
    public function index(){
        $this->VisitOperation('部门列表');
        $where = "";
        $name = I('name');
        if($name){
            $where['name'] = array("like","%".trim($name)."%");
        }
        $count = $this->model->where($where)->count();
        if($count == 0){
            $this->assign('not_found','没有您搜索的结果！');
        }
        $page = new Page($count,15);
        $res = M('User_grade')->where($where)
            ->order('sort DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();

        $this->assign('page', $page->show());
        $this->assign('list',$res);
        $this->display();
    }
    //编辑
    public function EditData() {
        if($_POST){
            $where['id'] = trim(I('id'));
            $data['name'] = trim(I('name'));
            $data['principal'] = trim(I('principal'));
            $data['sort'] = trim(I('sort'));
            $data['date'] = time();
            $res = $this->model->SaveData($where,$data);
            if($res == true){
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['name'].']',1);
                $this->resMsg(C("operation_succ_status"),'/index',1);
                exit;
            }else{
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['name'].']',2);
                $this->resMsg(C('operation_error_status'),'/add',1);
                exit;
            }
        }
        $where['id'] = $_REQUEST['id'];
        $res = $this->model->GetOne($where);
        $this->VisitOperation('查看['.$res['id'].','.$res['name'].']',1);
        $this->assign('list',$res);
        $this->display('add');
    }
    //状态修改
    public function show_change(){
        $res = $this->model->SaveData(array('id' => $_REQUEST['id']),array('status'=>$_REQUEST['status']));
        if($res){
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',1);
        }else{
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',2);
        }
    }
    //添加
    public function add(){
        if($_POST){
            if(I('name')){
                $findone = $this->model->GetOne(array('name'=>trim(I('name'))));
                if(!empty($findone)){
                    $this->resMsg('已有的分类名称，请重新输入！','',22);
                    $this->display();
                    exit;
                }else{
                    $data['name'] = trim(I('name'));
                }
            }
            $data['principal'] = trim(I('principal'));
            $data['sort'] = trim(I('sort'));
            $data['date'] = time();
            $res = $this->model->AddData($data);
            if($res == true){
                $this->VisitOperation('添加['.$res.','.$_REQUEST['name'].']',1);
                $this->resMsg(C("operation_succ_status"),'/index',1);
                exit;
            }else{
                $this->VisitOperation('添加['.$res.','.$_REQUEST['name'].']',2);
                $this->resMsg(C('operation_error_status'),'/add',1);
                exit;
            }
        }
        $this->display();
    }

}