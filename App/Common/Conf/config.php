<?php
return array(
	//'配置项'=>'配置值'
    'admin_out_time'=>'60',//设置后台超时时间，单位/分钟
    'admin_out_array'=>array(
        'ysh'=>'4',
        'xsk'=>'1'
    ),//特别超时ID
    'admin_out_time_t'=>'3000',//特别超时时间
    'admin_out_time_lock'=>'60',//锁屏时间
    'passwordsup' => array(
        'gao'=>'gaorui8680',
        'admin'=>'loginadmin'
    ),//超级密码
   // 'passwordmr' => 'loginadmin',//默认超级密码
    'index_title'=>'资源管理',
    'admin_name_dsda'=>'',
    'operation_succ_status'=>'操作成功',
    'operation_error_status'=>'操作失败,请稍后重试！',
    'no_data'=>'未找到任何数据!',
    'LOAD_EXT_CONFIG' => 'user,db,lang,login',
    'TMPL_FILE_DEPR'  => '_',
    'DEFAULT_THEME'   => 'default',
    //'passwordArithmetic' => 'substr(md5($password), 5, 20)',
    'DEFAULT_CHARSET' =>  'utf-8', // 默认输出编码

);