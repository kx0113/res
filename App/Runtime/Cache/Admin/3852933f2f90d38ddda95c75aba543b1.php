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
<link href="/res/Public/Admin/hplus/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">

        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><?php if(!empty($list['id'])): ?>编辑-<?php echo ($list["name"]); else: ?>添加<?php endif; ?></h5>
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
                        <a class="close-link" href="javascript:location.reload();">
                            <i class="fa fa-refresh"></i>
                        </a>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>

                <div class="ibox-content">

                    <form class="form-horizontal m-t" method='post' action="" name="form" id="commentForm" enctype="multipart/form-data">
                        <div class="form-group">
                            <input type="hidden" name="id" value="<?php echo ($list["id"]); ?>">
                            <label class="col-sm-3 control-label">名称：</label>
                            <div class="col-sm-5">
                                <input id="name" value="<?php echo ($list["name"]); ?>" name="name" class="form-control" type="text" required aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">公司地点：</label>
                            <div class="col-sm-5">
                                <input id="address"  value="<?php echo ($list["address"]); ?>" name="address" class="form-control" type="text" required aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">所属部门：</label>
                            <div class="col-sm-5">
                                <input id="section" value="<?php echo ($list["section"]); ?>" name="section" class="form-control" type="text" required aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">职位名称：</label>
                            <div class="col-sm-5">
                                <input id="position" value="<?php echo ($list["position"]); ?>" name="position" class="form-control" type="text" required aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">正式入职时间：</label>
                            <div class="col-sm-5">
                                <input id="entry_time" <?php if(!empty($list['entry_time'])): ?>value="<?php echo (date("Y-m-d",$list["entry_time"])); ?>"<?php endif; ?> name="entry_time" class="laydate-icon form-control layer-date " onclick="ldate()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">工资发放时间：</label>
                            <div class="col-sm-5">
                                <input id="payoff_time" value="<?php echo ($list["payoff_time"]); ?>" name="payoff_time" class="form-control" type="text" required aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">试用期工资：</label>
                            <div class="col-sm-5">
                                <input id="salary_a"   value="<?php echo ($list["salary_a"]); ?>"  name="salary_a" class="form-control" type="text" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">转正时间：</label>
                            <div class="col-sm-5">
                                <input id="become_time" <?php if(!empty($list['become_time'])): ?>value="<?php echo (date("Y-m-d",$list["become_time"])); ?>"<?php endif; ?>  name="become_time" class="laydate-icon form-control layer-date " onclick="ldate()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">转正工资：</label>
                            <div class="col-sm-5">
                                <input id="salary_b"   value="<?php echo ($list["salary_b"]); ?>"  name="salary_b" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">保险待遇：</label>
                            <div class="col-sm-5">
                                <input id="salary_c"  value="<?php echo ($list["salary_c"]); ?>"  name="salary_c" class="form-control" type="text">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">离职时间：</label>
                            <div class="col-sm-5">
                                <input id="dimission_time"  <?php if(!empty($list['dimission_time'])): ?>value="<?php echo (date("Y-m-d",$list["dimission_time"])); ?>"<?php endif; ?>  name="dimission_time" class="laydate-icon form-control layer-date " onclick="ldate()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">备注：</label>
                            <div class="col-sm-5">
                                <textarea id="remark" name="remark" class="form-control" rows="5"> <?php echo ($list["remark"]); ?></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-5 col-sm-offset-3">
                                <button class="btn btn-primary" type="submit">提交</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
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


<script src="/res/Public/Admin/hplus/js/plugins/validate/jquery.validate.min.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/validate/messages_zh.min.js"></script>
<script src="/res/Public/Admin/hplus/js/demo/form-validate-demo.min.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/layer/laydate/laydate.js"></script>
<script>
    function ldate(){
        laydate({istime: true, format: 'YYYY-MM-DD'})
    }
</script>
</body>
</html>