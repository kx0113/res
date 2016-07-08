<?php

/**
 * author yxf
 */

/**
 * 定义命名空间
 */
namespace Common\Model;

/**
 * 引用文件
 */
use Think\Model;

class AdminModel extends Model {
	
	//查询列表
	public function Getlist($conditon = "",$field = "*",$size="10"){
		$result = $this->where($conditon)->field($field)->order()->limit($size)->page($page)->select();
		return $result;
	}
	//单个查找
	public function Getone($conditon,$field = "*"){
		$result = $this->where($conditon)->field($field)->find();
		return $result;
	}
	
	
	//添加
	public function Adduser($data){
		$result = $this->data($data)->add();
		if( $result ){
			return 1;
		}else{
			return 2;
		}
	}
	//修改
	public function Saveuser($where,$data){
		$result = $this->where($where)->data($data)->save();
		if( $result ){
			return 1;
		}else{
			return 2;
		}
	}
	
	//删除
	public function user_del($where){
		$result = $this->where($where)->delete();
		return $result;
	}
}