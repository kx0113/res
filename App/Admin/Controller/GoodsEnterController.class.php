<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class GoodsEnterController extends BaseController {
    public $model;
    public function _initialize(){
        parent::_initialize();
        //模型初始化
        $this->model = D('GoodsEnter');
    }
    public function Edetail(){
        $db = M('goods_quantity');
        $id = $_REQUEST['id'];
        if(empty($id)){exit;}
        $where['ge_id'] = $id;
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
    public function ajaxGetClass(){
        if($_REQUEST['par'] == '1'){
            $id = $_REQUEST['id'];
            if(!empty($id)){
                $res = M('goods_enter')->where(array('id'=>$id))->find();
                $gClass = M('goods_class')->where(array('id'=>$res['gc_id']))->find();
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
                $selres = M('goods_enter')->where(array('id'=>$id))->find();
                $num = $selres['quantity'] + $newquantity;
                $data['quantity'] = $num;
                $res = M('goods_enter')->where(array('id'=>$id))->data($data)->save();
                if($res){
                    $quantity['add_num'] = $newquantity;
                    $quantity['add_time'] = time();
                    $quantity['add_user'] = $this->cid;
                    $quantity['ge_id'] = $selres['id'];
                    $quantity['gc_class'] = $_REQUEST['gc_id'];
                    $quantity['quantity'] = $num;
                    $quannum = M('goods_quantity')->add($quantity);
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
    public function prioradd(){
        $this->VisitOperation('已入库');
        $res = M('goods_enter')->where(array('status'=>1))->field('id,name,unit,quantity,gc_id')->select();
        $this->assign('geList',$res);
        $this->display();
    }
    public function export_goodsout()
    {
        $this->VisitOperation('导出Excel');
        $now = date('Y-m-d',time());
        $filenamef = $now."出库导出.xls";
        header('Content-Type: application/vnd.ms-excel;charset=gbk');
        header('Content-Disposition: attachment;filename='.$filenamef);
        header('Cache-Control: max-age=0');
        // error_reporting();
        $fp = fopen('php://output', 'a');

        // 输出Excel列头信息
        $head = array(
            '物品ID',
            '物品名称',
            '物品分类',
            '领取人',
            '入库设置单位',
            '领取数量',
            '现总剩余量',
            '用途',
            '是否归还',
            '领取日期',
            '备注'
        );
        $tab="\t"; $br="\n";
        $head = implode($tab, $head);
        $head .=$br;
       // $head = iconv('utf-8', 'utf-8', $head);
        echo iconv("UTF-8","GBK//IGNORE",$head);
        // 从数据库中获取数据
        set_time_limit(1800) ;

        $cnt = 0;

        $limit = 50000;
        $sql = "SELECT
  a.id,
  c.name gename ,b.name gcname,a.get_person,c.unit,a.get_num,c.quantity,a.purpose,a.is_return,a.add_time,a.remark
FROM
  tp_goods_out a
  INNER JOIN tp_goods_class b
    ON a.gc_id = b.id
  INNER JOIN tp_goods_enter c
    ON a.ge_id = c.id WHERE a.status =1";
        $data = M()->query($sql);

        foreach ($data as $key => $value) {
            $value['gename'] = iconv('utf-8','gbk',$value['gename']);
            $value['gcname'] = iconv('utf-8','gbk',$value['gcname']);
            $value['get_person'] = iconv('utf-8','gbk',$value['get_person']);
            $value['unit'] = iconv('utf-8','gbk',$value['unit']);
            $value['quantity'] = iconv('utf-8','gbk',$value['quantity']);
            $value['get_num'] = iconv('utf-8','gbk',$value['get_num']);
            $value['purpose'] = iconv('utf-8','gbk',$value['purpose']);
            $value['remark'] = iconv('utf-8','gbk',$value['remark']);
            $value['add_time']  =   "'".date('Y-m-d H:i:s',$value['add_time'])."'";
            $value = implode($tab, $value);
            $value .=$br;
            echo $value;
            $cnt++;
            if ($limit == $cnt) { //刷新一下输出buffer，防止由于数据过多造成问题
                ob_flush();
                flush();
                exit;
            }

            fputcsv($fp, $value);
        }
    }
    public function enterimport(){
        $this->VisitOperation('导入Excel');
        $this->assign('gclist',D('GoodsClass')->GetGoodsClass(1));
        $this->display();
    }
    public function downExcelTel(){
        $this->VisitOperation('下载Excel模板');
        $file_name = "GoodsEnterTem.xls";     //下载文件名
        $this->DownloadFile($file_name);
    }
    public function import_execlt(){
        $gc_id = I('post.gc_id');
        if($gc_id == 0){
           $this->error('请选择物品分类！');
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
            $this->VisitOperation('导入Excel['.$filename.',物品分类ID:'.$_REQUEST['gc_id'].']',1);
            import('Org.Util.excel.PHPExcelReader');
            $excel = new \Spreadsheet_Excel_Reader($filename, true, "UTF-8");
            $sheet = $excel->sheets[0]["cells"];
            $max = 1;
            $num = 0;
            foreach ($sheet as $rows => $rdata) {
                $name = trim($rdata[1]); //物品名称
                $unit = trim($rdata[2]); //单位
                $quantity = trim($rdata[3]);//数量
                $remark = trim($rdata[4]); //备注
                if (is_numeric($quantity)) {
                    $max++;
                    $data['name'] = $name;
                    $data['unit'] =  $unit;
                    $data['quantity'] = $quantity;
                    $data['remark'] = $remark;
                    $data['gc_id'] = $gc_id;
                    $data['add_time'] = time();
                    $data['add_user'] = $this->cid;
                    $res = M("goods_enter")->add($data);
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
    //物品入库
    public function add(){
        if(IS_POST !== false){
            $data['name'] = I('request.name','','trim');
            $data['unit'] = I('request.unit','','trim');
            $data['quantity'] = I('request.quantity','0','intval');
            $data['gc_id'] = I('request.gc_id','','intval');
            $data['remark'] = I('request.remark','','trim');
            $data['add_time'] = time();
            $data['add_user'] = $this->cid;
            if($this->model->create($data)){
                $addgoodsenter = $this->model->AddData($data);
                if($addgoodsenter){
                    $this->VisitOperation('添加['.$addgoodsenter.','.$_REQUEST['name'].']',1);
                    $this->resMsg(C("operation_succ_status"),'/index',1);
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
        $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(1));
        $this->display();
    }
    //入库列表
    public function index(){
        $this->VisitOperation('物品入库');
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
                $where["gc_id"] = array("like", "%" . trim($fdata["gc_namer"]) . "%");
            }
        }
        $where['status'] = 1;
        $count = $this->model->where($where)->count();
        $page = new Page($count,20);
        $res = $this->model->where($where)
            ->order('id DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        if($count > 0 ){
            $get_num = $this->model->where($where)->select();
            foreach($get_num as $get_num_v){
                $getnum_r += $get_num_v['quantity'];
            }
        }else{
            $getnum_r =0;
        }
        //dump($get_num);
        $this->assign('allgetnumm',$getnum_r);
        $this->assign('page', $page->show());
        $this->assign('allcount',$count);
        $this->assign('list',$res);
        $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(1));
        $this->display();
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
        $res = $this->model->SaveData($where,$data);
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
      if($_POST){
          $where['id'] = I('id');
          $data['name'] = I('request.name','','trim');
          $data['unit'] = I('request.unit','','trim');
        //  $data['quantity'] = I('request.quantity','','intval');
          $data['gc_id'] = I('request.gc_id','','intval');
          $data['remark'] = I('request.remark','','trim');
          if($this->model->create($data)){
              if($this->model->SaveData($where,$data)){
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
        $this->assign('GoodsClass_list',D('GoodsClass')->GetGoodsClass(1));
        $this->VisitOperation('查看['.$res['id'].','.$res['name'].']',1);
        $this->display('add');
    }
    //修改状态
    public function show_change(){  
         $res = $this->model
         ->where(array('id' => $_REQUEST['id']))
         ->save(array('status'=>$_REQUEST['status']));
    }

}