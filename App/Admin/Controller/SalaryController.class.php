<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2016/6/26
 * Time: 15:37
 */

namespace Admin\Controller;
use Think\Controller;
use Common\ORG\Util\Page;
class SalaryController extends BaseController
{
    public $db;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = M('res'); // 实例化User对象
    }
    private function dynamic(){
        for ($x=1; $x<=10; $x++) {
          //  $arr = D('GoodsClass')->GetGoodsClass(3);
            echo $x;
        }

      //  echo count($arr);

    }
    //首页
    public function index(){
        $this->dynamic();
        $this->VisitOperation('Res首页');
        $fdata = readPost($_REQUEST);
        $now_Y = date("Y", time());
        $now_m = date("m", time());
        $now_d = date("d", time());
        $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
        $now_low = strtotime($now_time);
        $time1 = $now_low - 86400 * 60;
        $time2 = $now_low + 86400 * 3;
        if (empty($fdata["start_time"])) {
            $fdata["start_time"] = date("Y-m-d", $time1);
        }
        if (empty($fdata["end_time"])) {
            $fdata["end_time"] = date("Y-m-d", $time2);
        }
        if (!empty($fdata["name"])) {
            if ($fdata["name"] != "") {
                $where["name"] = array("like", "%" . trim($fdata["name"]) . "%");
            }
        }
        $this->assign("fdata", $fdata);
        $start_time = strtotime($fdata["start_time"]);
        $end_time = strtotime($fdata["end_time"]);
        $xtime = array(array("gt", $start_time), array("lt", $end_time));
        $where["add_time"] = $xtime;
        $where['status'] = 1;
        $count = $this->db->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 30); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出.
        $list = $this->db->where($where)->order('add_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
      //  $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(6));
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }
    public function add(){
        if(IS_POST){
            $data = readPost($_POST);
            $data['sub_time'] = strtotime($data['sub_time']);
            $data['add_time'] = time();
            $data['add_user'] = $this->cid;
       //   dump($data);exit;
            $res = $this->db->add($data);
            if ($res) {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['name'].']',1);
                $this->resMsg(C("operation_succ_status"), '/index', 1);
                exit;
            } else {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['name'].']',2);
                $this->resMsg(C('operation_error_status'), '/index', 1);
                exit;
            }
        }
        $this->goodsClassInfo();
        $this->display();
    }
    private function goodsClassInfo(){
        $this->assign('zf', D('GoodsClass')->GetGoodsClass(1));
        $this->assign('gm', D('GoodsClass')->GetGoodsClass(2));
    }
    //编辑
    function edit()
    {
        $id = I("get.id");
        if (IS_POST) {
            $data = readPost($_POST);
            $data["update_time"] = time();
            $data['sub_time'] = strtotime($data['sub_time']);
            $where = array(
                "id" => $id
            );
            $res = $this->db->where($where)->save($data);
            if ($res) {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['name'].']',1);
                $this->resMsg(C("operation_succ_status"), '/index', 1);
                exit;
            } else {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['name'].']',2);
                $this->resMsg(C('operation_error_status'), '/index', 1);
                exit;
            }
        }
        $this->goodsClassInfo();
        $row = $this->db->where(array("id" => $id))->find();
        $this->VisitOperation('查看['.$_REQUEST['id'].','.$row['name'].']',1);
        $this->assign("list", $row);
       // dump($row);
        $this->display('add');
    }
    //删除
    public function delete_data(){
        $where['id'] = $_REQUEST['id'];
        $data['status'] = $_REQUEST['status'];
        $del = $_REQUEST['par'];
        switch ($del) {
            case 'index':
                $res = $this->db->where($where)->data($data)->save();
                break;
            case 'out':
                $res = $this->mobileout->where($where)->data($data)->save();
                break;
            case 'cardlist':
                $res = M('recharge')->where($where)->data($data)->save();
                break;
            case 'list':
                /* $list = $_REQUEST['list'];
                 if($list){
                     $var=explode(",",$list);
                     foreach ($var as $k=>$v)
                     {
                         $del_id[] = $v;
                     }
                     $where['id'] = array('in',$del_id);
                 }*/
                break;
            default:
                exit;
        }
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
}


















































