<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class GoodsOutController extends BaseController {
    public $goodsout;
    public function _initialize(){
        parent::_initialize();
        //模型初始化
        $this->goodsout = D('GoodsOut');
    }
    //首页
    //status = 1 状态正常
    //status = 5 已经归还
    //status = 0 已删除
    //status = 7 换新
    public function index(){
        $par = $_REQUEST['par'];
        if(!empty($par)){
            $where['status'] = $par ;
            if($_GET['par'] == 7){
               // $where['is_return'] = '';
            }else{
                $where['is_return'] = $par;
            }
            $this->VisitOperation('已归还物品');
            $this->assign('parad',$par);
        }else{
            $where['status'] = 1;
            $this->VisitOperation('物品出库');
        }
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
        $where["add_time"] = $xtime;
        if (!empty($fdata["get_person"])) {
            if ($fdata["get_person"] != "") {
                $where["get_person"] = array("like", "%" . trim($fdata["get_person"]) . "%");
            }
        }
        if (!empty($fdata["ge_namer"])) {
            if ($fdata["ge_namer"] != "0") {
                $where["ge_id"] = array("like", "%" . trim($fdata["ge_namer"]) . "%");
            }
        }
        if (!empty($fdata["gc_namer"])) {
            if ($fdata["gc_namer"] != "0") {
                $where["gc_id"] = array("like", "%" . trim($fdata["gc_namer"]) . "%");
            }
        }
        $count = $this->goodsout->where($where)->count();
        $page = new Page($count,20);
        $res = $this->goodsout->where($where)
            ->order('id DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        if($count > 0 ){
            $get_num = $this->goodsout->where($where)->select();
            foreach($get_num as $get_num_v){
                $getnum_r += $get_num_v['get_num'];
            }
        }else{
            $getnum_r = 0;
            $this->assign('count_not',1);
        }
        $this->assign('page', $page->show());
        $this->assign('allgetnumm',$getnum_r);
        $this->assign('allcount',$count);
        $this->assign('list',$res);
        $this->assign('getgenter',$this->getGeGcList());
       // dump($where);
        $this->display();
    }
    private function getGeGcList(){
        //查询goods_out表中gc_id ge_id
        $where['status'] = 1;
        $glist = M('goods_out')->field('id,gc_id,ge_id,status')->where($where)->order('id DESC')->select();
        if($glist){
            foreach($glist as $glek=>$glev){
                $getgoodsenterlist[] = $glev['ge_id'];
                $getgoodsclasslist[] = $glev['gc_id'];
            }
        }
        //去除数组重复数据
        $geid = array_unique($getgoodsenterlist);
        $gcid = array_unique($getgoodsclasslist);
        //通过id查询名称
        $goodsenter = M('goods_enter')->field('id,name,status')
            ->where(array('status'=>1,'id'=>array('in',$geid)))
            ->select();
        $goodsclass = M('goods_class')->field('id,name,status')
            ->where(array('status'=>1,'id'=>array('in',$gcid)))
            ->select();
        //赋值新数组
        foreach($goodsenter as $kt1=>$kv1){
            $new_arr['geinfo'][$kv1['id']] =$kv1['name'];
        }
        foreach($goodsclass as $kt11=>$kv11){
            $new_arr['gcinfo'][$kv11['id']] =$kv11['name'];
        }
      return $new_arr;
    }

    //删除
    public function delete_data(){
        $list = $_REQUEST['list'];
        if($list){
            $var=explode(",",$list);
            foreach ($var as $k=>$v)
            {
                $del_id[] = $v;
            }
            $where['id'] = array('in',$del_id);
        }else{
            $where['id'] = $_REQUEST['id'];
        }
        $data['status'] = $_REQUEST['status'];
        $res = $this->goodsout->SaveData($where,$data);
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
    //修改
    public function EditData() {
        if(empty($_REQUEST['id'])){ exit; }
        $this->assign('gc_list',$this->GetGoodsClass());
        $this->assign('ge_list',$this->GetGoodsEdit());
        $where['id'] = $_REQUEST['id'];
        $res = $this->goodsout->where($where)->find();
        $this->assign('list',$res);
        $ge_content = D('GoodsEnter')->where(array('id'=>$res['ge_id']))->find();
        $this->assign('ge_content',$ge_content);
        $this->VisitOperation('查看['.$res['id'].','.$res['get_person'].']',1);
        $this->display('add');
    }
    //修改状态
    public function show_change(){  
         $res = $this->goodsout
         ->where(array('id' => $_REQUEST['id']))
         ->save(array('status'=>$_REQUEST['status']));
    }
    //插入出库数据
    public function addData(){
        if($_POST !== false){
            if($_REQUEST['ge_id']){
                $where['id'] = I('request.ge_id');
                $where['name'] = I('request.select_name');
                //按照物品id跟名称查询
                $googsenter_find = D('GoodsEnter')->where($where)->find();
                if($googsenter_find){
                    //计算剩余量
                    $num = $googsenter_find['quantity'] - $_REQUEST['get_num'];
                    if($num < $googsenter_find['quantity']){
                        $data1['quantity'] = $num; //修改物品剩余量
                        $data['get_surplus'] = $num; //记录领取后的剩余量
                        $data['gc_id'] = $googsenter_find['gc_id']; //记录物品分类
                        $savegoogds = D('GoodsEnter')->where($where)->data($data1)->save();
                        if($savegoogds){
                            $data['ge_id'] = $googsenter_find['id'];  //记录领取产品id
                        }
                    }else{
                        $json['result'] = 'quantity';
                        $this->ajaxReturn($json);
                        exit;
                    }
                }
            }else{
                $json['result'] = 'findgn';
                $this->ajaxReturn($json);
                exit;
            }
            $data['get_num'] = I('post.get_num','','intval');
            $data['get_person'] =  I('request.get_person','','trim');
            $data['purpose'] = I('request.purpose','','trim');
            $data['is_return'] = I('request.is_return','0','intval');
            $data['remark'] = I('request.remark','','trim');
            $data['add_time'] = time();
            $data['get_time'] = strtotime($_REQUEST['get_time']);
            if($this->goodsout->create($data)){
                $addgoodsout = $this->goodsout->data($data)->add();
                if($addgoodsout){
                    $this->VisitOperation('添加['.$addgoodsout.','.$_REQUEST['get_person'].']',1);
                    $json['result'] = 'success';
                }else{
                    $this->VisitOperation('添加['.$addgoodsout.','.$_REQUEST['get_person'].']',2);
                    $json['result'] = 'error';
                }
            }
            else{
                $json['result'] = 'error';
                $error = $this->goodsout->getError();
                $this->resMsg($error,'',2);
            }
            $this->ajaxReturn($json);
        }
    }
    public function returnPro(){

        $selgoodsout = M('goods_out')->where($where)->order('return_time DESC')->select();
        $this->assign('list',$selgoodsout);
        $this->display();
    }
    public function is_return(){
        if($_REQUEST['par'] == 'is_return'){
            $where['id'] = $_REQUEST['id'];
            $is_return_num = $_REQUEST['data'];
            if($is_return_num == '1'){
                $data['is_return'] = $is_return_num;
                $res = M('goods_out')->where($where)->data($data)->save();
                if($res){
                    $this->VisitOperation('是否归还['.$_REQUEST['id'].','.$_REQUEST['data'].']',1);
                    $json['result'] = 'suc';
                }else{
                    $this->VisitOperation('是否归还['.$_REQUEST['id'].','.$_REQUEST['data'].']',2);
                    $json['result'] = 'err';
                }
            }elseif ($is_return_num == '5'){
                if(!empty($_REQUEST['id'])){
                    $selgoodsout = M('goods_out')->where($where)->find();
                    $selgoodsenter = M('goods_enter')->where(array('id'=>$selgoodsout['ge_id']))->find();
                    $get_num = $selgoodsout['get_num'];
                    $quantity = $selgoodsenter['quantity'];
                    $num = $get_num+$quantity;
                    $datage['quantity'] = $num;
                    $savegoodsenter = M('goods_enter')->where(array('id'=>$selgoodsout['ge_id']))->data($datage)->save();
                    if($savegoodsenter){
                        $data['status'] = 5;
                        $data['is_return'] = $is_return_num;
                        $data['return_time'] = time();
                        $res = M('goods_out')->where($where)->data($data)->save();
                        if($res){
                            $this->VisitOperation('已归还['.$_REQUEST['id'].','.$_REQUEST['data'].']',1);
                            $json['result'] = 'suc';
                        }else{
                            $this->VisitOperation('已归还['.$_REQUEST['id'].','.$_REQUEST['data'].']',2);
                            $json['result'] = 'err';
                        }
                    }else{
                        $json['result'] = 'err';
                    }
                }
            }else{
                $json['result'] = 'err';
            }
        }else{
            $json['result'] = 'err';
        }
        $this->ajaxReturn($json);
    }
    //添加页面
    public function add(){
        //查询物品分类
        $this->assign('gc_list', D('GoodsClass')->GetGoodsClass(1));
        $this->display();
    }
    /**
     * 获取产品分类列表
     */
    public function GetGoodsClass(){
        $goodsout_where['status'] = 1;
        $goodsout_order = 'sort DESC';
        $goodsout_field = 'id,name';
        $goodsout_list = D('GoodsClass')->where($goodsout_where)->order($goodsout_order)->field($goodsout_field)->select();
        return $goodsout_list;
    }
    /**
     * 编辑页面，查询入库物品
     */
    public function GetGoodsEdit(){
        $goodsout_where['status'] = 1;
        $goodsout_order = 'sort DESC';
        $goodsout_field = 'id,name';
        $goodsout_list = D('GoodsEnter')->field($goodsout_field)->where($goodsout_where)->select();
        return $goodsout_list;
    }
    /**
     * Ajax获取物品
     */
    public function GetGoods(){
        $goodsout_where['gc_id'] = I('gc_id');
        $goodsout_where['status'] = 1;
        $goodsout_order = 'id DESC';
        $goodsout_field = 'id,name';
        $goodsout_list = D('GoodsEnter')->where($goodsout_where)->order($goodsout_order)->field($goodsout_field)->select();
        if($goodsout_list){
            $this->ajaxReturn($goodsout_list);
        }else{
            $json['result'] = 1;
            $this->ajaxReturn($json);
        }

    }
    /**
     * Ajax获取单个物品详细
     */
    public function getGoodsContent(){
        $goodsout_where['id'] = I('ge_id');
        $goodsout_field = 'id,name,unit,quantity';
        $goodsout_list = D('GoodsEnter')->GetOne($goodsout_where,$goodsout_field);
        if($goodsout_list){
            $this->ajaxReturn($goodsout_list);
        }else{
            $json['result'] = 1;
            $this->ajaxReturn($json);
        }

    }

}