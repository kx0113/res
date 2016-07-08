<?php if (!defined('THINK_PATH')) exit();?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<title><?php echo ($index["title"]); ?></title>
	<meta name="description" content="overview &amp; stats" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link href="/res/Public/Admin/assets/css/bootstrap.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/font-awesome.min.css" />
	<!--[if IE 7]>
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/font-awesome-ie7.min.css" />
	<![endif]-->
	<script src="/res/Public/Admin/assets/js/xenon-api.js"></script>
	<!-- page specific plugin styles -->
	<!-- fonts -->
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/ace-fonts.css" />
	<!-- ace styles -->
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/ace.min.css" />
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/ace-rtl.min.css" />
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/ace-skins.min.css" />
	<!--[if lte IE 8]>
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/ace-ie.min.css" />
	<![endif]-->
	<!-- inline styles related to this page -->
	<!-- ace settings handler -->
	<script src="/res/Public/Admin/assets/js/ace-extra.min.js"></script>
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
	<script src="/res/Public/Admin/assets/js/html5shiv.js"></script>
	<script src="/res/Public/Admin/assets/js/respond.min.js"></script>
	<![endif]-->
	<style>*{font-family: '微软雅黑';}</style>
	<!-- ace settings handler -->
	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
 		<!-- jq库 -->
		<script src='/res/Public/Admin/assets/js/jquery-2.0.3.min.js'></script>
		<!-- bootstrap -->
		<!-- 时间选择器  -->
	<link href="/res/Public/Components/bootstrap-datetimepicker-master/sample in bootstrap v3/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen">
   		 <link href="/res/Public/Components/bootstrap-datetimepicker-master/css/bootstrap-datetimepicker.min.css" rel="stylesheet" media="screen">
		<script type="text/javascript" src="/res/Public/Components/bootstrap-datetimepicker-master/js/bootstrap-datetimepicker.js" charset="UTF-8"></script>
		<script type="text/javascript" src="/res/Public/Components/bootstrap-datetimepicker-master/js/locales/bootstrap-datetimepicker.zh-CN.js" charset="UTF-8"></script>
		 <!-- jqgrid -->

		<!-- 通知层 -->
		 <link href="/res/Public/Components/toastr/toastr.css" rel="stylesheet" />
		 <script src='/res/Public/Components/toastr/toastr.js'></script>
		  <!-- awesome 字体库 -->
 		<!-- 表单验证 -->
		<link rel="stylesheet" href="/res/Public/Components/jquery-validation-1.14.0/demo/css/screen.css">
		<script src="/res/Public/Components/jquery-validation-1.14.0/dist/jquery.validate.js"></script>
		<!-- ace styles -->
		<!-- 图片上传预览 -->
		<!--<script type="text/javascript" src="/res/Public/js/uploadPreview.min.js"></script>-->
		<!-- 后台样式 -->
 		<!-- ace settings handler -->
	<!--页面预加载 -->

	<script src="/res/Public/Admin/assets/js/xenon-custom.js"></script>
	<!--顶部加载-->
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/xenon-core.css">
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/xenon-components.css">
	<script src="/res/Public/Components/layer-v2.3/layer.js"></script>
<!--	<script src="/res/Public/Admin/assets/js/chosen.jquery.min.js"></script>

		<script src="/res/Public/Admin/assets/js/fuelux/fuelux.spinner.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jquery.knob.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jquery.autosize.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jquery.inputlimiter.1.3.1.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jquery.maskedinput.min.js"></script>
		<script src="/res/Public/Admin/assets/js/bootstrap-tag.min.js"></script>
		&lt;!&ndash; 公共js  <link rel="stylesheet" href=on.js" /> &ndash;&gt;
	<link rel="stylesheet" href="/res/Public/Admin/assets/css/xenon-core.css">
	<script src="/res/Public/Admin/assets/js/TweenMax.min.js"></script>
	<script src="/res/Public/Admin/assets/js/xenon-api.js"></script>
	<script src="/res/Public/Admin/assets/js/xenon-custom.js"></script>-->
			<!-- 自定义公共css  -->
	<link href="/res/Public/css/layout.css" rel="stylesheet">
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<script type="text/javascript" charset="utf-8" src="/res/Public/Components/ueditor1_4_3-utf8-php/ueditor.config.js"></script>
		<script type="text/javascript" charset="utf-8" src="/res/Public/Components/ueditor1_4_3-utf8-php/ueditor.all.min.js"> </script>
		<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
		<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
		<script type="text/javascript" charset="utf-8" src="/res/Public/Components/ueditor1_4_3-utf8-php/lang/zh-cn/zh-cn.js"></script>
		<!-- //公共js -->
		<script src="/res/Public/js/common.js"></script>
		<!-- 通知层前台js -->

		<script type="text/javascript">

			//Ajax 提交
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
		<!--[if lt IE 9]>

		<![endif]-->
	<style>
		.main-content {
			margin-left: 0;
			margin-right: 0;
			margin-top: 0;
			min-height: 100%;
			padding: 0;
		}
		.input-style input{border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;
			background:#f3f3f3;float:left;}
		.input-style label{float:left;}
		.nbname_style{margin:4px 14px 0 10px;}
		.nbname_style1{margin:6px 0px 0 10px;}
		#sample-table-1  tr td{text-align: center;}

			.widget-toolbar>.nav-tabs {
				border-bottom: 0;
				margin-bottom: 0;
				position: relative;
				top: 3px;
			}

			*{font-family:'微软雅黑';}
			.page-content {
				background: #fff;
				margin: 0;
				padding: 0 20px 0 24px;
			}

.
</style>
	</head>
<body>

<div class="main-container" id="main-container">
    <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
    </script>

    <div class="main-container-inner">




        <div class="main-content">
            <script type="text/javascript">

            </script>
            <style>
                .widget-toolbar {
                    display: inline-block;
                    padding: 0 10px;
                    line-height: 37px;
                    float: none;
                    position: relative;
                }
            </style>
            <div class="page-content">
                <div class="row">

                    <div class="col-xs-12">

                        <div class="widget-box transparent" id="recent-box">
                            <div class="widget-header">


                                <div class="widget-toolbar no-border">
                                    <ul class="nav nav-tabs" id="recent-tab">
                                        <?php if($row["id"] == '' ): ?><li class="active">
                                                <a href="<?php echo U(CONTROLLER_NAME . '/yj');?>">意见反馈</a>
                                            </li>
                                            <?php else: ?>
                                            <?php if($status == 'true' ): ?><li class="active">
                                                    <a href="#">[<?php echo ($row['qqcode']); ?>]-查看</a>
                                                </li>
                                                <?php else: ?>
                                                <li class="active">
                                                    <a href="#">[<?php echo ($row['qqcode']); ?>]-编辑</a>
                                                </li><?php endif; endif; ?>


                                    </ul>
                                </div>
                            </div>

                        </div>
                        <br>
                        <form class="form-horizontal" role="form"  id="form1" enctype="multipart/form-data" action="" method="post">

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">模块</label>
                                <input type="hidden" name="id" id="id" value="<?php echo ($row["id"]); ?>" />
                                <div class="col-sm-9">
                                    <input type="text" name="model" id="model" class="col-xs-10 col-sm-5"  value="<?php echo ($row['model']); ?>" />
                                  <br>  <p style="color:#999;">比如（手机卡登记->添加新卡）</p>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">标题</label>
                                <input type="hidden" name="id" id="id" value="<?php echo ($row["id"]); ?>" />
                                <div class="col-sm-9">
                                    <input type="text" name="title" id="title" class="col-xs-10 col-sm-5"  value="<?php echo ($row['title']); ?>" />
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">内容</label>
                                <div class="col-sm-9">
                                    <textarea class=" col-xs-10 col-sm-5" name='content' id='content' rows="5"><?php echo ($row['content']); ?></textarea>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">所属部门</label>
                                <div class="col-sm-9">
                                    <input type="text" name="station" id="station" class="col-xs-10 col-sm-5"  value="<?php echo ($row['station']); ?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label no-padding-right" for="form-field-1">提交人</label>
                                <div class="col-sm-9">
                                    <input type="text" name="add_user" id="add_user" class="col-xs-10 col-sm-5"  value="<?php echo ($row['add_user']); ?>" />
                                </div>
                            </div>






                                <div class="clearfix form-actions">
                                    <div class="col-md-offset-3 col-md-9">
                                        <?php if($row["id"] == '' ): ?><a class="sub_f btn btn-info" href="javascript:$('#form1').submit()"><i class="icon-ok bigger-110" ></i>提交</a>
                                            <?php else: ?>
                                            <a class="sub_f btn btn-info" href="javascript:$('#form1').submit()"><i class="icon-ok bigger-110" ></i>修改</a><?php endif; ?>
                                        <?php if($row["id"] == '' ): ?>&nbsp; &nbsp; &nbsp;
                                            <button class="btn" type="reset">
                                                <i class="icon-undo bigger-110"></i>
                                                重置
                                            </button><?php endif; ?>
                                    </div><div style="clear:both;"></div>
                                </div>


                        </form>


                    </div>


                </div><!-- /.row -->
            </div><!-- /.page-content -->
        </div><!-- /.main-content -->
    </div><!-- /.main-container-inner -->

</div>
<script>
    $().ready(function() {
        $("#form1").validate({
            rules: {
                model: "required",
                title: "required",
                content:"required",
                station:"required",
                add_user:"required",
            },
            messages: {
                model: "不能为空",
                title: "不能为空",
                content:"不能为空",
                add_user:"不能为空",
                station:"不能为空",
            }
        });
    });
    $.validator.setDefaults({
        submitHandler: function(form) {
            form.submit();
        }
    });
</script>
<div class="page-loading-overlay">
	<div class="loader-2"></div>
</div>
<script type="text/javascript">
 window.jQuery || document.write("<script src='/res/Public/Admin/assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<script src="/res/Public/Admin/assets/js/TweenMax.min.js"></script>
<script src="/res/Public/Admin/assets/js/xenon-api.js"></script>
<script src="/res/Public/Admin/assets/js/xenon-custom.js"></script>
		<script src="/res/Public/Admin/assets/js/bootstrap.min.js"></script>
		<script src="/res/Public/Admin/assets/js/typeahead-bs2.min.js"></script>
		<!-- dataTables -->
		<script src="/res/Public/Admin/assets/js/jquery.dataTables.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jquery.dataTables.bootstrap.js"></script>
		<!-- ace scripts -->
		<script src="/res/Public/Admin/assets/js/ace-elements.min.js"></script>
		<script src="/res/Public/Admin/assets/js/ace.min.js"></script>
		 <!-- bootstrap -->
		<!-- page specific plugin scripts -->
		<!--[if lte IE 8]>
		  <script src="/res/Public/Admin/assets/js/excanvas.min.js"></script>
		<![endif]-->
		<!-- jqGrid -->
	<script src="/res/Public/Admin/assets/js/jqGrid/jquery.jqGrid.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jqGrid/i18n/grid.locale-en.js"></script>
		<!-- ace scripts -->
		<script src="/res/Public/Admin/assets/js/jquery-ui-1.10.3.custom.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jquery.ui.touch-punch.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jquery.slimscroll.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jquery.easy-pie-chart.min.js"></script>
		<script src="/res/Public/Admin/assets/js/jquery.sparkline.min.js"></script>
		<script src="/res/Public/Admin/assets/js/flot/jquery.flot.min.js"></script>
		<script src="/res/Public/Admin/assets/js/flot/jquery.flot.pie.min.js"></script>
		<script src="/res/Public/Admin/assets/js/flot/jquery.flot.resize.min.js"></script>
		<!-- ace scripts -->

<script type="text/javascript">
	window.jQuery || document.write("<script src='/res/Public/Admin/assets/js/jquery-2.0.3.min.js'>"+"<"+"/script>");
</script>
<!-- <![endif]-->
<!--[if IE]>
<script type="text/javascript">
	window.jQuery || document.write("<script src='assets/js/jquery-1.10.2.min.js'>"+"<"+"/script>");
</script>
<![endif]-->
<script type="text/javascript">
	if("ontouchend" in document) document.write("<script src='assets/js/jquery.mobile.custom.min.js'>"+"<"+"/script>");
</script>
<!-- page specific plugin scripts -->
<!--[if lte IE 8]>

<![endif]-->

<script>
	$(function(){

		//$(".form_date").val(now);
		$('.form_date').datetimepicker({
			language:  'zh-CN',
			weekStart: 1,
			todayBtn:  1,
			autoclose: 1,
			todayHighlight: 1,
			startView: 2,
			minView: 2,
			forceParse: 0,
			format: 'yyyy-m-dd'
	});

});
</script>

	</body>
</html>