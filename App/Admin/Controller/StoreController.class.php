<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;

class StoreController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
    }

    public function index()
    {
        // $this->display();
    }
    public function yjindex()
    {
        $status = $_REQUEST['status'];
        if(!empty($status)){
            $where = '';
            $this->assign('nostat', 1);
            $this->VisitOperation('已提交反馈');
        }else{
            $where = '';
            $this->VisitOperation('管理员查看');
        }
        $DB = M('feedback'); // 实例化User对象
        //  import('ORG.Util.Page'); // 导入分页类
        $count = $DB->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $DB->where($where)->order('time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }
    public function yj()
    {
        if(IS_POST){
        $data = readPost($_POST);
            $data['time']  = time();
            $data['user_id']  = $this->cid;
        $res = M('feedback')->add($data);
        if ($res) {
            $this->VisitOperation('添加['.$res.','.$_REQUEST['title'].']',1);
            $this->resMsg('管理员已收到，并会尽快处理！', '/yj', 1);
            exit;
        } else {
            $this->VisitOperation('添加['.$res.','.$_REQUEST['title'].']',2);
            $this->resMsg(C('operation_error_status'), '/yj', 1);
            exit;
        }
        }
         $this->display();
    }
    //添加银行
    function bankadd()
    {
        $status = I('get.status');
        if (empty($status)) {
            $this->assign("status", $status);
        }
        $back = cookie("back");
        if (empty($back)) {
            $back = U("qqset");
        }

        if (IS_POST) {
            $data = readPost($_POST);
            $data["admin_id"] = $this->cid;
            $data["creat_time"] = strtotime($data["creat_time"]);
            $res = M("store_card")->add($data);
            if ($res) {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['uname'].']',1);
                $this->resMsg(C("operation_succ_status"), '/bank?stat=1', 1);
                exit;
            } else {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['uname'].']',2);
                $this->resMsg(C('operation_error_status'), '/bank?stat=1', 1);
                exit;
            }

        }

        $this->assign("back", $back);
        $this->display();
    }

//银行编辑
    function bankedit()
    {
        $status = I('get.status');
        if (empty($status)) {
            $this->assign("status", $status);
        }
        $id = I("get.id");
        $back = cookie("back");

        if (IS_POST) {
            $data = readPost($_POST);
            $data["creat_time"] = strtotime($data["creat_time"]);
            $where = array(
                "id" => $id
            );
            $res = M("store_card")->where($where)->save($data);
            if ($res) {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['uname'].']',1);
              //  $this->resMsg(C("operation_succ_status"), '/bank?stat=1', 1);
                $this->success('操作成功');
                redirect($back);
                exit;
            } else {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['uname'].']',2);
                $this->success('操作失败');
                redirect($back);
                exit;
            }
        }
        $this->assign("back", $back);
        $row = M("store_card")->where(array("id" => $id))->find();
        $this->assign("row", $row);
        $this->VisitOperation('查看['.$row['id'].','.$row['uname'].']',1);
        $this->display('bankadd');
    }

    //绑定银行
    function bank()
    {
        if($_REQUEST['stat'] == ''){exit('非法操作!');}
        switch ($_REQUEST['stat']){
            case 0:
                $this->VisitOperation('回收站-手机绑定银行');
                break;
            case 1:
                $this->VisitOperation('手机绑定银行');
                break;
            default:
                $this->VisitOperation('非法操作');
        }
        cookie("back", __SELF__);
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
        $this->assign("fdata", $fdata);

        $start_time = strtotime($fdata["start_time"]);
        $end_time = strtotime($fdata["end_time"]);

        $xtime = array(array("gt", $start_time), array("lt", $end_time));

        $where = array();
        $where["creat_time"] = $xtime;

        if (!empty($fdata["phonename"])) {
            if ($fdata["phonename"] != "") {
                $where["phonename"] = $fdata["phonename"];
            }
        }
        if (!empty($fdata["uname"])) {
            if ($fdata["uname"] != "") {
                $where["uname"] = array("like", "%" . trim($fdata["uname"]) . "%");
            }
        }
        if (!empty($fdata["mobie"])) {
            if ($fdata["mobie"] != "") {
                $where["mobie"] = array("like", "%" . trim($fdata["mobie"]) . "%");
            }
        }
        $stat = $_REQUEST['stat'];
        switch ($stat) {
            case "0":
                $where['status'] = 0;
                $this->assign('recycle', 1);
                break;
            case "1":
                $where['status'] = 1;
                break;
            default:
                break;
        }
        $DB = M('store_card'); // 实例化User对象
      //  import('ORG.Util.Page'); // 导入分页类
        $count = $DB->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 50); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出

        $list = $DB->where($where)->order('creat_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('stat', $stat);
        $this->display();
    }

    //手机卡编辑
    function teleedit()
    {
        $status = I('get.status');
        if (empty($status)) {
            $this->assign("status", $status);
        }
        $id = I("get.id");
        $back = cookie("back");
        if (IS_POST) {
            $data = readPost($_POST);

            $data["creat_time"] = strtotime($data["creat_time"]);
            $where = array(
                "id" => $id
            );
            $res = M("store_tele")->where($where)->save($data);
            if ($res) {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['telenumber'].']',1);
                redirect($back);
             //   $this->resMsg(C("operation_succ_status"), '/teleset?stat=1', 1);
                exit;
            } else {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['telenumber'].']',2);
                redirect($back);
                //   $this->resMsg(C('operation_error_status'), '/teleset?stat=1', 1);
                exit;
            }
        }

        $this->assign("back", $back);
        $row = M("store_tele")->where(array("id" => $id))->find();
        $this->assign("row", $row);
        $this->VisitOperation('查看['.$row['id'].','.$row['telenumber'].']',1);
        $this->display('teleadd');
    }

    //手机卡添加
    function teleadd()
    {
        $status = I('get.status');
        if (empty($status)) {
            $this->assign("status", $status);
        }
        $back = cookie("back");
        if (empty($back)) {
            $back = U("qqset");
        }
        if (IS_POST) {
            $data = readPost($_POST);
            $data["admin_id"] = $this->cid;
            $data["creat_time"] = strtotime($data["creat_time"]);
            $res = M("store_tele")->add($data);
            if ($res > 0) {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['telenumber'].']',1);
                $this->resMsg(C("operation_succ_status"), '/teleset?stat=1', 1);
                exit;
            } else {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['telenumber'].']',2);
                $this->resMsg(C('operation_error_status'), '/teleset?stat=1', 1);
                exit;
            }
        }

        $this->assign("back", $back);
        $this->display();
    }
    //手机卡首页
    function teleset()
    {
        if($_REQUEST['stat'] == ''){exit('非法操作!');}
        cookie("back", __SELF__);
        switch ($_REQUEST['stat']){
            case 0:
                $this->VisitOperation('回收站-手机卡登记册');
                break;
            case 1:
                $this->VisitOperation('手机卡登记册');
                break;
            default:
                $this->VisitOperation('非法操作');
        }
        $fdata = readPost($_REQUEST);
        $now_Y = date("Y", time());
        $now_m = date("m", time());
        $now_d = date("d", time());
        $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
        $now_low = strtotime($now_time);
        $time1 = $now_low - 86400 * 150;
        $time2 = $now_low + 86400 * 3;
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
        $where["creat_time"] = $xtime;

        if (!empty($fdata["telenumber"])) {
            if ($fdata["telenumber"] != "") {
                $where["telenumber"] = array("like", "%" . trim($fdata["telenumber"]) . "%");
            }
        }
        if (!empty($fdata["remark"])) {
            if ($fdata["remark"] != "") {
                $where["remark"] = array("like", "%" . trim($fdata["remark"]) . "%");
            }
        }
        if (!empty($fdata["address"])) {
            if ($fdata["address"] != "") {
                $where["address"] = array("like", "%" . trim($fdata["address"]) . "%");
            }
        }
        if (!empty($fdata["buyer"])) {
            if ($fdata["buyer"] != "") {
                $where["buyer"] = array("like", "%" . trim($fdata["buyer"]) . "%");
            }
        }
        $stat = $_REQUEST['stat'];
        switch ($stat) {
            case "0":
                $where['status'] = 0;
                $this->assign('recycle', 1);
                break;
            case "1":
                $where['status'] = 1;
                break;
            default:
                break;
        }
        $DB = M('store_tele'); // 实例化User对象
       // import('ORG.Util.Page'); // 导入分页类
        $count = $DB->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 50); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        $list = $DB->where($where)->order('creat_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $c = -1;
        if(IS_GET){
            if($count == 0){
                $this->assign('not_count', 1);
            }
        }
        $savec = "";
        foreach ($list as $nk => $nv) {
            $timestr = date("Y-m-d", $nv["creat_time"]);
            if ($timestr == $savec) {
                $list[$nk]["color"] = $c;
            } else {
                $savec = $timestr;
                $c = ($c + 1) % 7;
                $list[$nk]["color"] = $c;
            }
        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('stat', $stat);
        $this->display();
    }

    //qq添加
    function qqadd()
    {
        $status = I('get.status');
        if (empty($status)) {
            $this->assign("status", $status);
        }
        $back = cookie("back");
        if (empty($back)) {
            $back = U("qqset");
        }
        if (IS_POST) {
            $data = readPost($_POST);
            $data["admin_id"] = $this->cid;
            $data["creat_time"] = strtotime($data["creat_time"]);
            $res = M("store_qq")->add($data);
            if ($res) {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['qqcode'].']',1);
                $this->resMsg(C("operation_succ_status"), '/qqset?stat=1', 1);
                exit;
            } else {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['qqcode'].']',2);
                $this->resMsg(C('operation_error_status'), '/qqset?stat=1', 1);
                exit;
            }
        }

        $this->assign("back", $back);
        $this->display();
    }

    //qq编辑
    function qqedit()
    {
        $status = I('get.status');
        if (empty($status)) {
            $this->assign("status", $status);
        }
        $id = I("get.id");
        $back = cookie("back");

        if (IS_POST) {
            $data = readPost($_POST);
            $data["creat_time"] = strtotime($data["creat_time"]);
            $where = array(
                "id" => $id
            );
            $res = M("store_qq")->where($where)->save($data);
            if ($res) {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['qqcode'].']',1);
                $this->success('操作成功');
                redirect($back);
                exit;
            } else {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['qqcode'].']',2);
                redirect($back);// $this->resMsg(C('operation_error_status'), '/qqset?stat=1', 1);
                exit;
            }
        }
        $this->assign("back", $back);
        $row = M("store_qq")->where(array("id" => $id))->find();
        $this->assign("row", $row);

        $this->display('qqadd');
    }

    //qq列表页
    function qqset()
    {
        if($_REQUEST['stat'] == ''){exit('非法操作!');}
        $where = array();
        switch ($_REQUEST['stat']){
            case 0:
                $this->VisitOperation('回收站-公司QQ');
                break;
            case 1:
                $this->VisitOperation('公司QQ');
                break;
            default:
                $this->VisitOperation('非法操作');
        }
        cookie("back", __SELF__);
        $fdata = readPost($_REQUEST);
        $now_Y = date("Y", time());
        $now_m = date("m", time());
        $now_d = date("d", time());
        $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
        $now_low = strtotime($now_time);
        $time1 = $now_low - 86400 * 160;
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
        $where["creat_time"] = $xtime;

        if (!empty($fdata["qqcode"])) {
            if ($fdata["qqcode"] != "") {
                $where["qqcode"] = array("like", "%" . trim($fdata["qqcode"]) . "%");
            }
        }
        if (!empty($fdata["xphone"])) {
            if ($fdata["xphone"] != "") {
                $where["xphone"] = array("like", "%" . trim($fdata["xphone"]) . "%");
            }
        }
        if (!empty($fdata["remark"])) {
            if ($fdata["remark"] != "") {
                $where["remark"] = array("like", "%" . trim($fdata["remark"]) . "%");
            }
        }
        $stat = $_REQUEST['stat'];
        switch ($stat) {
            case "0":
                $where['status'] = 0;
                $this->assign('recycle', 1);
                break;
            case "1":
                $where['status'] = 1;
                break;
            default:
                break;
        }
        $DB = M('store_qq'); // 实例化User对象
        // import('ORG.Util.Page'); // 导入分页类
        $count = $DB->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 50); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出

        $list = $DB->where($where)->order('creat_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();

        $c = -1;
        $savec = "";
        foreach ($list as $nk => $nv) {
            $timestr = date("Y-m-d", $nv["creat_time"]);
            if ($timestr == $savec) {
                $list[$nk]["color"] = $c;
            } else {
                $savec = $timestr;
                $c = ($c + 1) % 7;
                $list[$nk]["color"] = $c;
            }
        }
        $this->assign('stat', $stat);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $this->display();
    }

    //推广手机页面渲染
    public function phoneset()
    {
        if($_REQUEST['stat'] == ''){exit('非法操作!');}
        switch ($_REQUEST['stat']){
            case 0:
                $this->VisitOperation('推广手机登记册');
                break;
            case 1:
                $this->VisitOperation('推广手机投放中');
                break;
            case 2:
                $this->VisitOperation('回收站-推广手机登记册');
                break;
            case 3:
                $this->VisitOperation('回收站-推广手机投放中');
                break;
            default:
                $this->VisitOperation('非法操作');
        }
        $where = array();
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
        $this->assign("fdata", $fdata);
        $start_time = strtotime($fdata["start_time"]);
        $end_time = strtotime($fdata["end_time"]);
        $xtime = array(array("gt", $start_time), array("lt", $end_time));
        $where["creat_time"] = $xtime;

        if (!empty($fdata["nbname"])) {
            if ($fdata["nbname"] != "") {
                $where["nbname"] = array("like", "%" . trim($fdata["nbname"]) . "%");
            }
        }
        if (!empty($fdata["nbphone"])) {
            if ($fdata["nbphone"] != "") {
                $where["nbphone"] = array("like", "%" . trim($fdata["nbphone"]) . "%");
            }
        }
        if (!empty($fdata["nbcode"])) {
            if ($fdata["nbcode"] != "") {
                $where["nbcode"] = array("like", "%" . trim($fdata["nbcode"]) . "%");
            }
        }
        if (!empty($fdata["nbmode"])) {
            if ($fdata["nbmode"] != "") {
                $where["nbmode"] = $fdata["nbmode"];
            }
        }
        $stat = $_REQUEST['stat'];
        switch ($stat) {
            case "0":
                $where['status'] = 1;
                $where["is_used"] = 0;
                break;
            case "1":
                $where['status'] = 1;
                $where["is_used"] = 1;
                break;
            case "2":
                $where['status'] = 0;
                $where["is_used"] = 0;
                $this->assign('recycle', 1);
                break;
            case "3":
                $where['status'] = 0;
                $where["is_used"] = 1;
                $this->assign('recycle', 1);
                break;
            default:
                break;
        }
        $DB = M('store_phone'); // 实例化User对象
        $count = $DB->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 50); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出.
        $list = $DB->where($where)->order('creat_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $c = -1;
        $savec = "";
        foreach ($list as $nk => $nv) {
            $timestr = date("Y-m-d", $nv["creat_time"]);
            if ($timestr == $savec) {
                $list[$nk]["color"] = $c;
            } else {
                $savec = $timestr;
                $c = ($c + 1) % 7;
                $list[$nk]["color"] = $c;
            }
        }
        $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(6));
        $this->assign('stat', $stat);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display();
    }

    //ajax 判断名称是否重复
    public function repetitionAjax()
    {
        $action = I("post.par");
        switch ($action) {
            case "phone":
                $chkname = I('post.nbname', '', 'trim');
                $map = array(
                    "nbname" => $chkname
                );
                $num = M("store_phone")->where($map)->count();
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

    //删除数据
    public function del_data(){
        $action = I("post.par");
        $where['id'] = $_REQUEST['id'];
        $data['status'] = $_REQUEST['status'];
        $data['del_time'] = time();
        $data['del_user'] = $this->cid;
        switch ($action) {
            case "phoneset":
                $res = M("store_phone")->where($where)->data($data)->save();
                break;
            case "bank":
                $res = M("store_card")->where($where)->data($data)->save();
                break;
            case "qqset":
                $res = M("store_qq")->where($where)->data($data)->save();
                break;
            case "teleset":
                $res = M("store_tele")->where($where)->data($data)->save();
                break;
            default:
                break;
        }
        if ($res) {
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',1);
            $json['res'] = 'suc';
        } else {
            $this->VisitOperation('删除['.$_REQUEST['id'].','.$_REQUEST['status'].']',2);
            $json['res'] = 'err';
        }
        $this->ajaxReturn($json);
    }
    public function show_changer()
    {
        if(empty($_REQUEST['parar'])){exit;}
        if(!empty($_REQUEST['parar'])){
            if($_REQUEST['parar'] == '1'){
                $data['going'] = $_REQUEST['show'];
                $this->VisitOperation('修改['.$_REQUEST['id'].',正在开发：'.$_REQUEST['show'].']',1);
            }elseif($_REQUEST['parar'] == '2'){
                $data['status'] = $_REQUEST['show'];
                $this->VisitOperation('修改['.$_REQUEST['id'].',状态：'.$_REQUEST['show'].']',1);
            }else{
                exit('非法操作！');
            }
        }
        $where['id'] = $_REQUEST['id'];
        $res = M("feedback")->where($where)->data($data)->save();
        if ($res) {
            $json['res'] = 'suc';
        } else {
            $json['res'] = 'err';
        }
        $this->ajaxReturn($json);
    }
    //是否投放使用
    public function show_change()
    {
        $where['id'] = $_REQUEST['id'];
        $data['is_used'] = $_REQUEST['is_used'];
        $res = M("store_phone")->where($where)->data($data)->save();
        if ($res) {
            $this->VisitOperation('是否投放使用	['.$_REQUEST['id'].','.$_REQUEST['is_used'].']',1);
            $json['res'] = 'suc';
        } else {
            $this->VisitOperation('是否投放使用	['.$_REQUEST['id'].','.$_REQUEST['is_used'].']',2);
            $json['res'] = 'err';
        }
        $this->ajaxReturn($json);
    }

    //form表单提交 add
    function phoneadd()
    {
        $status = I('get.status');
        if (empty($status)) {
            $this->assign("status", $status);
        }
        $back = cookie("back");
        if (empty($back)) {
            $back = U("phoneset");
        }
        if (IS_POST) {
            $data = readPost($_POST);
            $data["admin_id"] = $this->cid;
            $data["creat_time"] = strtotime($data["creat_time"]);
            $res = M("store_phone")->add($data);
            if ($res > 0) {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['nbname'].']',1);
                $this->resMsg(C("operation_succ_status"), '/phoneset?stat=0', 1);
                exit;
            } else {
                $this->VisitOperation('添加['.$res.','.$_REQUEST['nbname'].']',2);
                $this->resMsg(C('operation_error_status'), '/phoneset?stat=0', 1);
                exit;
            }
        }
        $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(6));
        $this->assign("back", $back);
        $this->display();
    }

    //编辑
    function phonedit()
    {
        $id = I("get.id");
        $status = I('get.status');
        if (!empty($status)) {
            $this->assign("status", $status);
        }
        if (IS_POST) {
            $data = readPost($_POST);
            $data["creat_time"] = strtotime($data["creat_time"]);
            $where = array(
                "id" => $id
            );
            $res = M("store_phone")->where($where)->save($data);
            if ($res) {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['nbname'].']',1);
                $this->resMsg(C("operation_succ_status"), '/phoneset?stat=0', 1);
                exit;
            } else {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['nbname'].']',2);
                $this->resMsg(C('operation_error_status'), '/phoneset?stat=0', 1);
                exit;
            }
        }

        $this->assign("back", $back);
        $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(6));
        $row = M("store_phone")->where(array("id" => $id))->find();
        $this->VisitOperation('查看['.$_REQUEST['id'].','.$row['nbname'].']',1);
        $this->assign("row", $row);
        $this->display('phoneadd');
    }

    function ajax()
    {
        set_time_limit(0);
        $action = I("post.dopost");
        switch ($action) {
            case "chong":
                $chkname = I("post.chkname");
                $map = array(
                    "nbname" => $chkname
                );
                $num = M("store_phone")->where($map)->count();
                if ($num > 0) {
                    $backdata = array(
                        "res" => 2
                    );
                } else {
                    $backdata = array(
                        "res" => 1
                    );
                }
                $this->ajaxReturn($backdata);
                break;
            case "qqchong":
                $qqcode = I("post.qqcode");
                $map = array(
                    "qqcode" => $qqcode
                );
                $num = M("store_qq")->where($map)->count();
                if ($num > 0) {
                    $backdata = array(
                        "res" => 2
                    );
                } else {
                    $backdata = array(
                        "res" => 1
                    );
                }
                $this->ajaxReturn($backdata);
                break;
            default:
                break;
        }
    }

}

?>
