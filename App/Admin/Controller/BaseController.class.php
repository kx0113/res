<?php
namespace Admin\Controller;

use Think\Controller;

//use Common\ORG\Util\Ip;
class BaseController extends Controller
{
    public $system_menu;
    public $system_menu_sub_path;
    public $currentMenu = "";
    public $currentTop;
    public $leftMenu;
    public $totalMenu;
    public $currentPower;
    public $cid;
    public $account;
    public $visit;
    public $actionUrl;
    public $isSenior;
    public $svis, $lock;

    function _initialize()
    {
        $this->cid = session("cid");
        $this->account = session("account");
        $this->lock = session("lock");
        if (!isset($this->account) || !($this->account) || !($this->cid) || !isset($this->cid)) {
            $this->redirect("Admin/Login/login");
            exit;
        }
        if ($this->lock == 'locked') {
            $this->_logout();
            $this->error('屏幕锁定，请重新登陆！',U('Admin/Login/login'));
            exit;
        }
        $this->svis = session("svis");
        $this->actionUrl = CONTROLLER_NAME . '/' . ACTION_NAME;
        $title = array('title' => C('index_title'));
        $this->assign("index", $title);
        $this->getleft();
        $this->gethead();
        $this->checkAdminSession();
    }

    /**
     * 取得客户端ip
     * return string
     * */
    public function get_client_ip()
    {
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $cip = getenv("HTTP_CLIENT_IP");
        } else {
            $cip = "unknown";
        }
        return $cip;
    }

    /**
     *文件下载
     */
    public function DownloadFile($file_name = '')
    {
        //  $file_name = "GoogleChrome.zip";     //下载文件名
        $file_dir = "./Public/";        //下载文件存放目录
        //检查文件是否存在
        if (!file_exists($file_dir . $file_name)) {
            echo "文件找不到";
            exit ();
        } else {
            //打开文件
            $file = fopen($file_dir . $file_name, "r");
            //输入文件标签
            header('Content-Type: application/msexcel');
//            Header("Content-type: application/x-xls;");
            header('Content-Disposition: attachment; filename="' . $file_name . '"');
            header('Content-Transfer-Encoding: binary');
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($file_dir . $file_name));

//            //输出文件内容
            //读取文件内容并直接输出到浏览器
//            echo iconv('UTF-8', 'GBK', fread ( $file, filesize ( $file_dir . $file_name ) ));
            echo icon('UTF-8', 'GBK', fread($file, filesize($file_dir . $file_name)));
            fclose($file);
            exit ();
        }
    }

    /*
	* 注销登陆
	*/
    public function _logout()
    {
        session(NULL);
        session_unset();
        session_destroy();
    }

    /**
     * 设置登陆超时
     */
    public function checkAdminSession()
    {
        foreach (C('admin_out_array') as $k => $v) {
            if ($this->cid == $v) {
                $admin_out_time = C('admin_out_time_t');
                break;
            } else {
                $admin_out_time = C('admin_out_time_lock');
            }
        }
        $nowtime = time();
        $login_time = session('login_time');//(60 * $admin_out_time)
        if (($nowtime - $login_time) > (60 * $admin_out_time)) {
            session("lock", 'locked');
            logAdd('超时屏幕锁定',$this->cid);
            $this->redirect("LockScreen/index");
        } else {
            session('login_time', time());
        }
    }

    public function getleft()
    {
        if ($this->cid == "1" || $this->cid == "2") {
            $menupackage = M('Package')->where(array('status' => 1))->order("weight desc")->select();
        } else {
            $sel = M('admin')->where(array('id' => $this->cid))->find();
            $neiqin_group = M("neiqin_group")->where(array('id' => $sel['group_id']))->find();
            if (!empty($users['package'])) {
                $userPackage = $sel['package'];
            } else {
                $userPackage = $neiqin_group['grouppower'];
            }
            $nav = unserialize($userPackage);
            foreach ($nav as $k => $v) {
                $menu_id[] = $v;
            }
            $ids = implode(',', $menu_id);
            $menupackage = M('package')->where(array('id' => array('in', $ids), 'status' => 1))->order("weight DESC")->select();
            // $url = $_SERVER['PATH_INFO'];
            $url = MODULE_NAME . '/' . CONTROLLER_NAME;
            //
            foreach ($menupackage as $k12 => $v12) {
                $funcname[] = $v12['funcname'];

            }
            foreach ($funcname as $v_r) {
                if (!empty($v_r)) {
                    $v_r1 = explode('/', $v_r);
                    $v_r2[] = 'Admin/' . $v_r1[0];
                }
            }
            if (in_array($url, array_unique($v_r2))) {
                // echo 'A';
            } else {
                //  echo 'B';
                $this->error('您没有权限访问！');
            }
        }
        $this->assign('system_menu', $menupackage);
    }

    /**
     * 用户权限检测
     */
    public function userPackageCheck($userPackage, $menupackage)
    {
        $userPackageArr = unserialize($userPackage);
        foreach ($userPackageArr as $userPackageArr_k => $userPackageArr_v) {
            $userPackageList[] = $userPackageArr_v;
        }
        $funcname = CONTROLLER_NAME . '/' . ACTION_NAME;
        foreach ($menupackage as $AllPackage_v) {
            if ($AllPackage_v['funcname'] == $funcname) {
                if (in_array($AllPackage_v['id'], $userPackageList)) {
                    return true;
                } else {
                    return false;
                }
            }
        }
        return true;
    }

    public function getleft23()
    {
        //左侧导航列表
        //dump(cookie("account"));
        if ($this->cid == "1") {
            $this->system_menu = M('menu_admin')->where(array('status' => 1))->order("sort desc")->select();
        } else {
            $sel = M('admin')->where(array('id' => $this->cid))->find();
            $neiqin_group = M("neiqin_group")->where(array('id' => $sel['group_id']))->find();
            $nav = unserialize($neiqin_group['grouppower']);
            $this->visit = unserialize($neiqin_group['menu_url']);
            session('svis', $this->visit);
            if (is_array($nav)) {
                foreach ($nav as $k => $v) {
                    $menu_id[] = $v;
                }
                $this->system_menu = M('menu_admin')->where(array('id' => array('in', $menu_id), 'status' => 1))->order("sort DESC")->select();
                $count = count($this->system_menu);
            } else {
                $this->assign("not_menu", 1);
            }
        }
        $this->assign("system_menu", $this->system_menu);
        // $topMenu = M('menu_admin')->where('parent=0 and status=1')->order("sort asc")->select();
        // $this->assign("topMenu",$topMenu);
        //导航列表end
    }

    public function checkVisit($url = '')
    {
        if ($this->cid == 1) {

        } else {

        }
    }

    /**
     * 记录系统日志
     *
     * @param $lang 日志说明
     * @param $state 1成功0失败null不出现成功失败提示
     * @param $admin_name
     * @param $admin_id
     */

    public function VisitOperation($lang = '', $state = '1')
    {
        $log = M('behavior');
        $data1['user_id'] = isset($this->cid) ? $this->cid : 0;
        $data1['behavior'] = $lang;
        $data1['time'] = time();
        $data1['url'] = $_SERVER['PATH_INFO'];
        $data1['status'] = $state;
        if ($this->cid == '1') {
            $data1['exclude'] = 1;
        } else {
            $data1['exclude'] = 2;
        }
        $data1['ip'] = $this->get_client_ip();
        $add_log = $log->data($data1)->add();
    }

    /**
     * JsAlert返回信息
     * @param string $message
     * @param string $url
     * @param int $status 是否当前控制器 1为是
     */
    public function resMsg($message, $url, $status = 1)
    {
        echo '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />';
        if (intval($status) == 1) {
            echo "<script>alert('$message');window.location.href=\"" . U(CONTROLLER_NAME . $url) . "\";</script>";
        } elseif ($status == 2) {
            echo "<script>alert('$message');</script>";
        } else {
            echo "<script>alert('$message');window.location.href=\"" . U($url) . "\";</script>";
        }
        // exit;
    }

    //首次加载获取信息
    public function gethead()
    {
        $this->assign("account", $this->account);
        $this->assign("userheadpath", C('userheadpath'));
        $this->assign("adminheadpath", C('adminheadpath'));
        $this->assign("interviewpath", C('interviewpath'));
        $this->assign("index_title", C('index_title'));
        $this->assign("admin_name_dsda", C('admin_name_dsda'));
        $adminindexshow = M('admin')->where(array('id' => $this->cid))->find();
        $this->assign("adminindexshow", $adminindexshow);
    }

}