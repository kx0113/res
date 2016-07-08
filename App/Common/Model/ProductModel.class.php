<?php

/**
 *  author:Allen
 */

/**
 * 定义命名空间
 */
namespace Common\Model;

/**
 * 引用文件
 */
use Think\Model;

class ProductModel extends Model {
	public function _initialize(){
        $this->model = M('product');
    }
    
    protected $_validate=array(
		array('name','require','产品名称必须填写!'),
	    array('editor_notice','require','产品内容必须填写!'),
	//	array('acc_id','','用户名已经存在！',0,'unique',1),
	///	array('mobile','/((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/','请填写正确的电话号码！',0,'regex',1),
		);
    
	//查询列表
	public function pro_getlist($conditon = "",$field = "*",$size="10"){
		$result = $this->model->where($conditon)->field($field)->order()->limit($size)->page($page)->select();
		return $result;
	}
	//单个查找
	public function pro_getone($conditon = "",$field = "*"){
		$result = $this->model->where($conditon)->field($field)->find();
		return $result;
	}
	
	
	//添加
	public function pro_add($data=array()){
		$result = $this->model->data($data)->add();
		return $result;
	}
	//修改
	public function pro_save($where=array(),$data=array()){
		$result = $this->model->where($where)->data($data)->save();
		return $result;
	}
	
	//删除
	public function pro_del($where){
		$result = $this->model->where($where)->delete();
		return $result;
	}
}