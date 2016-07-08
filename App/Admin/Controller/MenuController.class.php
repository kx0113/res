<?php
namespace Admin\Controller;
use Think\Controller;
class MenuController extends BaseController {
    public function _initialize(){
        parent::_initialize();
        //模型初始化

    }
    //首页
        public function index(){
            if($this->account == 'admin'){
                $system_menu1 = M('menu_admin')->order("sort desc")->select();
            }else{
                $sel = M('admin')->where(array('id'=>$this->cid))->find();
                $nav = ($sel['nav']);
                $c = explode(',',$nav);
                $system_menu1 = M('menu_admin')->where(array('id'=>array('in',$c)))->order("sort asc")->select();
            }
          $this->assign("system_menu1",$system_menu1);
          $this->display();
        }
    //添加
        public function add(){
            if($_POST){
                $menu = M("menu_admin");
                $menu->create();
                $result = $menu->add();
                if($result){
                    $json['result'] = "success";
                }else{
                    $json['result'] = "error";
                }
                $this->ajaxReturn($json);
            }
            $this->display();
        }
        public function home_edit(){
        	$system_menu = M('menu_home')->where('status=1')->select();
        	$this->assign("system_menu",$system_menu);
        	$this->display();
        }
    //删除
        public function del(){
        	$menu = M("menu_admin");
        	$where['id'] = $_REQUEST['id'];
        	$result = $menu->where($where)->delete();
        	if($result){
        		$json['result'] = "success1";
        	}else{
        		$json['result'] = "error";
        	}
        	$this->ajaxReturn($json);
        }
    //更新
		public function updata(){
			$menu = M("menu_admin");
			$where['id'] = I('id');
			$data['name'] = $_REQUEST['name'];
			$data['path'] = $_REQUEST['path'];
			$data['icon'] = $_REQUEST['icon'];
			$data['sort'] = $_REQUEST['sort'];
			$data['status'] = $_REQUEST['status'];
		    $result = $menu->where($where)->save($data);
            if($result){
                $json['result'] = "success";
            }else{
                $json['result'] = "error";
            }
            $this->ajaxReturn($json);
        }
}