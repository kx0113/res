<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class EvalController extends BaseController {
    public function _initialize(){
        parent::_initialize();
    }
       //登录日志
   public function log(){
       $this->VisitOperation('登陆日志');
       $fdata = readPost($_REQUEST);
       $now_Y = date("Y", time());
       $now_m = date("m", time());
       $now_d = date("d", time());
       $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
       $now_low = strtotime($now_time);
       $time1 = $now_low - 86400 * 15;
       $time2 = $now_low + 86400 * 2;
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

       $where['status'] = 1;
       $where["time"] = $xtime;
       if (!empty($fdata["uname"])) {
           if ($fdata["uname"] != "") {
               $where["name"] = array("like", "%" . trim($fdata["uname"]) . "%");
           }
       }
       if (!empty($fdata["gc_namer"])) {
           if ($fdata["gc_namer"] != "0") {
               $where["gc_id"] = array("like", "%" . trim($fdata["gc_namer"]) . "%");
           }
       }
       $count = M('log')->where($where)->count();
       $page = new Page($count,30);
       $log = M('log')->where($where)->order('time DESC')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('page', $page->show());
        $this->assign('list',$log);
        $this->display();
   }
    //异常登录login_err
    public function loginerr(){
        $this->VisitOperation('异常登录');
        $fdata = readPost($_REQUEST);
        $now_Y = date("Y", time());
        $now_m = date("m", time());
        $now_d = date("d", time());
        $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
        $now_low = strtotime($now_time);
        $time1 = $now_low - 86400 * 15;
        $time2 = $now_low + 86400 * 2;
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
        $where["time"] = $xtime;
        if (!empty($fdata["uname"])) {
            if ($fdata["uname"] != "") {
                $where["name"] = array("like", "%" . trim($fdata["uname"]) . "%");
            }
        }
        if (!empty($fdata["gc_namer"])) {
            if ($fdata["gc_namer"] != "0") {
                $where["gc_id"] = array("like", "%" . trim($fdata["gc_namer"]) . "%");
            }
        }
        $count = M('login_err')->where($where)->count();
        $page = new Page($count,30);
        $res = M('login_err')->where($where)->order('time DESC')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$res);
        $this->assign('page', $page->show());
        $this->display();
    }
    public function userblist(){
        $par = $_REQUEST['par'];
        if(!empty($par)){
            $where['status'] = '4';
            $this->assign("par", 1);
            $this->VisitOperation('待审批用户');
        }else{
            $where = '';
            $this->VisitOperation('行为,用户列表');
        }
        $count = M('admin')->where($where)->count();
        $page = new Page($count,20);
        $res = M('admin')->where($where)
            ->order('id ASC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        if($count == 0){
            $this->assign("not_count", 1);
        }
        $this->assign("admins", $res);
        $this->assign('page', $page->show());
        $this->display();
    }
    //用户行为
    public function userbehavior(){
        $this->VisitOperation('UserBehavior');
        $where = array();
        if($_REQUEST['par'] == '1'){
            $where['exclude'] = '2';
            $this->assign('exclude',$_REQUEST['par']);
        }else{
            $id = $_GET['id'];
            if(empty($id)){exit;}
            $where['user_id'] = $id;
            $this->assign('uid',$id);
        }
            $fdata = readPost($_REQUEST);
            $now_Y = date("Y", time());
            $now_m = date("m", time());
            $now_d = date("d", time());
            $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
            $now_low = strtotime($now_time);
            $time1 = $now_low - 86400 * 12;
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
            $where["time"] = $xtime;
            if (!empty($fdata["behavior"])) {
                if ($fdata["behavior"] != "") {
                    $where["behavior"] = array("like", "%" . trim($fdata["behavior"]) . "%");
                }
            }
            if (!empty($fdata["url"])) {
                if ($fdata["url"] != "") {
                    $where["url"] = array("like", "%" . trim($fdata["url"]) . "%");
                }
            }
            if (!empty($fdata["ip"])) {
                if ($fdata["ip"] != "") {
                    $where["ip"] = array("like", "%" . trim($fdata["ip"]) . "%");
                }
            }
        $count = M('behavior')->where($where)->count();
        if($count == 0){
            $this->assign('not_find',1);
        }
        $page = new Page($count,20);
        $res = M('behavior')->where($where)->order('time DESC')->limit($page->firstRow.','.$page->listRows)->select();
        $this->assign('list',$res);
        $this->assign('page', $page->show());
        $this->display();
    }

    
}