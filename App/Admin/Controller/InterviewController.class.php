<?php
namespace Admin\Controller;
use Common\ORG\Util\Page;
class InterviewController extends BaseController {
    public $model;
    public function _initialize(){
        parent::_initialize();
        //模型初始化
        $this->model = D('Interview');
    }
    //首页
    public function index(){
        if(empty($_REQUEST['class'])) exit;
        $where['interview_status'] = 1; //状态为1
        $where['class'] = $_REQUEST['class'];  //是面试通过人员
        switch ( $_REQUEST['class']){
            case 1:
                $this->VisitOperation('面试通过人员');
                break;
            case 2:
                $this->VisitOperation('到岗人员');
                break;
            default:
                $this->VisitOperation('非法操作');
        }
        $fdata = readPost($_REQUEST);
        if (!empty($fdata["name"])) {
            if ($fdata["name"] != "") {
                $where["interview_name"] = array("like", "%" . trim($fdata["name"]) . "%");
            }
        }
        $this->assign("fdata", $fdata);
        $count = $this->model->where($where)->count();
        if($name){
            if($count == 0){
                $this->assign('not_found',2);
            }
        }else{
            if($count == 0){
                $this->assign('not_found',1);
            }
        }
        $page = new Page($count,10);
        $res = $this->model->where($where)
            ->order('interview_id DESC')
            ->limit($page->firstRow.','.$page->listRows)
            ->select();
        //视图展示数据集
       // dump($res);
        $this->assign('page', $page->show());
        $this->assign('getclass',$_REQUEST['class']);
         $this->assign('list',$res);
        $this->display();
    }
    //删除
    public function delete_data(){
        if(empty($_REQUEST['class'])) exit;
        $list = $_REQUEST['list'];
        if($list){
            $var=explode(",",$list);
             foreach ($var as $k=>$v){
                 $del_id[] = $v;
             }
            $where['interview_id'] = array('in',$del_id);
        }else{
            $where['interview_id'] = $_REQUEST['interview_id'];
        }
        $where['class'] = $_REQUEST['class'];
        $data['interview_status'] = $_REQUEST['interview_status'];
        $res = $this->model->SaveData($where,$data);
        if($res){
            $this->VisitOperation('删除['.$_REQUEST['interview_id'].','.$_REQUEST['interview_status'].']',1);
            $json['result'] = 'success';
        }
        else{
            $this->VisitOperation('删除['.$_REQUEST['interview_id'].','.$_REQUEST['interview_status'].']',2);
            $json['result'] = 'error';
        }
        $this->ajaxReturn($json);
     }

    //修改
    public function editData() {
        if(empty($_REQUEST['class'])) exit;
        if(empty($_REQUEST['interview_id'])) exit;
        if($_POST){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','docx','doc');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/interview/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
                // $this->error($upload->getError());
            }else{// 上传成功
                if($_REQUEST['delpath'] == '1'){
                    $data['img'] = $info['file']['savepath'].$info['file']['savename'];
                }
                //$data['doc'] = $info['filer']['savepath'].$info['filer']['savename'];
            }
            $where['interview_id'] = I('interview_id');
            $data['interview_name'] =  trim($_REQUEST['interview_name']);//姓名
            $data['station_id'] = intval($_REQUEST['station_id']); //申请职位
            $data['interview_tel'] = trim($_REQUEST['interview_tel']);//联系电话
            $data['interview_entry_time'] = strtotime($_REQUEST['interview_entry_time']); //入职时间
            $data['lastmodifytime'] = time(); //最后编辑时间
            $data['interview_describe'] = trim($_REQUEST['interview_describe']);//备注
            $data['interview_salary'] = intval($_REQUEST['interview_salary']); //待遇
            if($this->model->create($data)){
                if($this->model->SaveData($where,$data)){
                    $this->VisitOperation('修改['.$_REQUEST['interview_id'].','.$_REQUEST['interview_name'].']',1);
                    $this->resMsg(C("operation_succ_status"),'/index?class='.$_REQUEST['class'],1);
                }else{
                    $this->VisitOperation('修改['.$_REQUEST['interview_id'].','.$_REQUEST['interview_name'].']',2);
                    $this->resMsg(C("operation_error_status"),'/index?class='.$_REQUEST['class'],1);
                }
            }
            else{
                $error = $this->model->getError();
                $this->resMsg($error,'',2);
            }
        }
        if(!empty($_REQUEST['par'])){
            $this->assign('par',$_REQUEST['par']);
        }
        $where['interview_id'] = $_REQUEST['interview_id'];
        $res = $this->model->GetOne($where);
        //前台抛出职位列表
        $this->assign('station_list',$this->GetStation());
        $this->assign('getclass',$_REQUEST['class']);

        $this->assign('list',$res);
        $this->display('add');
    }
    //修改状态
    public function show_change(){  
         $res = $this->model
         ->where(array('id' => $_REQUEST['id']))
         ->save(array('status'=>$_REQUEST['status']));
    }
    //添加
    public function add(){
        if(empty($_REQUEST['class'])) exit;
        if($_POST){
            $upload = new \Think\Upload();// 实例化上传类
            $upload->maxSize   =     3145728 ;// 设置附件上传大小
            $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg','docx','doc');// 设置附件上传类型
            $upload->rootPath  =     './Uploads/interview/'; // 设置附件上传根目录
            $upload->savePath  =     ''; // 设置附件上传（子）目录
            // 上传文件
            $info   =   $upload->upload();
            if(!$info) {// 上传错误提示错误信息
               // $this->error($upload->getError());
            }else{// 上传成功
                $data['img'] = $info['file']['savepath'].$info['file']['savename'];
               // $data['doc'] = $info['filer']['savepath'].$info['filer']['savename'];
            }
            $data['interview_name'] =  trim($_REQUEST['interview_name']);//姓名
            $data['station_id'] = intval($_REQUEST['station_id']); //申请职位
            $data['interview_tel'] = trim($_REQUEST['interview_tel']);//联系电话
            $data['interview_entry_time'] = strtotime($_REQUEST['interview_entry_time']); //入职时间
            $data['interview_add_time'] = time();//写入时间
            $data['class'] = $_REQUEST['class'];//分类
            $data['interview_describe'] = trim($_REQUEST['interview_describe']);//备注
            $data['interview_salary'] = intval($_REQUEST['interview_salary']); //待遇
            if($this->model->create($data)){
                $addinterview = $this->model->AddData($data);
                if($addinterview){
                    $this->VisitOperation('添加['.$addinterview.','.$_REQUEST['interview_name'].']',1);
                    $this->resMsg(C("operation_succ_status"),'/index?class='.$_REQUEST['class'],1);
                }else{
                    $this->VisitOperation('添加['.$addinterview.','.$_REQUEST['interview_name'].']',2);
                    $this->resMsg(C("operation_error_status"),'/add?class='.$_REQUEST['class'],1);
                }
            }
            else{
                $error = $this->model->getError();
                $this->resMsg($error,'',2);
            }
        }
        //前台抛出职位列表
        $this->assign('getclass',$_REQUEST['class']);
        $this->assign('station_list',$this->GetStation());
        $this->display();
    }
    /**
     * 获取职位列表
     */
    public function GetStation(){
        $station_where['station_status'] = 1;
        $station_order = 'station_sort DESC';
        $station_field = 'station_id,station_name';
        $station_list = D('Station')->where($station_where)->order($station_order)->field($station_field)->select();
        return $station_list;
    }

}