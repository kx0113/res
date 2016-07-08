<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/9
 * Time: 12:53
 */

namespace Admin\Controller;
use Think\Controller;
use Common\ORG\Util\Page;
class RegimeController extends BaseController
{
    public $db;
    public function _initialize(){
        parent::_initialize();
        $this->db = M('regime'); // 实例化User对象
    }
    //首页
    public function index(){
        $this->VisitOperation('公司规章制度');
        $where['status'] = 1;
        // import('ORG.Util.Page'); // 导入分页类
        $count = $this->db->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $this->db->where($where)->order('add_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $Page->show());
        $this->display();
    }
    public function commonData(){
        $id = $_REQUEST['id'];
        if(empty($id)){exit;}
        $res = $this->db->where(array('id'=>$id,'status'=>1))->find();
        $this->assign('list', $res);
        $this->VisitOperation('查看['.$res['name'].','.$_REQUEST['id'].']');
    }
    public function look(){
        $this->commonData();
        $this->display();
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
        $res = $this->db->where($where)->data($data)->save();
        if ($res) {
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',1);
            $json['result'] = 'success';
        } else {
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',2);
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
    }
    public function editData(){
        if(IS_POST){
           // dump($_REQUEST);exit;
            $where['id'] = $_REQUEST['id'];
            $data['name'] = $_REQUEST['name'];
            $data['text'] = $_REQUEST['text'];
            $data['updata_time'] = time();
          //  $data['add_user'] = $this->cid;
            $res= $this->db->where($where)->data($data)->save();
            if($res){
                $this->VisitOperation('编辑['.$_REQUEST['id'].','.$_REQUEST['name'].']',1);
                $this->resMsg(C("operation_succ_status"),'/index',1);
            }else{
                $this->VisitOperation('编辑['.$_REQUEST['id'].','.$_REQUEST['name'].']',2);
                $this->resMsg(C('operation_error_status'),'/index',1);
            }
        }
        $this->commonData();
        $this->display('add');
    }
    public function add(){
        if(IS_POST){
          //  dump($_REQUEST);exit;
            if(empty($_REQUEST['name'])){
                $this->error('请输入标题');
                exit;
            }
            $data['name'] = $_REQUEST['name'];
            $data['text'] = $_REQUEST['text'];
            $data['add_time'] = time();
            $data['add_user'] = $this->cid;
            $res= $this->db->add($data);
            if($res > 0){
                $this->VisitOperation('添加['.$res.','.$_REQUEST['name'].']',1);
                $this->resMsg(C("operation_succ_status"),'/index',1);
            }else{
                $this->VisitOperation('添加['.$res.','.$_REQUEST['name'].']',2);
                $this->resMsg(C('operation_error_status'),'/index',1);
            }
        }
        $this->display();
    }

}