<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2016/1/27
 * Time: 13:08
 */
namespace Common\Model;

/**
 * 引用文件
 */
use Think\Model;

class CompanyClassModel extends Model
{
    public function _initialize()
    {
        $this->model = M('company_class');
    }

    protected $_validate = array(
        array('name', 'require', '请填写名称!'),
        ///	array('mobile','require','手机号必须填写!'),
        //	array('acc_id','','用户名已经存在！',0,'unique',1),
        ///	array('mobile','/((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)/','请填写正确的电话号码！',0,'regex',1),
    );
    public function db(){
        return $this->model = M('company_class');
    }
    //查询列表
    public function getlist($conditon = "",$field = "*",$size="10"){
        $result = $this->model->where($conditon)->field($field)->order()->limit($size)->page($page)->select();
        return $result;
    }
    //单个查找
    public function getone($conditon = "",$field = "*"){
        $result = $this->model->where($conditon)->field($field)->find();
        return $result;
    }


    //添加
    public function pc_add($data=array()){
        $result = $this->model->data($data)->add();
        return $result;
    }
    //修改
    public function pc_save($where=array(),$data=array()){
        $result = $this->model->where($where)->data($data)->save();
        return $result;
    }

    //删除
    public function pc_del($where){
        $result = $this->model->where($where)->delete();
        return $result;
    }


}

?>