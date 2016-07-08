<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
use Think\Controller;
class GroupController extends BaseController {
    public function _initialize(){
        parent::_initialize();
    }
    public function index(){
        $this->VisitOperation('账号用户组');
        $where = '';
        $DB = M('neiqin_group'); // 实例化User对象
        $count = $DB->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $DB->where($where)->order('creat_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }
    public function edit(){
        if(empty($_REQUEST['id'])) {exit;}
        $where['id'] = I('id');
        if(IS_POST){
            $data = readPost($_POST);
            $data['updata_time'] = time();
            $result = M('neiqin_group')->where($where)->data($data)->save();
            if ($result) {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['groupname'].']',1);
                $this->resMsg(C("operation_succ_status"), '/index', 1);
                exit;
            } else {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['groupname'].']',2);
                $this->resMsg(C('operation_error_status'), '/index', 1);
                exit;
            }
        }
        $this->assign('par',9);
        $admin = M('neiqin_group')->where($where)->find();
        $this->assign("row1", $admin);
        $this->VisitOperation('查看['.$admin['id'].','.$admin['groupname'].']',1);
        $this->display('add');
    }
    public function add(){
        if(IS_POST){
            $data = readPost($_POST);
            /*grouppower
            creat_time
            updata_time*/
            if(is_array($data['checkbox'])){
                $data['grouppower'] = serialize($data['checkbox']);
            }
            $data['creat_time']  = time();
            $data['add_user']  = $this->cid;
            $res = M('neiqin_group')->add($data);
            if ($res) {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['groupname'].']',1);
                $this->resMsg(C("operation_succ_status"), '/index', 1);
                exit;
            } else {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['groupname'].']',2);
                $this->resMsg(C('operation_error_status'), '/index', 1);
                exit;
            }
        }
        $system_module = M('menu_admin')->order('sort DESC')->where(array('status'=>1))->select();
        $this->assign("system_module", $system_module);
        $this->display();
    }
    //ajax 判断名称是否重复
    public function repetitionAjax()
    {
        $action = I("post.par");
        switch ($action) {
            case "group":
                $chkname = I('post.groupname', '', 'trim');
                $map = array(
                    "groupname" => $chkname
                );
                $num = M("neiqin_group")->where($map)->count();
                if ($num > 0) {
                    $json['result'] = '2';
                } else {
                    $json['result'] = '1';
                }
                $this->ajaxReturn($json);
                break;
            case "bank":
                $chkname = I('post.phonename', '', 'trim');
                $map = array(
                    "phonename" => $chkname
                );
                $num = M("store_card")->where($map)->count();
                if ($num > 0) {
                    $json['result'] = '2';
                } else {
                    $json['result'] = '1';
                }
                $this->ajaxReturn($json);
                break;
            case "tele":
                $chkname = I('post.telenumber', '', 'trim');
                $map = array(
                    "telenumber" => $chkname
                );
                $num = M("store_tele")->where($map)->count();
                if ($num > 0) {
                    $json['result'] = '2';
                } else {
                    $json['result'] = '1';
                }
                $this->ajaxReturn($json);
                break;
            case "qq":
                $qqcode = I('post.qqcode', '', 'trim');
                $map = array(
                    "qqcode" => $qqcode
                );
                $num = M("store_qq")->where($map)->count();
                if ($num > 0) {
                    $json['result'] = '2';
                } else {
                    $json['result'] = '1';
                }
                $this->ajaxReturn($json);
                break;
            default:
                break;
        }
    }
}