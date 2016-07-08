<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
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
<div style="margin:30px;">
    <form method="post" action="/res/index.php/Res/import_execlt" enctype="multipart/form-data">
        <div> <span>请选择姓名3：</span>
            <select name="gc_id3" >
                <option value="0">- 请选择 -</option>
                <?php if(is_array($gclist3)): $i = 0; $__LIST__ = $gclist3;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gls3): $mod = ($i % 2 );++$i;?><option value="<?php echo ($gls3["id"]); ?>"><?php echo ($gls3["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <br>
        <div> <span>请选择购买方式2：</span>
            <select name="gc_id2" >
                <option value="0">- 请选择 -</option>
                <?php if(is_array($gclist2)): $i = 0; $__LIST__ = $gclist2;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$gls2): $mod = ($i % 2 );++$i;?><option value="<?php echo ($gls2["id"]); ?>"><?php echo ($gls2["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
            </select>
        </div>
        <br>
        <input style="border: none;"  type="file" name="file_stu" />
        <br><br>
        <button id="" class="btn btn-sm  btn-warning">确定导入Excel</button>
    </form> <br>
    <p style="color:#999"><span style="color:#f00;font-size:20px;" class="icon-warning-sign"></span>
        Excel文件格式仅限于后缀为
        <span style="color:#F00">[.xls]</span>
    </p>
    <!-- <br><br>
     <a id="fe" href="#">关闭</a>
     <script>
         $(function(){
             var index = parent.layer.getFrameIndex(window.name); //获取当前窗体索引
             $('#fe').on('click', function(){
                 parent.layer.close(index); //执行关闭
             });

         });

     </script>-->
</div>
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