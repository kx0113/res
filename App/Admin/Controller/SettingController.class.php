<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
use Think\Model;

class SettingController extends BaseController
{
    public function _initialize()
    {
        parent::_initialize();
        $this->uid = session('sid');
        //$this->menu = M('menu_admin')
        $this->admin = M('admin');
    }
    public function index()
    {
        $par = $_REQUEST['par'];
        if(!empty($par)){
            $where['status'] = '4';
            $this->assign("par", 1);
            $this->VisitOperation('待审批用户');
        }else{
            $where = '';
            $this->VisitOperation('用户列表');
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

    public function operateParams()
    {
        //$setting = M("setting")->order("'group' asc ")->select();
        //$this->assign("params", $setting);

        $this->display();
    }

    // 保存参数
    public function saveParams()
    {
        if ($_REQUEST['name'] != '') {

            $setting = M("setting");

            $data['key'] = $_REQUEST['name'];
            $data['value'] = $_REQUEST['value'];
            $data['group'] = $_REQUEST['group'];
            $data['sort'] = $_REQUEST['sort'];
            $data['status'] = $_REQUEST['status'];

            if ($_REQUEST['id'] != '') {
                $where['id'] = $_REQUEST['id'];
                $result = $setting->where($where)
                    ->data($data)
                    ->save();
            } else {
                $result = $setting->data($data)->add();
            }

            if ($result) {
                $json['result'] = "success";
            } else {
                $json['result'] = "error";
            }

            $this->ajaxReturn($json);
        }
    }

    // 删除参数
    public function deleteParams()
    {
        if ($_REQUEST['id'] != '') {

            $setting = M("setting");

            $where['id'] = $_REQUEST['id'];

            $result = $setting->where($where)->delete();
        }
        if ($result) {
            $json['result'] = "success";
        } else {
            $json['result'] = "error";
        }

        $this->ajaxReturn($json);
    }

    public function area()
    {
        $area = M("city_area")->where("status=1")->select();

        $this->assign("areas", $area);
        $this->display();
    }


    public function power()
    {
        $system_module = M('system_menu')->select();

        $this->assign("system_module", $system_module);
        $this->display();
    }

    public function addPower()
    {
        if ($_REQUEST['name'] != '') {
            $system_menu = M("system_menu");
            $system_menu->create();
            $result = $system_menu->add();
            if ($result) {
                $json['result'] = "success";
            } else {
                $json['result'] = "error";
            }
            $this->ajaxReturn($json);
        }

        $this->display();
    }

    public function editpower()
    {
        if ($_REQUEST['action'] == 'update') {
            $where['id'] = $_REQUEST['id'];
            $system_menu = M('system_menu');
            $data['name'] = $_REQUEST['name'];
            $data['path'] = $_REQUEST['path'];
            $data['parent'] = $_REQUEST['parent'];
            $data['sort'] = $_REQUEST['sort'];
            $data['status'] = $_REQUEST['status'];
            $data['is_menu'] = $_REQUEST['is_menu'];
            $result = $system_menu->where($where)->data($data)->save();
            if ($result) {
                $json['result'] = "success";
            } else {
                $json['result'] = "error";
            }

            $this->ajaxReturn($json);
        }
        $where['id'] = $_REQUEST['id'];

        $system_menu = M('system_menu')->where($where)->find();
        $this->assign("power", $system_menu);

        $system_module = M('system_menu')->select();
        $this->assign("system_module", $system_module);

        $this->display();
    }

    public function delPower()
    {
        if ($_REQUEST['id'] != '') {
            $system_menu = M("system_menu");

            $where['parent'] = $_REQUEST['id'];

            $result = $system_menu->where($where)->find();

            if ($result) {
                $json['result'] = "error";
                $json['msg'] = '有下级模块，不能删除';

                $this->ajaxReturn($json);
            } else {

                $whereDel["id"] = $_REQUEST['id'];
                $resultDel = $system_menu->where($whereDel)->delete();

                if ($resultDel) {
                    $json['result'] = "success";
                } else {
                    $json['result'] = "error";
                }

                $this->ajaxReturn($json);
            }
        }
    }

    public function role()
    {
        $roles = M("admin_role")->select();
        $this->assign("roles", $roles);
        $this->display();
    }

    public function addRole()
    {
        if ($_REQUEST['name'] != '') {

            $role = M("admin_role");
            $role->create();

            $result = $role->add();
            if ($result) {
                $json['result'] = "success";
            } else {
                $json['result'] = "error";
            }

            $this->ajaxReturn($json);
        }

        $this->display();
    }

    public function editRole()
    {
        if ($_REQUEST['action'] == 'update') {
            $where['id'] = $_REQUEST['id'];
            $role = M('admin_role');

            $data['name'] = $_REQUEST['name'];

            $data['status'] = $_REQUEST['status'];

            $result = $role->where($where)
                ->data($data)
                ->save();

            if ($result) {
                $json['result'] = "success";
            } else {
                $json['result'] = "error";
            }

            $this->ajaxReturn($json);
        } else
            if ($_REQUEST['id'] != '') {

                $role = M('admin_role')->where("id=" . $_REQUEST['id'])->find();
                $this->assign("role", $role);
            }

        $this->display();
    }

    public function setpower()
    {
        $this->checkVisit($this->actionUrl);
        if(empty($_REQUEST['id'])) exit;
        $id = I('request.id');
        $res = M('neiqin_group')->where(array('id' => $id))->find();
        $nav = unserialize($res['grouppower']);
        $this->assign("groupid", $id);
        $this->assign("power", $nav);
        $set_power = M('package')->order("weight DESC")->where(array('status' => 1))->select();
        $this->assign("system_module1", $set_power);
        $this->assign("findng", $res);
        $this->VisitOperation('查看设置权限['.$_REQUEST['id'].','.$res['groupname'].']',1);
        $this->display();
    }
    public function jurisdiction()
    {
        $id = $_REQUEST['groupid'];
        if(empty($id)) exit;
       // dump($_POST['checkbox']);exit;
        $data['grouppower'] = serialize($_POST['checkbox']);
       // $data['menu_url'] = serialize($_POST['checkbox_path']);
        $data['updata_time'] = time();
        $res = M('neiqin_group')->where(array('id' => $id))->data($data)->save();
        if ($res) {
            $this->VisitOperation('修改['.$_REQUEST['groupid'].']',1);
            $this->resMsg(C("operation_succ_status"), 'Group/index', 4);
            exit;
        } else {
            $this->VisitOperation('修改['.$_REQUEST['groupid'].']',2);
            $this->resMsg(C('operation_error_status'), 'Group/index', 4);
            exit;
        }
    }
    //创建新用户
    public function add(){
        if ($_POST) {
            $admin = M("admin");
            $account = I('post.account','','trim');
            $password = I('post.password','','trim');
            $res = $admin->where(array('account' => $account))->select();
            if ($res) {
                $json['resultr'] = "error1";
            } else {
                $data['account'] = $account;
                $data['nickname'] = I('post.nickname','','trim');
                $data['status'] = I('post.status','','intval');
                $data['password'] = substr(md5($password), 5, 20);
                $data['group_id'] = I('post.group_id','','intval');
                $data['create_time'] = time();
                $data['add_user'] = $this->cid;
                $result = $admin->data($data)->add();
                if ($result) {
                    $json['result'] = "success2";
                } else {
                    $json['result'] = "error";
                }
            }
            $this->ajaxReturn($json);
        } else {
            $this->assign("grouplist", $this->getGroupList());
            $this->display();
        }
    }
    //ajax编辑用户信息
    public function editAdd(){

    }
    //编辑用户信息
    public function edit(){
        if(empty($_REQUEST['id'])) {exit;}
        if(IS_POST) {
            $par = I('post.par','','intval');
            $where['id'] = I('post.id','','intval');
            //如果$par为0，说明account值未修改
            if($par == '0'){
                $data['account'] = I('post.account','','trim');
            }elseif($par == '1'){
                //如果$par值为1，说明已修改account值,查询名称是否重复
                $account = I('post.account','','trim');
                $res = M('admin')->where(array('account' => $account))->select();
                if($res){
                    $json['result1'] = "selisarr";
                }else{
                    $data['account'] = $account;
                }
            }else{
                $json['result'] = "err";
            }
            $data['nickname'] = I('post.nickname','','trim');
            $data['status'] = I('post.status','','intval');
            $data['updat_time'] = time();
            $data['group_id'] = I('post.group_id','','intval');
            $result = M('admin')->where($where)->data($data)->save();
            if ($result !== 0) {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['account'].']',1);
                $json['result'] = "suc";
            } else {
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['account'].']',2);
                $json['result'] = "err";
            }
            $this->ajaxReturn($json);
        }
        $par = $_REQUEST['par'];
        $where['id'] = I('id');
        $admin = M('admin')->where($where)->find();
        $this->assign("admin1", $admin);
        $this->assign("par", $par);
        $this->VisitOperation('查看['.$admin['id'].','.$admin['account'].']',1);
        $this->assign("grouplist", $this->getGroupList());
        $this->display('add');
    }
    public function getGroupList(){
        $grouplist = M('neiqin_group')->where(array('is_use'=>1))->select();
        return $grouplist;
    }
    //设置密码
    public function setAdminPassword(){
        if(empty($_REQUEST['id'])) exit;
        $sp = $_REQUEST['sp'];
        $this->assign("sp", $sp);
        $where['id'] = $_REQUEST['id'];
        if ($_REQUEST['action'] == 'password') {
            $admin = M('admin');
            $password = I('request.password');
            $data['password'] = substr(md5($password), 5, 20);
            $result = $admin->where($where)->data($data)->save();
            if ($result) {
                $this->VisitOperation('设置密码['.$_REQUEST['id'].','.md5($_REQUEST['password']).']',1);
                $json['result'] = "success";
            } else {
                $this->VisitOperation('设置密码['.$_REQUEST['id'].','.md5($_REQUEST['password']).']',2);
                $json['result'] = "error";
            }
            $this->ajaxReturn($json);
        }
        $admin = M('admin')->where($where)->find();
        $this->assign("grouplist", $this->getGroupList());
        $this->assign("admin1", $admin);
        $this->display('add');
    }

    public function getArea()
    {
        $where['status'] = 1;

        if ($_REQUEST['parent_code'] != '') {
            $where['parent_code'] = $_REQUEST['parent_code'];
        } else {
            $where['parent_code'] = array(
                "exp",
                "is null"
            );
        }

        $area = M("city_area")->where($where)->select();

        $json["result"] = $area;

        $this->ajaxReturn($json);
    }

//-----------------------------板块------------------------------
    public function Plate()
    {
        $this->display('setting/plate/plate');
    }

    // 添加板块
    public function addPlate()
    {
        $regional_plate = $_POST['regional_plate'];
        if ($_POST['regional_plate'] != '') {
            $plate = M("plate");
            $count = $plate->where('regional_plate=' . "'" . $regional_plate . "'")->count();
            if ($count == 0) {
                $plate->create();
                $result = $plate->add();
                if ($result) {
                    $json['result'] = "success";
                } else {
                    $json['result'] = "error";
                }
                $this->ajaxReturn($json);
            } else
                if ($count != 0) {
                    $json['result'] = "error";
                    $this->ajaxReturn($json);
                }
        } else {
            $this->display("/setting/plate/addPlate");
        }
    }

    // 查询需要编辑的板块在前端显示
    public function editPlate()
    {
        $plateID = $_GET['id'];
        $plate = M('plate');
        $data = $plate->where('id=' . $plateID)->find();
        $this->assign('result', $data);
        $this->assign('id', $plateID);
        $this->display('setting/plate/editPlate');
    }

    public function ajaxPlate()
    {
        $plateID = $_POST['plateId'];
        $cz = $_POST['cz'];

        if ($cz == '') {
            $plate = M('plate')->select();
            $result['result'] = json_encode($plate);
            $this->ajaxReturn($result);
        } else
            if ($cz == 'edit') { // 编辑板块
                $data = $_POST['data'];
                $id = $_POST['id'];
                $plate = M('plate');
                $res = $plate->execute("update plate set regional_plate='" . $data . "' where id=" . $id);
//                 $res=$plate->where('id='.$id)->save($data);
                if ($res) {
                    $result['result'] = 'success';
                } else {
                    $result['result'] = 'error';
                }
                $this->ajaxReturn($result);
            } else
                if ($cz == 'del') { // 删除板块
                    $proExit = M('project');
                    $proExit->execute("update project set plateId=null where plateId=" . $plateID);
                    $plate = M('plate');
                    $plate->where('id=' . $plateID)->delete();
                    if ($plate) {
                        $result['result'] = 'success';
                    } else {
                        $result['result'] = 'error';
                    }
                    $this->ajaxReturn($result);
                }
    }

//-----------------------自定义板块-----------------------------
    public function userPlate()
    {
        $this->display('setting/plate/userPlate');
    }

    // 添加板块
    public function addUserPlate()
    {
        $regional_plate = $_POST['regional_plate'];
        if ($_POST['regional_plate'] != '') {
            $plate = M("plate_user");
            $count = $plate->where('regional_plate=' . "'" . $regional_plate . "'")->count();
            if ($count == 0) {
                $plate->create();
                $result = $plate->add();
                if ($result) {
                    $json['result'] = "success";
                } else {
                    $json['result'] = "error";
                }
                $this->ajaxReturn($json);
            } else
                if ($count != 0) {
                    $json['result'] = "error";
                    $this->ajaxReturn($json);
                }
        } else {
            $this->display("/setting/plate/addUserPlate");
        }
    }

    // 查询需要编辑的板块在前端显示
    public function editUserPlate()
    {
        $plateID = $_GET['id'];
        $plate = M('plate_user');
        $data = $plate->where('id=' . $plateID)->find();
        $this->assign('result', $data);
        $this->assign('id', $plateID);
        $this->display('setting/plate/editUserPlate');
    }

    public function ajaxUserPlate()
    {
        $plateID = $_POST['plateId'];
        $cz = $_POST['cz'];

        if ($cz == '') {
            $plate = M('plate_user')->select();
            $result['result'] = json_encode($plate);
            $this->ajaxReturn($result);
        } else
            if ($cz == 'edit') { // 编辑板块
                $data = $_POST['data'];
                $id = $_POST['id'];
                $plate = M('plate_user');
                $res = $plate->execute("update plate_user set regional_plate='" . $data . "' where id=" . $id);
                //                 $res=$plate->where('id='.$id)->save($data);
                if ($res) {
                    $result['result'] = 'success';
                } else {
                    $result['result'] = 'error';
                }
                $this->ajaxReturn($result);
            } else
                if ($cz == 'del') { // 删除板块
//             $proExit = M('project');
//             $proExit->execute("update project set plateId=null where plateId=" . $plateID);
                    $plate = M('plate_user');
                    $plate->where('id=' . $plateID)->delete();
                    if ($plate) {
                        $result['result'] = 'success';
                    } else {
                        $result['result'] = 'error';
                    }
                    $this->ajaxReturn($result);
                }
    }
}