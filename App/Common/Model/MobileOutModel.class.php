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

class MobileOutModel extends Model
{
    public function _initialize()
    {
        $this->model = M('mobile_out');
    }
    protected $_validate=array(
        array('gcp_id','require','物品分类必填!'),
        array('gcs_id','require','手机型号id必填!'),
        array('m_id','require','物品id必填!'),
        array('get_num','require','领取数量必填!'),
        array('get_person','require','使用人必填!'),
    );


}

?>