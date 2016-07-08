<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends BaseController {
    public function _initialize(){
        parent::_initialize();

    }
    public function tour(){
        $par = $_REQUEST['par'];
        if(!empty($par)){
            echo '1111';

        }
    }

    public function mainWebSocket(){

    }
    public function upload_head(){
        $uid =  $_REQUEST['uid'];
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     99999999 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/admin_head/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            foreach($info as $file){
                $filename = $file['savepath'].$file['savename'];
            }
            $data['head'] = $filename;
            $where['id'] = $this->cid;
            $resd = M('admin')->where($where)->data($data)->save();
            if($resd){
                $this->resMsg('操作成功','/content',1);
            }else{
                $this->error('操作失败，请稍后重试！');
            }

        }
    }
    public function index(){
        $this->display();
    }
    public function ajaxSaveInfo(){
        $action = I("post.par");
        $where['id'] = $_REQUEST['id'];
        switch ($action) {
            case "nickname":
                $this->VisitOperation('修改用户姓名['.$_REQUEST['id'].','.$_REQUEST['nickname'].']',1);
                $data['nickname'] = $_REQUEST['nickname'];
                break;
            case "password":
                $this->VisitOperation('修改用户密码['.$_REQUEST['id'].','.md5($_REQUEST['password']).']',1);
                $password = $_REQUEST['password'];
                $data['password'] = substr(md5($password), 5, 20);
                break;
            default:
                break;
        }
        $res = M("admin")->where($where)->data($data)->save();
        if ($res) {
            $json['res'] = 'suc';
        } else {
            $json['res'] = 'err';
        }
        $this->ajaxReturn($json);
    }
    public function e2(){
        $this->error('失败！');
    }
    public function e1(){
        $this->success('成功！');
    }
    public function password(){
       // $res = M('password')->select();
        dump($res);
    }
    public function content(){
        $this->VisitOperation('平台首页');
        $res = M('admin')->where(array('id'=>$this->cid))->find();
        $this->assign('cont',$res);
        $neiqin_group = M("neiqin_group")->where(array('id'=>$res['group_id']))->find();
        $nav = unserialize($neiqin_group['grouppower']);
        if(is_array($nav)){
            foreach($nav as $k=>$v){
                $menu_id[] = $v;
            }
            $menu_r = M('package')->where(array('id'=>array('in',$menu_id),'status'=>1))->order("weight DESC")->select();
            $this->assign("menu_r",$menu_r);
        }
       // dump($menu_r);
        $this->assign('dip',$this->get_client_ip());
        $this->assign("cid",$this->cid);
        $this->display();
    }
    public function downFile(){
        $this->VisitOperation('下载GoogleChrome.zip');
        $file_name = "GoogleChrome.zip";     //下载文件名
        $this->DownloadFile($file_name);
    }
}