<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2016/2/2
 * Time: 15:05
 */

namespace Common\Model;

/**
 * 引用文件
 */
use Think\Model;

class StatisticsModel extends Model
{
    public $model;
    public function _initialize()
    {
        $this->model = M('Statistics');
    }
    protected $_validate=array(
        //	array('_name','require','请输入职位名称！'),
    );
    //查询列表
    public function GetList($conditon = "",$order="id asc",$field = "*",$size="10"){
        $result = $this->model->where($conditon)->field($field)->order($order)->limit($size)->page($page)->select();
        return $result;
    }
    //单个查找
    public function GetOne($conditon = "",$field = "*"){
        $result = $this->model->where($conditon)->field($field)->find();
        return $result;
    }
    //添加
    public function AddData($data){
        $result = $this->model->data($data)->add();
        return $result;
    }
    //修改
    public function SaveData($where,$data){
        $result = $this->model->where($where)->data($data)->save();
        return $result;
    }

    //删除
    public function DelData($where){
        $result = $this->model->where($where)->delete();
        return $result;
    }
}

?>