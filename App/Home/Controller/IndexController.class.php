<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        
      $this->display();
    }
    public function sel($where=array()){
        $m = M('order');
        $t = $m->where($where)->select();
        $r = json_encode($t);
        return $r;
    }
}