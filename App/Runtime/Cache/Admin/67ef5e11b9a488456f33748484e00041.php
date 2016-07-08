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

    table.dataTable thead .sorting, table.dataTable thead .sorting_asc, table.dataTable thead .sorting_desc, table.dataTable thead .sorting_asc_disabled, table.dataTable thead .sorting_desc_disabled {
        cursor: pointer;
        position: inherit;
    }

    table.dataTable thead .sorting_asc:after {
        content: none;
    }

    table.dataTable thead .sorting:after {
        content: none;
    }
    .input-style input{border-radius:3px;-moz-border-radius:3px;-webkit-border-radius:3px;
        background:#f3f3f3;float:left;}
    .input-style label{float:left;}
    .nbname_style{margin:4px 14px 0 10px;}
    .nbname_style1{margin:6px 0px 0 10px;}
    table.dataTable thead .sorting_desc:after {
        content: none;
    }
</style>
<script type="text/javascript">
    $(function(){
        //设置查询初始时间
        var start_timer = "<?php echo ($fdata["start_time"]); ?>";
        var end_timer = "<?php echo ($fdata["end_time"]); ?>";
        //设置查询初始时间
        $('#start_time').datetimepicker({
            language:  'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            endDate:end_timer,
            minView: 2,
            forceParse: 0,
            format: 'yyyy-mm-dd',
        }).on('changeDate', function(ev){
            var startDate = $('#start_time').val();
            $('#end_time').datetimepicker('setStartDate', startDate);
        });
        $('#end_time').datetimepicker({
            language:  'zh-CN',
            weekStart: 1,
            todayBtn:  1,
            autoclose: 1,
            todayHighlight: 1,
            startView: 2,
            minView: 2,
            startDate:start_timer,
            forceParse: 0,
            format: 'yyyy-mm-dd',
        }).on('changeDate', function(ev){
            var end_time = $('#end_time').val();
            $('#start_time').datetimepicker('setEndDate', end_time);
        });
    })
</script>
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
                                        <li class="active"><a style="content:'111';"
                                                              href="<?php echo U('eval/log');?>">登录日志</a></li>

                                    </ul>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="main-container" id="main-container">
                            <script type="text/javascript">
                                try {
                                    ace.settings.check('main-container', 'fixed')
                                } catch (e) {
                                }
                            </script>


                            <div class="main-container-inner"><a class="menu-toggler" id="menu-toggler" href="#"> <span
                                    class="menu-text"></span> </a>

                                <div class="row">
                                    <form class="form-horizontal" role="form"  id="form1" enctype="multipart/form-data" action="" method="get">
                                        <div><div><!--<?php echo ($fdata['start_time']); ?>-->
                                            <label class="nbname_style1" style="float:left;">起始日期</label>
                                            <div class="col-sm-3">
                                                <div style="padding:0;" class="input-group date start_time " data-date=""
                                                     data-date-format="yyyy-mm-dd" data-link-field="dtp_input2"
                                                     data-link-format="yyyy-mm-dd"

                                                >
                                                    <input class="form-control " id='start_time' placeholder="请选择起始日期"
                                                           size="16" type="text" name="start_time"
                                                    <?php if($fdata["start_time"] != '' ): ?>value="<?php echo ($fdata["start_time"]); ?>"<?php endif; ?>
                                                    >
    <span class="input-group-addon"><span
            class="glyphicon glyphicon-remove"></span></span>
    <span class="input-group-addon"><span
            class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>
                                            <label class="nbname_style1" style="float:left;">结束日期</label>
                                            <div class="col-sm-3">
                                                <div style="padding:0;" class="input-group date end_time " data-date=""
                                                     data-date-format="yyyy-mm-dd" data-link-field="dtp_input2"
                                                     data-link-format="yyyy-mm-dd">
                                                    <input class="form-control " id="end_time" placeholder="请选择结束日期"
                                                           size="16" type="text" name="end_time"
                                                    <?php if($fdata["end_time"] != '' ): ?>value="<?php echo ($fdata["end_time"]); ?>"<?php endif; ?>
                                                    >
    <span class="input-group-addon"><span
            class="glyphicon glyphicon-remove"></span></span>
    <span class="input-group-addon"><span
            class="glyphicon glyphicon-calendar"></span></span>
                                                </div>
                                            </div>  <a class="search" href="javascript:$('form').submit()">查询</a>
                                            <div style="clear: both;"> </div>
                                            <div style="width:100%;float:left;margin:0 0 20px 0;" class="input-style">


                                              </div>

                                    </form>
                                    <div class="col-xs-12">
                                        <!-- PAGE CONTENT BEGINS -->

                                          <div class="table-responsive">
                                          <table id="sample-table-1"
                                             class="table table-striped table-bordered table-hover">
                                            <thead>
                                              <tr>
                                                  <td>操作人账号</td>
                                                  <td>操作人姓名</td>
                                                  <td>行为 </td>
                                                  <td class="hidden-480">时间</td>
                                                  <td>IP</td>
                                              </tr>
                                            </thead>
                                            <tbody>
                                            <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                                    <td><?php echo (readUserAccount($vo["user_id"])); ?></td>
                                                    <td><?php echo (readUser($vo["user_id"])); ?></td>
                                                    <td><?php echo ($vo["operation"]); ?></td>
                                                    <td class="hidden-480">
                                                        <?php echo (date("Y-m-d H:i:s",$vo["time"])); ?>
                                                    </td>
                                                    <td><a href="http://ip138.com/ips138.asp?ip=<?php echo ($vo["ip"]); ?>&action=2"><?php echo ($vo["ip"]); ?></a></td>
                                                   <!-- <td><?php if($vo["status"] == 1): ?>成功<?php else: ?>失败<?php endif; ?></td>-->


                                                    <!-- <?php switch($vo["is_login"]): case "0": ?><font style="color:#f00;">用户主动退出</font><?php break;?>
                                                     <?php case "1": ?>用户登录<?php break;?>
                                                       <?php case "2": ?><font style="color:blue;">系统超时</font><?php break;?>
                                                          <?php default: ?>系统超时<?php endswitch;?>	 -->
                                                    <!-- <?php if($vo["is_login"] == 1 ): ?>登录
                                                      <?php else: ?><font style="color:#f00;">登出</font><?php endif; ?> -->
                                                    </td>
                                                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                            </tbody>
                                          </table>

                                          <p style="text-align: center; color: #ccc;"><?php echo ($not_found); ?></p>
                                          <ul style="" class="fy_li">
                                            <?php echo ($page); ?>
                                          </ul>
                                        </div>


                                        <script type="text/javascript">
                                            $(document).ready(function () {
                                                //$(".pagination > .prev > a").text('22');



                                                $('table th input:checkbox').on('click', function () {
                                                    var that = this;
                                                    $(this).closest('table').find('tr > td:first-child input:checkbox')
                                                            .each(function () {
                                                                this.checked = that.checked;
                                                                $(this).closest('tr').toggleClass('selected');
                                                            });

                                                });


                                                $('[data-rel="tooltip"]').tooltip({placement: tooltip_placement});
                                                function tooltip_placement(context, source) {
                                                    var $source = $(source);
                                                    var $parent = $source.closest('table')
                                                    var off1 = $parent.offset();
                                                    var w1 = $parent.width();

                                                    var off2 = $source.offset();
                                                    var w2 = $source.width();

                                                    if (parseInt(off2.left) < parseInt(off1.left) + parseInt(w1 / 2)) return 'right';
                                                    return 'left';
                                                }
                                            })
                                        </script>
                                        <!-- /.table-responsive -->

                                        <script type="text/javascript">
                                            $('.search').click(function () {
                                                var name = $('#name').val();
                                                if (name == "") {
                                                    alert('请输入您要搜索的数据！');
                                                    $('#name').focus();
                                                    return false;
                                                }
                                            });
                                            $(".chkall").click(function () {
                                                var checked = $(this).prop("checked");
                                                $('.box').prop("checked", checked);
                                            });
                                            $(".all_val").click(function () {
                                                var list = new Array();
                                                $("input[id='style_val']:checked").each(
                                                        function () {
                                                            list.push($(this).val());
                                                        });
                                                if (list == '') {
                                                    alert('请选择您要删除的数据！');
                                                    return false;
                                                }
                                                var data = "list=" + list.join(',');

                                                $.ajax({
                                                    url: "<?php echo u(CONTROLLER_NAME . '/previous_delete');?>",
                                                    type: "POST",
                                                    dataType: "json",
                                                    data: data,
                                                    success: function (res) {
                                                        if (res.result == 'success1') {
                                                            alert("操作成功");
                                                            location.reload()
                                                            // location.href="<?php echo u('/home/index/cc');?>"
                                                        } else {
                                                            alert("操作失败");
                                                        }

                                                    }
                                                });

                                            });
                                            function selectall(name) {
                                                if ($("#check_box").attr("checked") == 'checked') {
                                                    $("input[name='" + name + "']").each(function () {
                                                        $(this).attr("checked", "checked");

                                                    });
                                                } else {
                                                    $("input[name='" + name + "']").each(function () {
                                                        $(this).removeAttr("checked");
                                                    });
                                                }
                                            }

                                            $('.del').click(function () {
                                                var id = $(this).attr('data-id');
                                                $('.del_id').attr('data-cid', id);
                                            });
                                            $('.del_id').click(function () {
                                                var id = $(this).attr('data-cid');
                                                $.ajax({
                                                    url: "<?php echo u(CONTROLLER_NAME . '/previous_delete');?>",
                                                    type: "POST",
                                                    dataType: "json",
                                                    data: $(".form-horizontal").serialize() + "&id=" + id,
                                                    success: function (res) {
                                                        if (res.result == 'success') {
                                                            //alert("操作成功");
                                                            location.reload()
                                                            // location.href="<?php echo u('/home/index/cc');?>"
                                                        } else {
                                                            alert("操作失败");
                                                        }

                                                    }
                                                });
                                                // $('.del_id').attr('data-cid',id);
                                            });

                                            function show_change(id) {

                                                var show = document.getElementById("show_" + id).checked == true ? '1' : '0';

                                                $.ajax({
                                                    url: "<?php echo U(CONTROLLER_NAME . '/show_change');?>", // 表单提交的地址
                                                    type: 'POST',
                                                    dataType: 'json',
                                                    data: 'id=' + id + '&status=' + show, // 表单提交的数据
                                                    success: function (res) {
                                                    }
                                                });
                                            }


                                            $('table th input:checkbox').on('click', function () {
                                                var that = this;
                                                $(this).closest('table').find('tr > td:first-child input:checkbox')
                                                        .each(function () {
                                                            this.checked = that.checked;
                                                            $(this).closest('tr').toggleClass('selected');
                                                        });

                                            });


                                        </script>

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