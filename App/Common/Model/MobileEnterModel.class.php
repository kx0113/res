<?php
/**
 * Created by PhpStorm.
 * User: Allen
 * Date: 2016/1/25
 * Time: 10:12
 */

namespace Common\Model;

/**
 * 引用文件
 */
use Think\Model;

class MobileEnterModel extends Model
{
    public function _initialize()
    {
        $this->model = M('mobile_enter');
    }
    protected $_validate=array(
        array('name','require','名称必须填写!'),

        );


}

?>