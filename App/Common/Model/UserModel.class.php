<?php

/**
 * author Allen
 */

/**
 * 定义命名空间
 */
namespace Common\Model;

/**
 * 引用文件
 */
use Think\Model;

class UserModel extends Model {
	public function _initialize(){
		$this->model = M('User');
	}
	protected $_validate = array(
	//	array('PositionName','require','职位名称为必填字段！'),
		array('name','require','姓名为必填字段！'),
		//array('Sex',array(1,0),'不合法的值！',2,'in'), // 当值不为空的时候判断是否在一个范围内
		array('mobile','require','请输入手机号！'),
	    //array('email','email','email格式错误'),  //email格式
	/*	array('mobile','require','请输入手机号!',1),
		array('mobile','require','请输入邮箱!',1),*/
	 //   array('truename','require','真实姓名必须填写!',1),
	);
	//查询列表
	public function Getlist($conditon = "",$field = "*",$order="",$size="10"){
		$result = $this->model->where($conditon)->field($field)->order($order)->limit($size)->page($page)->select();
		return $result;
	}
	//单个查找
	public function Getone($conditon,$field = "*"){
		$result = $this->model->where($conditon)->field($field)->find();
		return $result;
	}
	//添加
	public function Error($msgTitle,$message,$status,$waitSecond,$jumpUrl){
		
	}
	public function Adduser($data){
		$result = $this->model->data($data)->add();
		return $result;
	}
	//修改
	public function Saveuser($where,$data){
		$result = $this->model->where($where)->data($data)->save();
		return $result;
	}


}