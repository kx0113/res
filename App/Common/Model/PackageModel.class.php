<?php

/**
 * 定义命名空间
 */
namespace Common\Model;

/**
 * 引用文件
 */
use Think\Model;


class PackageModel extends Model {

  // 获取所有节点信息
  public function getAllPackage($where = '' , $order = 'packagetype asc,weight desc,id asc') {
    return $this->where($where)->order($order)->select();
  }

  // 获取单个功能信息
  public function getPackage($where = '',$field = '*') {
    return $this->field($field)->where($where)->find();
  }
  
  // 查询子功能
  public function childPackage($id){
    return $this->where(array('pid'=>$id))->select();
  }

  // 删除节点
  public function delPackage($where) {
    if($where){
      return $this->where($where)->delete();
    }
    else {
      return false;
    }
  }
  
  public function getPackageArray($where) {
    $packagedata = $this->where($where)->order('packagetype asc,weight desc,id asc')->select();
    $data = array();
    foreach($packagedata as $val) {
      $data[$val['id']] = $val;
    }
    return $data;
  }

  /**
   * 根据用户权限生成用户菜单
   */
  public function menuPackage($package) {
    $p = unserialize($package);
    $array = array();
    $newarr = array();
    foreach($p as $k => $v)  {
      $array = array_merge($array, $v);
    }
    $ids = implode(',',$array);
    $map['id'] = array('in', $ids);
    $map['type'] = 0;
    $map['status'] = 1;
    $packagedata = $this->getPackageArray($map);
  //  dump($packagedata);
    foreach($packagedata as $a){
      // 一级菜单
      if($a['packagetype'] == 1 && $a['pid'] == 0){
        if(count($newarr[$a['id']]['sub'])) {
          $sub = $newarr[$a['id']]['sub'];
        }
        $newarr[$a['id']] = $a;
        $newarr[$a['id']]['sub'] = $sub;
        unset($sub);
      }
      $funcname = explode('/', $a['funcname']);
      
      // 二级菜单
      if($a['packagetype'] == 2){
        $newarr[$a['pid']]['sub'][$a['id']] = $a;
        $newarr[$a['pid']]['sub'][$a['id']]['funname'] = $funcname[0].'_'.$funcname[1];
        $newarr[$a['pid']]['sub'][$a['id']]['funcname'] = $a['funcname'];
      }
      
      // 三级菜单
      if($a['packagetype'] == 3){
        $p = $packagedata[$a['pid']];
        if ($p['funcname'] != 'Function/index') {
          $newarr[$p['pid']]['sub'][$a['pid']]['sub'][$a['id']] = $a;
          $newarr[$p['pid']]['sub'][$a['pid']]['sub'][$a['id']]['funname'] = $funcname[0].'_'.$funcname[1];
          $newarr[$p['pid']]['sub'][$a['pid']]['sub'][$a['id']]['funcname'] = $a['funcname'];
        }
        else {
          $newarr[$p['pid']]['sub'][$a['pid']]['funname'] .= '_'.$funcname[1];
        }
      }
    }
    return $newarr;
  }
    
  public function getFunctionPackage($package, $enablefunc) {
    $f      = unserialize($package);
    $array  = array();
    $newarr = array();
    foreach($f as $k => $v) {
      $array = array_merge($array, $v);
    }
    $ids          = implode(',',$array);
    $enablearr    = explode(',',$enablefunc);
    $map['id']    = array('in', $ids);
    $map['type']  = 1;
    $packagedata  = $this->where($map)->order('weight desc')->select();
    foreach ($packagedata as $a) {
      if($a['type'] == 1){
        if(in_array($a['id'], $enablearr)){
          $newarr[$a['id']] = $a['funcname'];
        }
      }
      else {
        $newarr[$a['id']] = $a['funcname'];
      }
    }
    return $newarr;
    
  }
  
  public function getSubFunction($package) {
    $f = unserialize($package);
    $array  = array();
    $newarr = array();
    foreach($f as $k => $v)  {
      $array = array_merge($array, $v);
    }
    $ids          = implode(',',$array);
    $map['id']    = array('in', $ids);
    $map['type']  = 1;
    $packagedata  = $this->where($map)->order('weight desc')->select();
    foreach($packagedata as $a){
      $newarr[$a['id']] = $a;
    }
    return $newarr;
  }
  
  /**
   * 递归的更新套餐类型
   */
  public function updatePackagetype($id, $pid) {
    $data = array();
    $package = $this->getPackage('id =' . $pid);
    $data['packagetype'] = $package['packagetype'] + 1;
    $this->where('id=' . $id)->save($data);
    $children = $this->getPackageArray('pid =' . $id);
    if (is_array($children) && !empty($children)) {
      foreach ($children as $cid => $child) {
        $this->updatePackagetype($cid,$id);
      }
    }
    
  }
}