<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2016/1/25
 * Time: 9:53
 */

namespace Admin\Controller;
use Common\ORG\Util\Page;
use Think\Model;

class ProductController extends BaseController
{
    public $db,$gc;
    const purpose = 6; //消费用途
    const persion = 3; //当前人员
    //控制器初始化
    function _initialize(){
        parent::_initialize();
        $this->db = M('res');
        $this->gc = M('goods_class');
    }
    //首页
    public function index(){
        $gc = $this->gc->field('id,name')->where(array('status'=>1,'class'=>self::purpose))->select();
        $per = $this->gc->field('id,name')->where(array('status'=>1,'class'=>self::persion))->select();
        foreach($gc as $k=>$v){
            $new_gck[$k]=$v['id'];
        }
        foreach($per as $k1=>$v1){
            $new_gck1[$k1]=$v1['id'];
        }
        $q = implode(',',$new_gck);
        $q1 = implode(',',$new_gck1);
        $sql="SELECT SUM(num) AS num,a.gc_id6 AS gc,b.name  FROM tp_res a JOIN tp_goods_class b ON a.gc_id6=b.id WHERE a.par=1 AND a.gc_id6 IN($q) GROUP BY a.gc_id6";
        $sql1="SELECT SUM(num) AS num,a.gc_id3 AS gc,b.name  FROM tp_res a JOIN tp_goods_class b ON a.gc_id3=b.id WHERE a.par=2 AND  a.gc_id3 IN($q1) GROUP BY a.gc_id3";
        $res = M()->query($sql);
        $res1 = M()->query($sql1);
        $this->assign('list',$res);
        $this->assign('list1',$res1);
     //   dump($res1);
        $this->display();
    }
    public function menuadd(){
        if(IS_POST){
            $data = readPost($_REQUEST);
            $data['add_time']=time();
            $data['add_user']=$this->cid;
            $db=M('menu');
            $res = $db->add($data);
            if($res){
                $this->resMsg(C("operation_succ_status"), '/menulist', 1);
            }else{
                $this->resMsg(C('operation_error_status'), '/menuadd', 1);
            }
        }
        $this->assign('gc_id',D('GoodsClass')->GetGoodsClass(7));
        $this->display();
    }
    public function menuedit(){
        $where['id'] = $_REQUEST['id'];
        $db=M('menu');
        if(IS_POST){
            $data = readPost($_POST);
            $res = $db->where($where)->data($data)->save();
            if($res){
                $this->resMsg(C("operation_succ_status"), '/menulist', 1);
            }else{
                $this->resMsg(C('operation_error_status'), '/menuadd', 1);
            }
        }
        $res = $db->where($where)->find();
        $this->assign('list',$res);
        $this->assign('gc_id',D('GoodsClass')->GetGoodsClass(7));
        $this->display('menuadd');
    }
    public function menulist(){
        $where['status'] = 1;
        $count = M('menu')->where($where)->count();
        $Page = new Page($count, 30);
        $show = $Page->show();
        $list = M('menu')->where($where)->order('add_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }
    /**
     * 删除
     */
    public function delete_data()
    {
        $where['id'] = $_REQUEST['id'];
        $data['status'] = $_REQUEST['status'];
        $del = $_REQUEST['par'];
        switch ($del) {
            case 'index':
                $res = M('menu')->where($where)->data($data)->save();
                break;
            default:
                exit;
        }
        if ($res !== false) {
            $json['result'] = 'success';
        } else {
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
    }

}