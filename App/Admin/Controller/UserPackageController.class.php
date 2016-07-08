<?php

/**
 * 声明命名空间
 */
namespace Admin\Controller;

/**
 * 引入文件
 */
use Common\Controller\AdminController;
use Common\Model\Node;
use Common\Model\Role;
use Common\ORG\Util\Page;
use Common\ORG\Util\Image;
use Common\ORG\Util\RBAC;
use Common\ORG\Util\IpLocation;
use Common\ORG\Util\Tree;
use Common\ORG\Util\Geo;

/**
 * 网站后台
 */
class UserPackageController extends BaseController {
  
  public function _initialize() {
    parent::_initialize();  //RBAC 验证接口初始化
  }
  
  /**
   *
   */
  /*<td align='center'>
  <input name='listorders[$id]' type='text' size='3' value='$listorder' class='input-text-c'>
  </td>*/
  public function index(){
    $package = D('Package')->getAllPackage();
    // 构建生成树中所需的数据
    foreach($package as $k => $r) {
        $package[$k]['type'] = $r['type'] == 0 ? '<font style="color:#1c84c6">菜单</font>' : '<font style="color:#ed5565">[功能]</font>';
      $package[$k]['statused'] = $r['status'] == 1 ? '<a href="'.U('UserPackage/statusChange',array('id'=>$r['id'],'status'=>0)).'"><font color="#18a689">关闭</font></a>' :'<a href="'.U('UserPackage/statusChange',array('id'=>$r['id'],'status'=>1)).'"><font color="#1872ab">[开启]</font></a>';
      $package[$k]['submenu'] = $r['packagetype'] == 3 ? '<font color="#cccccc">添加子菜单</font>' : '<a href="'.U('UserPackage/add',array('pid'=>$r['id'])).'">添加子菜单</a>';
      $package[$k]['edit'] = $r['packagetype'] == 0 ? '<font color="#cccccc">修改</font>' : "<a href='".U('UserPackage/edit',array('id'=>$r['id'],'pid'=>$r['pid']))."'>修改</a>";
      $package[$k]['del'] = $r['packagetype'] == 0 ? '<font color="#cccccc">删除</font>' : "<a onClick='return confirmurl(\"".U('UserPackage/del',array('id'=>$r['id']))."\",\"确定删除该菜单吗?\")' href='javascript:void(0)'>删除</a>";
      $package[$k]['weight'] ='<input name="weight['.$r['id'].']" type="text"value="'.$r['weight'].'" class="packagewidth" />';
      $package[$k]['checkbox'] ='<input name="checkbox['.$r['id'].']" type="checkbox" value="'.$r['id'].'" />';
    }
    $str  = "<tr class='tr'>
          <!--  <td align='center'><input type='checkbox' name='id[]' value='\$id' /></td>-->
          <td align='center'>\$checkbox</td>
           <td align='center'>\$weight</td>
            <td align='center'>\$id</td>
            <td style='text-align:left'>\$spacer \$packagename(\$funcname)</td>
            <td align='center'>\$type</td>
            <td align='center'>\$statused</td> 
            <td align='center'>
            \$submenu | \$edit | \$del
          </td>
          </tr>";
    
    $Tree = new Tree();
    $Tree->icon = array('&nbsp;&nbsp;&nbsp;│ ','&nbsp;&nbsp;&nbsp;├─ ','&nbsp;&nbsp;&nbsp;└─ ');
    $Tree->nbsp = '&nbsp;&nbsp;&nbsp;';
    $Tree->init($package);
    $html_tree = $Tree->get_tree(0, $str);
    $this->assign('html_tree',$html_tree);
    $this->display();
  }
  /*
    *菜单排序
    */
  public function listorder()
  {
    $listorders = $_POST['weight'];
   // dump($_POST);exit;
    if($_POST['sort'] == 'sort'){
        foreach ($listorders as $id => $value) {
          D('Package')->where(array('id' => $id))->data(array('weight' => $value))->save();
        }
    }
    $this->resMsg(C("operation_succ_status"),'/index',1);
  }
  public function add(){
    if($_POST){
      if(empty($_POST['packagename'])){
        $this->error('操作失败,请输入功能名称!');
      }
      $PackageDB = M("Package");
      if($_POST['pid'] != 0)
      {
        $package = D('Package')->getPackage('id ='.$_POST['pid']);
        $_POST['packagetype'] = $package['packagetype'] + 1;
        
      } else {
        $_POST['packagetype'] = 1;
      }
      //根据表单提交的POST数据创建数据对象
      if($PackageDB->create()){
        if($PackageDB->add()){
          $this->resMsg(C('operation_succ_status'),'/index',1);
        }else{
           $this->error('操作失败!');
        }
      }else{
        $this->error($PackageDB->getError());
      }
      
    }else{
      $Package  = D('Package')->getAllPackage();
     // $Function = D('Function')->getAllFunction();
      $pid      = I('get.pid',0,'intval');
      $array    = array();
      foreach ($Package as $k => $r) {
        $array[$r['id']] = $r;
      }
      $str  = "<option value='\$id' \$selected \$disabled >\$spacer \$packagename</option>";
      $Tree = new Tree();
      $Tree->init($array);
      $select_categorys = $Tree->get_tree(0, $str, $pid);
      $this->assign('tpltitle','添加');
    //  $this->assign('select_function',$Function);
      $this->assign('select_categorys',$select_categorys);
      $this->display();
    }

  }

  //编辑菜单
  public function edit(){
    $PackageDB = D('Package');
    if(IS_POST) {
      $data = readPost($_REQUEST);
      // dump($data);exit;
      //根据表单提交的POST数据创建数据对象
      if($data['pid'] != 0) {
        D('Package')->updatePackagetype($data['id'],$data['pid']);
        $package = D('Package')->getPackage('id ='.$data['pid']);
        $data['packagetype'] = $package['packagetype'] + 1;
      } else {
        $data['packagetype'] = 1;
      }
      $where['id'] = $data['id'];
      if($PackageDB->create()){
        if($PackageDB->where($where)->data($data)->save()){
          $this->assign("jumpUrl",U('UserPackage/index'));
          $this->success('编辑成功！');
        } else {
          $this->error('编辑失败!');
        }
      }else{
        $this->error($PackageDB->getError());
      }
    }
    else {
      $id  = I('get.id',0,'intval');
      $pid = I('get.pid',0,'intval');
      if(!$id)$this->error('参数错误!');
      $allPackage = $PackageDB->getAllPackage();
     // $Function = D('Function')->getAllFunction();
      $array = array();
      foreach($allPackage as $k => $r) {
        $r['id']          = $r['id'];
        $r['packagename'] = $r['packagename'];
        $array[$r['id']]  = $r;
      }
      $str  = "<option value='\$id' \$selected \$disabled >\$spacer \$packagename</option>";
      $Tree = new Tree();
      $Tree->init($array);
      $select_categorys = $Tree->get_tree(0, $str, $pid);
      $this->assign('tpltitle','编辑');
      $this->assign('select_categorys',$select_categorys);
      $this->assign('select_function',$Function);
      $this->assign('info', $PackageDB->getPackage('id='.$id));
    //  dump($PackageDB->getPackage('id='.$id));
      $this->display('add');
    }
  }
  
  //删除菜单
  public function del(){
    $id = I('get.id',0,'intval');
    if(!$id)$this->error('参数错误!');
    $PackageDB = D('Package');
    $info = $PackageDB->getPackage(array('id'=>$id),'id');
    if($PackageDB->childPackage($info['id'])){
      $this->error('存在子菜单，不可删除!');
    }
    if($PackageDB->delPackage('id='.$id)){
      $this->assign("jumpUrl",U('UserPackage/index'));
      $this->success('删除成功！');
    }else{
      $this->error('删除失败!');
    }
  }
  
  
  public function statusChange(){
    $PackageDB = M('Package');
    $where['id'] = I('get.id');
    $data['status'] = I('get.status');
    if($PackageDB->where($where)->save($data)){
      $this->success('状态更改成功！');
    } else {
      $this->error('状态更改失败!');
    }
  }

    // 批量删除菜单
    public function batchDelete(){
        $model = M('Package');
        $ids = I('post.id');
        if (!empty($ids)) {
            if ($model->where(array('token'=>$this->token,'id'=>array('in', $ids)))->delete() !== false) {
                $this->success('批量删除成功!',U(CONTROLLER_NAME.'/index',array('token' => $this->token)));
            } else {
                $this->error('服务器繁忙,请稍后再试!',U(CONTROLLER_NAME.'/index',array('token' => $this->token)));
            }
        } else {
            $this->error('未选中删除项!',U(CONTROLLER_NAME.'/index',array('token' => $this->token)));
        }
    }
}