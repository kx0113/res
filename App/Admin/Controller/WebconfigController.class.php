<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class WebconfigController extends BaseController {
    public $model;
    public function _initialize(){
        parent::_initialize();
        $this->model = M('config');
    }
    //首页
    public function index(){
        $where['par'] = 1;
        $res = $this->model->where($where)->select();
       // dump($res);
        $this->assign('list',$res);
        $this->display();
    }
    public function edit(){
        $where['id'] = $_REQUEST[''];
    }
}