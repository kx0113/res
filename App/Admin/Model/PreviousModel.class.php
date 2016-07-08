<?php

/**
 * author allen
 */

/**
 * 定义命名空间
 */
namespace Common\Model;

/**
 * 引用文件
 */
use Think\Model;

class PreviousModel extends Model {
	public function _initialize(){
        $this->model = M('previous');
    }
    public $_validate = array(
    		array('name','require','请输入名称',1),//必须验证name
    	//	array('mobile','require','手机号必须填写!'),
    		//array('truename','require','真实姓名必须填写!',1),
    );
}
