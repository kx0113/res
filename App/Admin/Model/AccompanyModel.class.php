<?php

/**
 * auth  by  yxf
 */

/**
 * 定义命名空间
 */
namespace Common\Model;

/**
 * 引用文件
 */
use Think\Model;

class AccompanyModel extends Model {
	public function _initialize(){
        $this->model = M('accompany');
    }
    
    protected $_validate=array(
		array('acc_id','require','用户名必须填写!'),
		array('mobile','require','手机号必须填写!'),
		array('acc_id','','用户名已经存在！',0,'unique',1),
		array('mobile','/((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/','请填写正确的电话号码！',0,'regex',1),
		); 
    
	//查询陪诊人员列表
	public function Getlist($conditon = "",$field = "*",$size="10"){
		$result = $this->model->where($conditon)->field($field)->order()->limit($size)->page($page)->select();
		return $result;
	}
	//单个查找
	public function Getone($conditon = "",$field = "*"){
		$result = $this->model->where($conditon)->field($field)->find();
		return $result;
	}
	
	
	//添加陪诊人员
	public function Adduser($data){
		$result = $this->model->data($data)->add();
		if( $result ){
			return 1;
		}else{
			return 2;
		}
	}
	//陪诊人员修改
	public function Saveuser($data){
		$result = $this->model->where($where)->data($data)->save();
		if( $result ){
			return 1;
		}else{
			return 2;
		}
	}
	
	//陪诊人员删除
	public function user_del($where){
		$result = $this->model->where($where)->delete();
		return $result;
	}
}