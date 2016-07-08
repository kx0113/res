<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="renderer" content="webkit">
<meta http-equiv="Cache-Control" content="no-siteapp" />
<title><?php echo ($index["title"]); ?></title>
<meta name="keywords" content="">
<meta name="description" content="">
<!--[if lt IE 9]>
<meta http-equiv="refresh" content="0;ie.html" />
<![endif]-->

	<script src='/res/Public/Admin/assets/js/jquery-2.0.3.min.js'></script>
	<link rel="shortcut icon" href="favicon.ico">
	<link href="/res/Public/Admin/hplus/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
	<link href="/res/Public/Admin/hplus/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
	<link href="/res/Public/Admin/hplus/css/animate.min.css" rel="stylesheet">
	<link href="/res/Public/Admin/hplus/css/style.min862f.css?v=4.1.0" rel="stylesheet">
	<!-- 表单构建器 -->
	<link href="/res/Public/Admin/hplus/css/plugins/bootstrap-table/bootstrap-table.min.css" rel="stylesheet">
	<link href="/res/Public/css/layout.css" rel="stylesheet">
	<script src='/res/Public/js/common.js'></script>


<script>
	var myDate = new Date();
	var y = myDate.getFullYear();
	var m = myDate.getMonth()+1;
	var d = myDate.getDate();
	var now = y+"-"+m+"-"+d;
	function AjaxSubmit(action, data,re) {
		$.ajax({
			url: "<?php echo u(CONTROLLER_NAME . '/" + action + "');?>",
			type: "POST",
			dataType: "json",
			data: data,
			success: function (res) {
				if (res.result == 'success') {
					alert("操作成功");
					if(re == 1){
						location.reload();
					}
				} else {
					alert("操作失败");
				}
			}
		});
	}
</script>
	<style>*{font-family: '微软雅黑';}
		.fy_li {
			margin:20px 0 0 0;
			padding: 4px;
		}
		.fy_li li {
			padding: 0 5px;
			float: left;
			list-style: none;
			text-align: center;
			margin: 0 4px;
			border: 1px solid #ccc;
		}
		.fy_li .active {
			background: #438eb9;
			color: #fff;
		}
	</style>
	<script src="/res/Public/js/echarts.js"></script>
</head>
<body class="gray-bg">
<script src="/res/Public/js/echarts.js"></script>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-5">

            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>最新公告 </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="">选项1</a>
                            </li>

                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="alert alert-success alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                       <a href="http://echarts.baidu.com/examples.html" target="_blank">百度Echarts</a>
                    </div>
                    <!--<div class="alert alert-info alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <a class="alert-link" href="notifications.html#">了解更多</a>.
                    </div>
                    <div class="alert alert-warning alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <a class="alert-link" href="notifications.html#">了解更多</a>.
                    </div>
                    <div class="alert alert-danger alert-dismissable">
                        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
                        <a class="alert-link" href="notifications.html#">了解更多</a>.
                    </div>-->
                </div>
            </div>
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5> 所属用户组权限
                    </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">选项1</a>
                            </li>
                            <li><a href="#">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <h3>
                        <?php if(($cid == 1) OR ($cid == 2)): ?><span class="simple_tag">所有权限</span>
                            <?php else: ?>
                            <?php if(is_array($menu_r)): $i = 0; $__LIST__ = $menu_r;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menulist): $mod = ($i % 2 );++$i;?><a href="<?php echo u($menulist['funcname']);?>" class="simple_tag" data-id="<?php echo ($menulist["id"]); ?>"><?php echo ($menulist["packagename"]); ?></a><?php endforeach; endif; else: echo "" ;endif; endif; ?>
                    </h3>


                </div>
            </div>


        </div>
        <div class="col-sm-7">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>当前用户信息 </h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="">选项1</a>
                            </li>

                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="widget-body">
                        <div class="widget-main no-padding">
                            <table class="table table-striped table-bordered table-hover">
                                <tbody>
                                <tr>
                                    <td class="">账户</td>
                                    <td class="hidden-480">
                                        <?php echo ($cont["account"]); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="">姓名</td>
                                    <!--<td class="hidden-480">
                                        <?php echo ($cont["nickname"]); ?>
                                    </td>-->
                                    <td class="hidden-480">
                                        <div class="acc_con_2"> <?php echo ($cont["nickname"]); ?>
                                            <a onclick="editInfo(2);" href="#" style="margin:0 0 0 10px;">[修改]</a>
                                        </div>
                                        <div style="display: none;" class="hide_acc_2">
                                            <input type="text" id="nickname" value="<?php echo ($cont["nickname"]); ?>" style="float: left;" />
                                            &nbsp;   <a class="btn btn-primary btn-xs" href="#" onclick="saveAccount(<?php echo ($cont["id"]); ?>,'nickname');">提交</a>
                                            <a  href="#" onclick="editInfo1(2);"  class="  btn-warning btn btn-xs" >取消</a>
                                        </div>
                                    </td>

                                </tr>
                                <tr>
                                    <td class="">密码</td>
                                    <td class="hidden-480">
                                        <div class="acc_con_3">
                                           <a onclick="editInfo(3);" href="#" >[修改]</a>
                                        </div>
                                        <div style="display: none;" class="hide_acc_3">
                                            <input type="text" id="password" value="" style="float: left;" />
                                          &nbsp;  <a  href="#" class="btn btn-primary btn-xs" onclick="saveAccount(<?php echo ($cont["id"]); ?>,'password');">提交</a>
                                            <a  href="#" onclick="editInfo1(3);"
                                                class="  btn-warning btn btn-xs">取消</a>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="">当前ip</td>
                                    <td class="hidden-480">
                                        <?php echo ($dip); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="">注册时间</td>
                                    <td class="hidden-480">
                                        <?php echo (date("Y-m-d H:i:s",$cont["create_time"])); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <td class="">用户组</td>
                                    <td class="hidden-480">
                                        <?php if($cid == 1): ?>不属于用户组<?php else: echo (readGroup($cont["group_id"])); endif; ?>

                                    </td>
                                </tr>

                                <!--<tr>
                                    <td class="">最后一次登录IP</td>
                                    <td class="hidden-480">
                                        <?php echo ($cont["last_login_ip"]); ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="">最后一次登录时间</td>
                                    <td class="hidden-480">
                                        <?php echo (date("Y-m-d H:i:s",$cont["last_login_time"])); ?>
                                    </td>
                                </tr>-->
                                <tr>
                                    <td class="">状态</td>
                                    <td class="hidden-480">
                                        <?php if($cont["status"] == '1' ): ?>正常<?php else: ?>无<?php endif; ?>
                                    </td>

                                </tr>

                                </tbody>
                            </table>


                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
<script>
    function editInfo(id){
        $('.acc_con_'+id).hide();
        $('.hide_acc_'+id).show();
    }
    function editInfo1(id){
        $('.acc_con_'+id).show();
        $('.hide_acc_'+id).hide();
    }
    function saveAccount(id,name){
        var ms = $('#'+name).val();
        if(ms == ''){
            alert('未输入任何字符！');
            return false;
        }
        if(confirm('确定要修改？')){
            if(name == 'password'){
                if(ms.length<6){
                    alert('不能低于6位数！');
                    return false;
                }
            }
            var data = 'id='+id+'&'+ name +'='+ ms +'&par='+name;
            AccountAjax(data);
        }
    }
    function AccountAjax(data){
        $.ajax({
            url:"<?php echo U(CONTROLLER_NAME . '/ajaxSaveInfo');?>", // 表单提交的地址
            type: 'POST',
            dataType: 'json',
            data:data, // 表单提交的数据
            success:function(res){
                if(res.res == 'suc'){
                    alert('操作成功');
                    location.reload();
                }
                if(res.res == 'err'){
                    alert('操作失败，请稍后再试！');
                }
            }
        });
    }
</script>
<script src="/res/Public/Admin/hplus/js/jquery.min.js?v=2.1.4"></script>
<script src="/res/Public/Admin/hplus/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/res/Public/Admin/hplus/js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/layer/layer.min.js"></script>
<script src="/res/Public/Admin/hplus/js/hplus.min.js?v=4.1.0"></script>
<script type="text/javascript" src="/res/Public/Admin/hplus/js/contabs.min.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/pace/pace.min.js"></script>
<!--input-->
<script src="/res/Public/Admin/hplus/js/content.min.js?v=1.0.0"></script>

<script src="/res/Public/Admin/hplus/js/plugins/bootstrap-table/bootstrap-table.min.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/bootstrap-table/bootstrap-table-mobile.min.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/bootstrap-table/locale/bootstrap-table-zh-CN.min.js"></script>
<script src="/res/Public/Admin/hplus/js/demo/bootstrap-table-demo.min.js"></script>


</body>
</html>