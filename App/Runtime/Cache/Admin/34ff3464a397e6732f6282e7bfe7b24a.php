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

</style>

<div class="main-container" id="main-container">
    <div class="main-container-inner">
        <div class="main-content">
            <div class="page-content">
                <div class="row">
                    <div class="col-xs-12">
                        <div class="widget-box transparent" id="recent-box">
                            <div class="widget-header">
                                <div class="widget-toolbar no-border">
                                    <ul class="nav nav-tabs" id="recent-tab">
                                            <?php if($nostat == ''): ?><li class="active"><a href="<?php echo U(CONTROLLER_NAME . '/yjindex');?>">管理员查看
                                                <?php else: ?>
                                                <li class="active"><a href="<?php echo U(CONTROLLER_NAME . '/yjindex',array('status'=>1));?>"> 已提交<?php endif; ?>

                                        </a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="main-container" id="main-container">
                            <script type="text/javascript">
                                try{ace.settings.check('main-container' , 'fixed')}catch(e){}
                            </script>

                            </div>

                            <div class="main-container-inner">

                                <div class="row">
                                    <div class="col-xs-12">
                                    <!--    <a href="#" class="all_val" >批量删除</a>-->
                                        <div class="table-responsive">
                                            <table id="sample-table-1"
                                                   class="table table-striped table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <td>模块	</td>
                                                    <td>标题	</td>
                                                    <td>内容</td>
                                                    <td>提交人</td>
                                                    <td>部门</td>
                                                    <td>时间	</td>
                                                    <td>创建用户	</td>
                                                    <td>正在开发	</td>
                                                    <td>状态</td>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                                        <input type="hidden" name="id" id="id" value="<?php echo ($vo["id"]); ?>" />
                                                        <td style="width:10%;"> <?php echo ($vo["model"]); ?></td>
                                                        <td style="width:10%;" ><?php echo ($vo["title"]); ?></td>
                                                        <td class="hidden-480" style="width:30%;"> <?php echo ($vo["content"]); ?> </td>
                                                        <td class="hidden-480"> <?php echo ($vo["add_user"]); ?> </td>
                                                        <td class="hidden-480"> <?php echo ($vo["station"]); ?> </td>

                                                        <td class="hidden-480"><?php echo (date("Y-m-d H:i:s",$vo["time"])); ?></td>
                                                        <td class="hidden-480"> <?php echo (readUser($vo["user_id"])); ?> </td>
                                                        <td class="hidden-480">
                                                            <?php if($nostat == ''): ?><label> <input name=""
                                                                               class="ace ace-switch ace-switch-6" type="checkbox"
                                                                               id="showr_<?php echo ($vo["id"]); ?>"
                                                                               onChange="show_changer('<?php echo ($vo["id"]); ?>',1)"

                                                                    <?php if($vo["going"] == 1 ): ?>checked="checked"
                                                                        <?php else: endif; ?>
                                                                    /> <span class="lbl"></span> </label>
                                                                <?php else: ?>

                                                                <?php if($vo["status"] == 1 ): ?><span style="color:#00a0e9">已完成</span>
                                                                    <?php else: ?>
                                                                    <?php if($vo["going"] == 1 ): ?><span style="color:#f00">正在开发此模块</span>
                                                                        <?php else: ?>
                                                                        等待开发<?php endif; endif; endif; ?>

                                                        </td>
                                                        <td class="hidden-480">
                                                            <?php if($nostat == ''): ?><label> <input name=""
                                                                               class="ace ace-switch ace-switch-6" type="checkbox"
                                                                               id="show_<?php echo ($vo["id"]); ?>"
                                                                               onChange="show_changer('<?php echo ($vo["id"]); ?>',2)"

                                                                    <?php if($vo["status"] == 1 ): ?>checked="checked"
                                                                        <?php else: endif; ?>
                                                                    /> <span class="lbl"></span> </label>
                                                                <?php else: ?>
                                                                <?php if($vo["status"] == 1 ): ?><span style="color:#00a0e9">已完成</span>
                                                                    <?php else: ?>
                                                                    未完成<?php endif; endif; ?>

                                                        </td>


                                                        <!--<td>
                                                            <a href="#" >[查看]</a>
                                                            <a href="#">[修改]</a>
                                                          &lt;!&ndash;  <a href="#" onclick="delData('<?php echo ($vo["id"]); ?>');">[删除]</a>&ndash;&gt;
                                                            &lt;!&ndash;<div
                                                                class="visible-md visible-lg hidden-sm hidden-xs btn-group"> <a
                                                                href="<?php echo u(CONTROLLER_NAME . '/editData',array('reserve_id'=>$vo['reserve_id']));?>"
                                                                class="btn btn-xs btn-info"> <i
                                                                class="icon-edit bigger-120">编辑</i> </a>
                                                            <button type="button" class="btn btn-xs btn-danger"
                                                            > <i class="icon-trash bigger-120 del"
                                                                 data-id="<?php echo ($vo["reserve_id"]); ?>">删除</i> </button>
                                                        </div>&ndash;&gt;
                                                        </td>-->
                                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                                </tbody>
                                            </table>
                                            <?php if($not_found == '1' ): ?><p style="text-align: center; color: #ccc;">您还没有创建过数据&nbsp;<a href="<?php echo u(CONTROLLER_NAME . '/add');?>">[点击创建]</a></p><?php endif; ?>
                                            <?php if($not_found == '2' ): ?><p style="text-align: center; color: #ccc;">未搜索到任何数据&nbsp;<a href="<?php echo u(CONTROLLER_NAME . '/index');?>">[返回上一页]</a></p><?php endif; ?>
                                            <ul style="" class="fy_li">
                                                <?php echo ($page); ?>
                                            </ul>
                                        </div>
                                        <script>
                                            function show_changer(id,par){
                                                if(par == 1){
                                                    var show = document.getElementById("showr_"+id).checked == true ? '1':'0';
                                                }else if(par == 2){
                                                    var show = document.getElementById("show_"+id).checked == true ? '1':'0';
                                                }
                                                $.ajax({
                                                    url:"<?php echo U(CONTROLLER_NAME . '/show_changer');?>", // 表单提交的地址
                                                    type: 'POST',
                                                    dataType: 'json',
                                                    data:'id='+id+'&show='+show+"&parar="+par, // 表单提交的数据
                                                    success:function(res){
                                                        if(res.res == 'err'){
                                                            alert('操作失败，请稍后再试！');
                                                        }
                                                    }
                                                });
                                            }
                                            function delData(id){
                                                if(confirm('确定要删除？')){
                                                    $.ajax({
                                                        url:"<?php echo U(CONTROLLER_NAME . '/del_data');?>", // 表单提交的地址
                                                        type: 'POST',
                                                        dataType: 'json',
                                                        data:'id='+id+'&status=0', // 表单提交的数据
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

                                            }
                                          /*  function show_change(id){
                                                var show = document.getElementById("show_"+id).checked == true ? '1':'0';
                                                $.ajax({
                                                    url:"<?php echo U(CONTROLLER_NAME . '/show_change');?>", // 表单提交的地址
                                                    type: 'POST',
                                                    dataType: 'json',
                                                    data:'id='+id+'&is_used='+show, // 表单提交的数据
                                                    success:function(res){
                                                        if(res.res == 'err'){
                                                            alert('操作失败，请稍后再试！');
                                                        }
                                                    }
                                                });
                                            }*/
                                        </script>
                                        <!-- /.table-responsive -->

                                        <!-- PAGE CONTENT ENDS -->
                                    </div>
                                    <!-- /.col -->
                                </div>
                                <!-- /.row -->

                            </div>
                            <!-- /.main-container-inner -->

                        </div>
                        <!-- /.main-container -->
                    </div>
                </div>
                <!-- /.row -->
            </div>
            <!-- /.page-content -->
        </div>
        <!-- /.main-content -->

    </div>

    <!-- /.main-container-inner -->

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