<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/2
 * Time: 10:19
 */

namespace Admin\Controller;
use Common\ORG\Util\Page;
class MobileController extends BaseController
{
    public $model;
    public $mobileout;
    public function _initialize()
    {
        parent::_initialize();
        $this->model = D('MobileEnter');
        $this->mobileout = D('MobileOut');
    }
    public function verfiyMobile(){
        $mobile['mobile'] = $_REQUEST['mobile'];
        $res = M('recharge')->where($mobile)->find();
        if($res){
            $json['res'] = '1';
            $json['id'] = $res['id'];
        }else{
            $json['res'] = '2';
        }
        $this->ajaxReturn($json);
    }
    public function downExcelTel(){
        $this->VisitOperation('下载Excel模板');
        $file_name = "MobileDemo.xls";     //下载文件名
        $file_dir = "./Public/";
        //http_get(U($file_dir.$file_name));
        $this->DownloadFile($file_name);
//        $file_dir.$file_name;
    }
    public function import_execlt(){
        $gc_id = I('post.gc_id');
        $gcp_id = I('post.gcp_id');
        if($gc_id == 0){
           // $this->error('请选择手机型号！');
        }
        if($gcp_id == 0){
            //$this->error('请选择产品分类！');
        }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('xls', 'xlsx', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/xls/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            foreach($info as $file){
                $filename = './Uploads/xls/'.$file['savepath'].$file['savename'];
            }
            $this->VisitOperation('导入Excel['.$filename.',手机型号ID:'.$_REQUEST['gc_id'].']',1);
            import('Org.Util.excel.PHPExcelReader');
            $excel = new \Spreadsheet_Excel_Reader($filename, true, "UTF-8");
            $sheet = $excel->sheets[0]["cells"];
            $max = 1;
            $num = 0;
            foreach ($sheet as $rows => $rdata) {
                /* $name = trim($rdata[1]); //名称
                 $pro_id = trim($rdata[2]); //产品编号
                 $wxname = trim($rdata[3]);//微信号
                 $get_person = trim($rdata[4]);//使用人
                 $remark = trim($rdata[5]); //备注
                 $unit = trim($rdata[6]); //入库设置单位
                 $par = trim($rdata[7]); //参数*/
                $pro_id = trim($rdata[1]); //产品编号
                $wxname = trim($rdata[2]); //微信
                $get_person = trim($rdata[3]);//使用人
                $remark = trim($rdata[4]);//备注
               $gcs_id = trim($rdata[5]); //手机型号
               $gcp_id = trim($rdata[6]); //产品分类
             //  $par = trim($rdata[7]); //参数
                if (is_numeric($gcs_id)) {
                    $max++;
                    $data['gcp_id'] = $gcp_id;
                    $data['gcs_id'] = $gcs_id;
                    $data['m_id'] = 0;
                    $data['mobile_name'] = '手机';
                    $data['get_num'] = 1;
                    $data['pro_id'] = $pro_id;
                    $data['wxname'] = $wxname;
                    $data['get_person'] = $get_person;
                    $data['unit'] = '个';
                    $data['purpose'] = 0;
                    $data['remark'] = $remark;
                    $data['get_time'] = time();
                    $data['add_time'] = time();
                    $data['add_user'] = $this->cid;
                    $res = M("mobile_out")->add($data);
                    if ($res) {
                        $num++;
                    }
                    continue;
                }
            }
            echo '<br>';
            echo '<p style="margin:0 0 0 10px;font-size:14px;color:#2e8965;">读取到'.$max.'条，导入成功'.$num.'条。</p>';

        }
    }
    //编辑
    public function editcard(){
        if(empty($_REQUEST['id'])){ exit; }
        if(IS_POST){
            $where['id'] = $_REQUEST['id'];
            $data = readPost($_REQUEST);
            $data['re_time'] = strtotime($data['re_time']);
            $data['add_user'] = $this->cid;
            $res = M('recharge_log')->where($where)->data($data)->save();
            if($res){
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['m_id'].']',1);
                $this->resMsg(C("operation_succ_status"),'/cardlist',1);
            }else{
                $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['m_id'].']',2);
                $this->resMsg(C('operation_error_status'),'/cardlist',1);
            }
        }
        $where['id'] = $_REQUEST['id'];
        $res = M('recharge_log')->where($where)->find();
        $this->assign('list',$res);
        $this->assign('para',1);
        $this->assign('mno', D('GoodsClass')->GetGoodsClass(4));
        $this->assign('money', D('GoodsClass')->GetGoodsClass(5));
        $this->VisitOperation('查看['.$_REQUEST['id'].','.$res['mobile'].']',1);
        $this->display('addcard');
    }
    public function enterimport(){
        $this->VisitOperation('导入Excel');
        $this->assign('gclist',D('GoodsClass')->GetGoodsClass(2));
        $this->assign('gclistr',D('GoodsClass')->GetGoodsClass(3));
        $this->display();
    }
    //手机卡充值添加
    public function addcard(){
        if(IS_POST){
            $mobpar = $_REQUEST['mobpar'];
            $data = readPost($_REQUEST);
            if(empty($data['mobile'])){
                $this->resMsg('手机号码不能为空！','',2);
            }
            $data['add_time'] = time();
            $data['add_user'] = $this->cid;
            $data['re_time'] = strtotime($data['re_time']);
            if(empty($mobpar)){
                $res= M('recharge')->add($data);
                if($res){
                    $data['mobile_id'] = $res;
                }
            }else{
                $data['mobile_id'] = $mobpar;
            }
            $res1 = M('recharge_log')->add($data);
            if($res1 > 0){
                if(!empty($mobpar)){
                    $recharger = M('recharge')->where(array('id'=>$mobpar))->find();
                    $data123['record'] = $recharger['record'] + 1;
                    M('recharge')->where(array('id'=>$mobpar))->save($data123);
                }
                $this->VisitOperation('添加['.$res.','.$_REQUEST['m_id'].']',1);
                $this->resMsg(C("operation_succ_status"),'/cardlist',1);
            }else{
                $this->VisitOperation('添加['.$res.','.$_REQUEST['m_id'].']',2);
                $this->resMsg(C("operation_succ_status"),'/cardlist',1);
            }
        }
        $this->assign('mno', D('GoodsClass')->GetGoodsClass(4));
        $this->assign('money', D('GoodsClass')->GetGoodsClass(5));
        $this->display();
    }
    //手机卡充值列表
    public function cardlist(){
        $this->VisitOperation('手机卡充值列表');
        $fdata = readPost($_REQUEST);
        $now_Y = date("Y", time());
        $now_m = date("m", time());
        $now_d = date("d", time());
        $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
        $now_low = strtotime($now_time);
        $time1 = $now_low - 86400 * 29;
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

        $where = array();
        $where["add_time"] = $xtime;
        $where["status"] = 1;
        if (!empty($fdata["mobile"])) {
            if ($fdata["mobile"] != "") {
                $where["mobile"] = array("like", "%" . trim($fdata["mobile"]) . "%");
            }
        }
        if (!empty($fdata["product"])) {
            if ($fdata["product"] != "") {
                $where["product"] = array("like", "%" . trim($fdata["product"]) . "%");
            }
        }
        if (!empty($fdata["remark"])) {
            if ($fdata["remark"] != "") {
                $where["remark"] = array("like", "%" . trim($fdata["remark"]) . "%");
            }
        }
        if(empty($_REQUEST['id'])){
            $DB = M('recharge'); // 实例化User对象
        }else{
            $where["mobile_id"] = $_REQUEST['id'];
            $DB = M('recharge_log');
            $idinfo = $DB->where(array('id'=>$_REQUEST['id']))->find();
            $this->assign('idinfo', $idinfo);
            $this->assign('par', 15);
        }
        $count = $DB->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        if($count == '0'){
            $this->assign('not_found',2);
        }
        $list = $DB->where($where)->order('add_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        if(!empty($_REQUEST['id'])){
            foreach($list as $k1=>$v1){
                $moneyd +=$v1['money'];
            }
            $this->assign('countr', $count);
            $this->assign('mountr', $moneyd);
        }
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->assign('stat', $stat);
        $this->display();
    }
    //插入出库数据
    public function addData(){
        if($_POST !== false){
            if($_REQUEST['m_id']){
                $where['id'] = I('request.m_id');
                $where['name'] = I('request.mobile_name','','trim');
                //按照物品id跟名称查询
                $googsenter_find = D('MobileEnter')->where($where)->find();
              //  $this->ajaxReturn($googsenter_find);exit;
                if($googsenter_find){
                    //计算剩余量
                    $num = $googsenter_find['quantity'] - $_REQUEST['get_num'];
                    if($num < $googsenter_find['quantity']){
                        $data1['quantity'] = $num; //修改物品剩余量
                        $data['get_surplus'] = $num; //记录领取后的剩余量
                        $data['gcs_id'] = $googsenter_find['gcs_id']; //手机型号id
                        $data['m_id'] = $googsenter_find['id'];  //记录物品id
                        $data['get_num'] = I('post.get_num','','intval');//领取数量
                        $data['pro_id'] = I('request.pro_id','','intval');//产品编号
                        $data['wxname'] = I('request.wxname','','intval');//微信
                        $data['station_id'] = I('request.station_id','','intval');//职位
                        $data['section_id'] = I('request.section_id','','intval');//部门
                        $data['get_person'] =  I('request.get_person','','trim');//使用人
                        $data['get_time'] = strtotime($_REQUEST['get_time']);//领取时间
                        $data['add_time'] = time();//写入时间
                      //  $data['purpose'] = I('request.purpose','','trim');//用途
                        $data['is_return'] = I('request.is_return','0','intval');//是否归还
                        $data['remark'] = I('request.remark','','trim');//备注
                        $data['gcp_id'] = I('post.gcp_id','','intval');//产品分类id
                        if($this->mobileout->create($data)){
                            $addgoodsout = M('mobile_out')->data($data)->add();
                            if($addgoodsout){
                                $savegoogds = D('MobileEnter')->where($where)->data($data1)->save();
                                if($savegoogds){
                                    $this->VisitOperation('添加['.$addgoodsout.','.$_REQUEST['get_person'].']',1);
                                    $json['result'] = 'success';
                                }else{
                                    $json['result'] = 'error';
                                }
                            }else{
                                $this->VisitOperation('添加['.$addgoodsout.','.$_REQUEST['get_person'].']',2);
                                $json['result'] = 'error';
                            }
                        }else{
                            $json['result'] = 'error';
                            $error = $this->mobileout->getError();
                            $this->resMsg($error,'',2);
                            $this->VisitOperation('非法操作['.$addgoodsout.','.$_REQUEST['get_person'].']',2);
                        }
                    }else{
                        $json['result'] = 'quantity';
                        $this->ajaxReturn($json);
                        exit;
                    }
                }else{
                    $json['result'] = 'error';
                }
            }else{
                $json['result'] = 'findgn';
                $this->ajaxReturn($json);
                exit;
            }
            $this->ajaxReturn($json);
        }
    }
    /**
     * Ajax获取单个物品详细
     */
    public function getGoodsContent(){
        $goodsout_where['id'] = I('ge_id');
        $goodsout_field = 'id,name,unit,quantity';
        $goodsout_list = D('MobileEnter')->where($goodsout_where)->field($goodsout_field)->find();
        if($goodsout_list){
            $this->ajaxReturn($goodsout_list);
        }else{
            $json['result'] = 1;
            $this->ajaxReturn($json);
        }

    }
    /**
     * Ajax获取物品
     */
    public function GetGoods(){
        $goodsout_where['gcs_id'] = I('gc_id');
        $goodsout_where['status'] = 1;
        $goodsout_order = 'id DESC';
        $goodsout_field = 'id,name';
        $goodsout_list = D('MobileEnter')->where($goodsout_where)->order($goodsout_order)->field($goodsout_field)->select();
        if($goodsout_list){
            $this->ajaxReturn($goodsout_list);
        }else{
            $json['result'] = 1;
            $this->ajaxReturn($json);
        }

    }
    //查看详细
    public function outread(){
        if(empty($_REQUEST['id'])){exit;}
        $res = M('mobile_out')->where(array('id'=>$_REQUEST['id']))->find();
        $this->assign('list',$res);
        $this->display();
    }
    public function EditDataOut() {
        if(empty($_REQUEST['id'])){ exit; }
        if(empty($_REQUEST['par'])){ exit; }
        $back = cookie("back");
        if($_POST){
            $data = readPost($_REQUEST);
          //  dump($data); exit;
            $where['id'] = $_REQUEST['id'];
            if($this->mobileout->create($data)){
                if($this->mobileout->where($where)->data($data)->save()){
                    $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['get_person'].']',1);
                    $this->resMsg(C("operation_succ_status"),'',2);
                    redirect($back);
                    exit;
                }else{
                    $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['get_person'].']',2);
                    $this->resMsg(C('operation_error_status'),'',2);
                    redirect($back);
                    exit;
                }
            }
            else{
                $error = $this->model->getError();
                $this->resMsg($error,'',2);
                exit;
            }
        }
        $res = M('mobile_out')->where(array('id'=>$_REQUEST['id']))->find();
        $this->assign('list',$res);
        $this->assign('par',$_REQUEST['par']);
        $this->display('outread');
    }
    //添加出库
    public function outadd(){
        $section_id = D('GoodsClass')->GetGoodsClass(7);
        foreach($section_id as $k=>$v){
            $arr[$v['id']] = $v['name'];
        }
        $this->assign('section_id_v',$arr);
        $this->assign('gc_list', D('GoodsClass')->GetGoodsClass(2));
        $this->assign('gcp_list', D('GoodsClass')->GetGoodsClass(3));
        $this->assign('section_id',$section_id);
        $this->assign('station_id', D('GoodsClass')->GetGoodsClass(8));
        $this->display();
    }
    //出库
    //status = 1 状态正常
    //status = 5 入库归还
    //status = 0 已删除
    //is_return = 5已经归还
    //status = 7 以旧换新
    //status = 6 导入归还
    public function out(){
        cookie("back", __SELF__);
       // $where = array();
        $par = $_GET['par'];
        if(isset($par)){
            $where['status'] = $par ;
           if($par == '5' || $par == '6'){
              $where['is_return'] = $par;
           }
            $this->VisitOperation('已归还物品');
            $this->assign('parad',$par);
        }else{
            $where['status'] = 1;
            $this->VisitOperation('物品出库');
        }
       // dump($where);
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
        if (empty($fdata["m_id"])) {
            $fdata["m_id"] = 0;
        }
        if (empty($fdata["gcs_id"])) {
            $fdata["gcs_id"] = 0;
        }
        if (empty($fdata["gcp_id"])) {
            $fdata["gcp_id"] =0;
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
        if (!empty($fdata["pro_id"])) {
            if ($fdata["pro_id"] != "") {
                $where["pro_id"] = array("like", "%" . trim($fdata["pro_id"]) . "%");
            }
        }
        if (!empty($fdata["remark"])) {
            if ($fdata["remark"] != "0") {
                $where["remark"] = array("like", "%" . trim($fdata["remark"]) . "%");
            }
        }
        if (!empty($fdata["gcp_id"])) {
            if ($fdata["gcp_id"] != "0") {
                $where["gcp_id"] = array("like", "%" . trim($fdata["gcp_id"]) . "%");
            }
        }
        if (!empty($fdata["gcs_id"])) {
            if ($fdata["gcs_id"] != "0") {
                $where["gcs_id"] = array("like", "%" . trim($fdata["gcs_id"]) . "%");
            }
        }
        if (!empty($fdata["station_id"])) {
            if ($fdata["station_id"] != "0") {
                $where["station_id"] = array("like", "%" . trim($fdata["station_id"]) . "%");
            }
        }
        if (!empty($fdata["section_id"])) {
            if ($fdata["section_id"] != "0") {
                $where["section_id"] = array("like", "%" . trim($fdata["section_id"]) . "%");
            }
        }
        if (!empty($fdata["m_id"])) {
            if ($fdata["m_id"] != "0") {
                $where["m_id"] = array("like", "%" . trim($fdata["m_id"]) . "%");
            }
        }
        $count = $this->mobileout->where($where)->count();
        $page = new Page($count,15);
        $res = $this->mobileout->where($where)
            ->order('id DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        if($count > 0 ){
            $get_num = $this->mobileout->where($where)->select();
            foreach($get_num as $get_num_v){
                $getnum_r += $get_num_v['get_num'];
            }
         //   $this->getMobileInfoList();

        }else{
            $getnum_r = 0;
            $this->assign('count_not',1);
        }
        $this->assign('getgenter',$this->getMobileInfoList());
        //$sum = 'SELECT SUM(get_num)  AS num FROM tp_mobile_out WHERE STATUS=1 AND is_return = 0';
        //$get_num = M()->query($sum);
      //  dump($get_num);
        $this->assign('allgetnumm',$getnum_r);
        $this->assign('page', $page->show());
        $this->assign('list',$res);
        $this->assign('allcount',$count);
        $this->display();
    }
    private function get_goods_class_select($gcsid){
        $goodsclasss = M('goods_class')->field('id,name')
            ->where(array('status'=>1,'id'=>array('in',$gcsid)))
            ->select();
        return $goodsclasss;
    }
    //去除mobile_enter gcs_id gcp_id m_id 重复数据
    private function getMobileInfoList(){
        $where['status'] = 1;
        $glist = M('mobile_out')->field('id,gcs_id,gcp_id,m_id,station_id,section_id')->where($where)->order('id DESC')->select();
        if($glist){
            foreach($glist as $glek=>$glev){
                $getgoodsenterlist[] = $glev['m_id'];
                $getgoodsclasslist[] = $glev['gcs_id'];
                $getgoodsmobilelist[] = $glev['gcp_id'];
                $getgoodsmobile_station_id[] = $glev['station_id'];
                $getgoodsmobile_section_id[] = $glev['section_id'];
            }
        }
        //去除数组重复数据
        $gcpid = array_unique($getgoodsmobilelist);
        $gcsid = array_unique($getgoodsclasslist);
        $mid = array_unique($getgoodsenterlist);
        $station_id = array_unique($getgoodsmobile_station_id);
        $section_id = array_unique($getgoodsmobile_section_id);
        //通过id查询名称
        $goodsenter = M('mobile_enter')->field('id,name')
            ->where(array('status'=>1,'id'=>array('in',$mid)))
            ->select();
        $goodsclass = $this->get_goods_class_select($gcpid);
        $goodsclasss = $this->get_goods_class_select($gcsid);
        $goodsclass_station_id = $this->get_goods_class_select($station_id);
        $goodsclass_section_id = $this->get_goods_class_select($section_id);
        //赋值新数组
        foreach(array_unique($goodsenter) as $kt1=>$kv1){
            $new_arr['midinfo'][$kv1['id']] =$kv1['name'];
        }
        foreach($goodsclass as $kt11=>$kv11){
            $new_arr['gcpinfo'][$kv11['id']] =$kv11['name'];
        }
        foreach($goodsclasss as $kt113=>$kv113){
            $new_arr['gcsinfo'][$kv113['id']] =$kv113['name'];
        }
        foreach($goodsclass_station_id as $kt1131=>$kv1131){
            $new_arr['station_id'][$kv1131['id']] =$kv1131['name'];
        }
        foreach($goodsclass_section_id as $kt1132=>$kv1132){
            $new_arr['section_id'][$kv1132['id']] =$kv1132['name'];
        }
        return $new_arr;
    }
    //Excel是否归还
    public function excelIsReturn(){
        if($_REQUEST['par'] == 'excel'){
            $where['id'] = $_REQUEST['id'];
            $is_return_num = $_REQUEST['data'];
            if($is_return_num == '1'){
                $data['is_return'] = $is_return_num;
                $res = M('mobile_out')->where($where)->data($data)->save();
                if($res){
                    $this->VisitOperation('是否归还['.$_REQUEST['id'].','.$_REQUEST['data'].']',1);
                    $json['result'] = 'suc';
                }else{
                    $this->VisitOperation('是否归还['.$_REQUEST['id'].','.$_REQUEST['data'].']',2);
                    $json['result'] = 'err';
                }
            }elseif($is_return_num == '5'){

            }else{
                $json['result'] = 'err';
            }
        }else{
            $json['result'] = 'err';
        }
        $this->ajaxReturn($json);
    }
    //是否归还
    public function is_return(){
        if($_REQUEST['par'] == 'is_return'){
            $where['id'] = $_REQUEST['id'];
            $is_return_num = $_REQUEST['data'];
            $especially = $_REQUEST['especially'];
            if($is_return_num == '1'){
                $data['is_return'] = $is_return_num;
                $res = M('mobile_out')->where($where)->data($data)->save();
                if($res){
                    $this->VisitOperation('是否归还['.$_REQUEST['id'].','.$_REQUEST['data'].']',1);
                    $json['result'] = 'suc';
                }else{
                    $this->VisitOperation('是否归还['.$_REQUEST['id'].','.$_REQUEST['data'].']',2);
                    $json['result'] = 'err';
                }
            }elseif ($is_return_num == '5'){
                    if(!empty($_REQUEST['id'])){
                        if(empty($especially)){
                            $selgoodsout = M('mobile_out')->where($where)->find();
                            $selgoodsenter = M('mobile_enter')->where(array('id'=>$selgoodsout['m_id']))->find();
                            $get_num = $selgoodsout['get_num'];
                            $quantity = $selgoodsenter['quantity'];
                            $num = $get_num+$quantity;
                            $datage['quantity'] = $num;
                            $savegoodsenter = M('mobile_enter')->where(array('id'=>$selgoodsout['m_id']))->data($datage)->save();
                            $data['status'] = 5;
                            $data['is_return'] = $is_return_num;
                        }else{
                            $savegoodsenter = true;
                            $data['status'] = 6;
                            $data['is_return'] = 6;
                        }
                        if($savegoodsenter){
                            $data['return_time'] = time();
                            $res = M('mobile_out')->where($where)->data($data)->save();
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

    //查看入库记录
    public function Edetail(){
        $db = M('mobile_quantity');
        $id = $_REQUEST['id'];
        if(empty($id)){exit;}
        $where['m_id'] = $id;
        $count = $db->where($where)->count();
        $page = new Page($count,20);
        $res = $db->where($where)
            ->order('add_time DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        if($count == '0'){
            $this->assign('not_found',1);
        }
        $this->assign('page', $page->show());
        $this->VisitOperation('查看入库记录['.$_REQUEST['id'].']');
        $this->assign('list',$res);
        $this->display();
    }
    //已入库
    public function ajaxGetClass(){
        if($_REQUEST['par'] == '1'){
            $id = $_REQUEST['id'];
            if(!empty($id)){
                $res = M('mobile_enter')->where(array('id'=>$id))->find();
                $gClass = M('goods_class')->where(array('id'=>$res['gcs_id']))->find();
                if($res){
                    $this->VisitOperation('选择['.$res['id'].','.$res['name'].']',1);
                    $json['res'] = $res;
                    $json['class'] = $gClass;
                }else{
                    $this->VisitOperation('选择['.$res['id'].','.$res['name'].']',2);
                    $json['res'] = 'err';
                }
            }else{
                $json['res'] = 'err';
            }
        }elseif($_REQUEST['par'] == '2'){
            $id = $_REQUEST['id'];
            if(!empty($id)){
                $newquantity = $_REQUEST['quantity'];
                $selres = M('mobile_enter')->where(array('id'=>$id))->find();
                $num = $selres['quantity'] + $newquantity;
                $data['quantity'] = $num;
                $res = M('mobile_enter')->where(array('id'=>$id))->data($data)->save();
                if($res){
                    $quantity['add_num'] = $newquantity;
                    $quantity['add_time'] = time();
                    $quantity['add_user'] = $this->cid;
                    $quantity['m_id'] = $selres['id'];
                    $quantity['gc_class'] = $_REQUEST['gc_id'];
                    $quantity['quantity'] = $num;
                    $quannum = M('mobile_quantity')->add($quantity);
                    if($quannum){
                        $this->VisitOperation('添加['.$selres['name'].','.$newquantity.']',1);
                        $json['res'] = 'suc';
                    }
                }else{
                    $this->VisitOperation('添加['.$selres['name'].','.$newquantity.']',2);
                    $json['res'] = 'err';
                }
            }
        }
        $this->ajaxReturn($json);
    }
    public function index(){
        $this->VisitOperation('手机入库');
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

        $xtime = array(array("egt", $start_time), array("elt", $end_time));

        $where['status'] = 1;
        $where["add_time"] = $xtime;
        if (!empty($fdata["uname"])) {
            if ($fdata["uname"] != "") {
                $where["name"] = array("like", "%" . trim($fdata["uname"]) . "%");
            }
        }
        if (!empty($fdata["gc_namer"])) {
            if ($fdata["gc_namer"] != "0") {
                $where["gcs_id"] = array("like", "%" . trim($fdata["gc_namer"]) . "%");
            }
        }
        $where['status'] = 1;
        $count = $this->model->where($where)->count();
        $page = new Page($count,20);
        $res = $this->model->where($where)
            ->order('id DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        $this->assign('page', $page->show());
        $this->assign('list',$res);
       // $where[]
        if($count > 0 ){
            $get_num = $this->model->where($where)->select();
            foreach($get_num as $get_num_v){
                $getnum_r += $get_num_v['quantity'];
            }
            $this->assign('getgenter',$this->getMobileInfoList());
        }else{
            $getnum_r = 0;
            $this->assign('count_not',1);
        }
        $this->assign('allcount',$count);
        $this->assign('allgetnumm',$getnum_r);
        $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(2));
        $this->display();
    }
    //物品入库
    public function add(){
        if(IS_POST){
            $data = readPost($_REQUEST);
           // dump($data);exit;
            $data['add_time'] = time();
            $data['add_user'] = $this->cid;
            if($this->model->create($data)){
                $addgoodsenter = $this->model->add($data);
                if($addgoodsenter){
                    $quantity['add_num'] = $_REQUEST['quantity'];
                    $quantity['add_time'] = time();
                    $quantity['add_user'] = $this->cid;
                    $quantity['m_id'] = $addgoodsenter;
                    $quantity['gc_class'] = $_REQUEST['gcs_id'];
                    $quantity['quantity'] = $_REQUEST['quantity'];
                    $quannum = M('mobile_quantity')->add($quantity);
                    if($quannum){
                        $this->VisitOperation('添加['.$addgoodsenter.','.$_REQUEST['name'].']',1);
                        $this->resMsg(C("operation_succ_status"),'/index',1);
                    }
                    exit;
                }else{
                    $this->VisitOperation('添加['.$addgoodsenter.','.$_REQUEST['name'].']',2);
                    $this->resMsg(C('operation_error_status'),'/add',1);
                    exit;
                }
            }else{
                $error = $this->model->getError();
                $this->resMsg($error,'',2);
                exit;
            }
        }
        $this->assign('GoodsClass_list2',D('GoodsClass')->GetGoodsClass(2));
       // $this->assign('GoodsClass_list3',D('GoodsClass')->GetGoodsClass(3));
        $this->display();
    }
    //已入库
    public function prioradd(){
        $this->VisitOperation('已入库');
        $res = M('mobile_enter')->where(array('status'=>1))->field('id,name')->select();
        $this->assign('geList',$res);
        $this->display();
    }

    //编辑
    public function EditData() {
        if(empty($_REQUEST['id'])){ exit; }
        if($_POST){
            $data = readPost($_REQUEST);
            $where['id'] = I('id');
            $data['name'] = I('request.name','','trim');
            $data['unit'] = I('request.unit','','trim');
         //   $data['quantity'] = I('request.quantity','0','intval');
            $data['gcs_id'] = I('request.gcs_id','','intval');
            $data['remark'] = I('request.remark','','trim');
            if($this->model->create($data)){
                if($this->model->where($where)->data($data)->save()){
                    $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['name'].']',1);
                    $this->resMsg(C("operation_succ_status"),'/index',1);
                    exit;
                }else{
                    $this->VisitOperation('修改['.$_REQUEST['id'].','.$_REQUEST['name'].']',2);
                    $this->resMsg(C('operation_error_status'),'/index',1);
                    exit;
                }
            }
            else{
                $error = $this->model->getError();
                $this->resMsg($error,'',2);
                exit;
            }
        }
        $where['id'] = $_REQUEST['id'];
        $res = $this->model->where($where)->find();
        $this->assign('list',$res);
        $this->assign('GoodsClass_list2',D('GoodsClass')->GetGoodsClass(2));
        $this->VisitOperation('查看['.$res['id'].','.$res['name'].']',1);
        $this->display('add');
    }
    //删除
    public function delete_data(){
        $where['id'] = $_REQUEST['id'];
        $data['status'] = $_REQUEST['status'];
        $del = $_REQUEST['par'];
        switch ($del) {
            case 'index':
                $res = $this->model->where($where)->data($data)->save();
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