<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2016/6/26
 * Time: 15:37
 */

namespace Admin\Controller;

use Think\Controller;
use Common\ORG\Util\Page;

class ResController extends BaseController
{
    public $db,$lang;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = M('res');
        $this->lang = array(
            'name'=>'名称', 'num'=>'金额', 'sub_time'=>'时间', 'remark'=>'备注',
            'add_time'=>'创建时间', 'add_user'=>'创建用户', 'operation'=>'操作',
            'gc_id1'=>'支付方式', 'gc_id2'=>'购买方式', 'gc_id3'=>'姓名',
            'gc_id4'=>'运营商', 'gc_id5'=>'充值金额', 'gc_id6'=>'消费用途',
        );
    }

    /**
     * 验证
     * @param $par
     * @return mixed
     */
    public function commShow($par){
        $all = array(
            'name','num','sub_time','remark',
            'gc_id1', 'gc_id2', 'gc_id3',
            'gc_id4', 'gc_id5', 'gc_id6',
        );
        if($par == 1){
            $arr = array(
                'name','num','sub_time','remark',
                'gc_id1', 'gc_id2', 'gc_id3', 'gc_id6',
            );
        }
        if($par == 2){
            $arr =  array(
                'name','num','sub_time','remark',
                'gc_id1', 'gc_id3', 'gc_id6',
            );
        }
        $this->assign('show',$this->show($arr));
    }
    public function listTitle(){
        $this->assign('tit',$this->lang);
    }

    /**
     * 处理键值对相等
     * @param string $arr
     * @return mixed
     */
    public function show($arr){
      foreach($arr as $k=>$v){
          $narr[$v]=$v;
      }
      return $narr;
     }

    /**
     * 显示分类Class全部
     * @return array
     */
    public function dynamic()
    {
        $num = D('GoodsClass')->field('class')->select();
        foreach ($num as $nk => $nv) {
            $where['class'] = $nv['class'];
            $arr[$nv['class']] = D('GoodsClass')->where($where)->select();
        }
        foreach ($arr as $k => $v) {
            if (count($v) !== 0) {
                $narr['class' . $k] = $v;
            }
        }
        return $narr;
    }
    /**
     * 传输信息
     * @param null
     * @return array
     */
    private function goodsClassInfo()
    {
        $this->assign('goodsclass', $this->dynamic());
        $this->listTitle();
    }
    /**
     * 首页
     * 支付方式 gc_id1
     * 购买方式 gc_id2
     * 姓名 gc_id3
     * 运营商 gc_id4
     * 充值金额 gc_id5
     * 消费用途 gc_id6
     * par = 1 使用
     * par = 2 收货
     * par = 3 充值
     *
     */
    public function index()
    {
        cookie("back", __SELF__);
        $par = $_REQUEST['par'];
        if ($par ==1) {
            $where['par'] = 1;
        } elseif($par ==2) {
            $where['par'] = 2;
        }elseif($par ==3){
            $where['gc_id6'] = 130;
        }
        $this->assign("par", $par);
        $this->VisitOperation('Res首页');
        $fdata = readPost($_REQUEST);
        $now_Y = date("Y", time());
        $now_m = date("m", time());
        $now_d = date("d", time());
        $now_time = $now_Y . "-" . $now_m . "-" . $now_d . " 00:00:00";
        $now_low = strtotime($now_time);
        $time1 = $now_low - 86400 * 60;
        $time2 = $now_low + 86400 * 3;
        if (empty($fdata["start_time"])) {
           // $fdata["start_time"] = date("Y-m-d", $time1);
            $fdata["start_time"] = "2010-01-01";
        }
        if (empty($fdata["end_time"])) {
            $fdata["end_time"] = date("Y-m-d", $time2);
        }
        if (!empty($fdata["name"])) {
            if ($fdata["name"] != "") {
                $where["name"] = array("like", "%" . trim($fdata["name"]) . "%");
            }
        }
        if (!empty($fdata["gc_id2"])) {
            if ($fdata["gc_id2"] != "") {
                $where["gc_id2"] = array("like", "%" . trim($fdata["gc_id2"]) . "%");
            }
        }
        if (!empty($fdata["gc_id3"])) {
            if ($fdata["gc_id3"] != "") {
                $where["gc_id3"] = array("like", "%" . trim($fdata["gc_id3"]) . "%");
            }
        }
        if (!empty($fdata["gc_id6"])) {
            if ($fdata["gc_id6"] != "") {
                $where["gc_id6"] = array("like", "%" . trim($fdata["gc_id6"]) . "%");
            }
        }
        if (!empty($fdata["gc_id1"])) {
            if ($fdata["gc_id1"] != "") {
                $where["gc_id1"] = array("like", "%" . trim($fdata["gc_id1"]) . "%");
            }
        }
        if (!empty($fdata["num"])) {
            if ($fdata["num"] != "") {
                $where["num"] = array("like", "%" . trim($fdata["num"]) . "%");
            }
        }
        if (!empty($fdata["remark"])) {
            if ($fdata["remark"] != "") {
                $where["remark"] = array("like", "%" . trim($fdata["remark"]) . "%");
            }
        }
        $this->assign("fdata", $fdata);
        $start_time = strtotime($fdata["start_time"]);
        $end_time = strtotime($fdata["end_time"]);
        $xtime = array(array("gt", $start_time), array("lt", $end_time));
        $where["sub_time"] = $xtime;
        $where['status'] = 1;
        $count = $this->db->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 30); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出.
        $list = $this->db->where($where)->order('sub_time DESC')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        if($count > 0 ){
            $get_num = $this->db->where($where)->select();
            foreach($get_num as $get_num_v){
                $getnum_r += $get_num_v['num'];
            }
        }
        $this->assign('allgetnumm',$getnum_r);
        $this->assign('allcount',$count);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->commShow($par);
        $this->goodsClassInfo();
        $this->display();
    }

    /**
     * 添加
     */
    public function add()
    {
        $par = $_REQUEST['par'];
        if (empty($par)) {
            exit('非法操作！');
        }
        $this->assign("par", $par);
        if (IS_POST) {
            $data = readPost($_POST);
            $data['sub_time'] = strtotime($data['sub_time']);
            $data['add_time'] = time();
            $data['add_user'] = $this->cid;
            $res = $this->db->add($data);
            if ($res) {
                $this->VisitOperation('添加[' . $res . ',' . $_REQUEST['name'] . ']', 1);
                $this->resMsg(C("operation_succ_status"), '/index/par/' . $par, 1);
                exit;
            } else {
                $this->VisitOperation('添加[' . $res . ',' . $_REQUEST['name'] . ']', 2);
                $this->resMsg(C('operation_error_status'), '/index/par/' . $par, 1);
                exit;
            }
        }
        $this->goodsClassInfo();
        $this->commShow($par);
        $this->display();
    }

    /**
     * 编辑
     */
    function edit()
    {
        $back = cookie("back");
        //$this->assign("back", $back);
        $par = $_REQUEST['par'];
        if (empty($par)) {
            exit('非法操作！');
        }
        $this->assign("par", $par);
        $id = I("get.id");
        if (IS_POST) {
            $data = readPost($_POST);
            $data["update_time"] = time();
            $data['sub_time'] = strtotime($data['sub_time']);
            $where = array(
                "id" => $id
            );
            $res = $this->db->where($where)->save($data);
            if ($res) {
                $this->VisitOperation('修改[' . $_REQUEST['id'] . ',' . $_REQUEST['name'] . ']', 1);
                $this->resMsg(C("operation_succ_status"), '', 2);
                redirect($back);
                exit;
            } else {
                $this->VisitOperation('修改[' . $_REQUEST['id'] . ',' . $_REQUEST['name'] . ']', 2);
                $this->resMsg(C('operation_error_status'), '', 2);
                redirect($back);
                exit;
            }
        }
        $this->goodsClassInfo();
        $row = $this->db->where(array("id" => $id))->find();
        $this->VisitOperation('查看[' . $_REQUEST['id'] . ',' . $row['name'] . ']', 1);
        $this->assign("list", $row);
        $this->commShow($par);
        $this->display('add');
    }
    public function Eimport(){
        $this->assign('gclist3',D('GoodsClass')->GetGoodsClass(3));
        $this->assign('gclist2',D('GoodsClass')->GetGoodsClass(2));
        $this->display();
    }
    public function import_execlt(){
       // dump($_REQUEST);exit;
        $gc_id3 = I('post.gc_id3');
        $gc_id2 = I('post.gc_id2');
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
            import('Org.Util.excel.PHPExcelReader');
            $excel = new \Spreadsheet_Excel_Reader($filename, true, "UTF-8");
            $sheet = $excel->sheets[0]["cells"];
            $max = 1;
            $num = 0;
            foreach ($sheet as $rows => $rdata) {
                $name = trim($rdata[1]);
                $num = trim($rdata[2]);
                $remark = trim($rdata[3]);
                $sub_time = trim($rdata[4]);
                $gc_id6 = trim($rdata[5]);
                $gc_id1 = trim($rdata[6]);
                if (is_numeric($num)) {
                    $max++;
                    $data['name'] = $name;
                    $data['num'] = $num;
                    $data['remark'] = $remark;
                    $data['add_time'] = time();
                    $data['sub_time'] = $sub_time;
                    $data['gc_id1'] = $gc_id1;
                    $data['gc_id2'] = $gc_id2;
                    $data['gc_id3'] = $gc_id3;
                    $data['gc_id6'] = $gc_id6;
                    $data['add_user'] = $this->cid;
                    $data['par'] = 1;
                   // $res = M("res")->add($data);
                    if ($res) {
                        $num++;
                    }
                    continue;
                }
            }
            dump($data);
            echo '<br>';
            echo '<p style="margin:0 0 0 10px;font-size:14px;color:#2e8965;">读取到'.$max.'条，导入成功'.$num.'条。</p>';

        }
    }
    /**
     * 删除
     */
    public function delete_data()
    {
        $where['id'] = $_REQUEST['id'];
        $del = $_REQUEST['par'];
        switch ($del) {
            case 'index':
                $data['status'] = $_REQUEST['status'];
                $res = $this->db->where($where)->data($data)->save();
                break;
            case 'is_bm':
                $data['is_bm']=$_REQUEST['status'];
                $res = $this->db->where($where)->data($data)->save();
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
        if ($res !== false) {
            $this->VisitOperation('删除[' . $_REQUEST['id'] . ',' . $_REQUEST['status'] . ']', 1);
            $json['result'] = 'success';
        } else {
            $this->VisitOperation('删除[' . $_REQUEST['id'] . ',' . $_REQUEST['status'] . ']', 2);
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
    }
}


















































