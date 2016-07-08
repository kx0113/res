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
<script src='/res/Public/js/echarts.js'></script>
<link href="/res/Public/Admin/hplus/css/plugins/datapicker/datepicker3.css" rel="stylesheet">
</head>
<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">

    <div class="row">

        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>添加</h5>
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
                            <li><a href="">选项2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>

                <div class="ibox-content">

                    <div id="main" style="width: 100%;height:600px;"></div>
                    <script>
                        var myChart = echarts.init(document.getElementById('main'));
                        option = {
                            tooltip: {
                                trigger: 'item',
                                formatter: "{a} <br/>{b}: {c} ({d}%)"
                            },
                            legend: {
                                orient: 'vertical',
                                x: 'left',
                                data:[<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>'<?php echo ($vo["name"]); ?>',<?php endforeach; endif; else: echo "" ;endif; ?>]
                            },
                            series: [
                                {
                                    name:'个人统计',
                                    type:'pie',
                                    selectedMode: 'single',
                                    radius: [0, '30%'],

                                    label: {
                                        normal: {
                                            position: 'inner'
                                        }
                                    },
                                    labelLine: {
                                        normal: {
                                            show: false
                                        }
                                    },
                                    data:[
                                    <?php if(is_array($list1)): $i = 0; $__LIST__ = $list1;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v1): $mod = ($i % 2 );++$i;?>{value:'<?php echo ($v1["num"]); ?>',name:'<?php echo ($v1["name"]); ?>'<?php if($v1["gc"] == 107): ?>,selected:true<?php endif; ?>},<?php endforeach; endif; else: echo "" ;endif; ?>
                                      //  {value:335, name:'直达', selected:true},
                                      //  {value:335, name:'直达', selected:true},
                                      //  {value:679, name:'营销广告'},
                                     //   {value:1548, name:'搜索引擎'}
                                    ]
                                },
                                {
                                    name:'用途统计',
                                    type:'pie',
                                    radius: ['40%', '55%'],
                                    data:[
                                    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?>{value:'<?php echo ($v["num"]); ?>',name:'<?php echo ($v["name"]); ?>'<?php if($v["gc"] == 119): ?>,selected:true<?php endif; ?>},<?php endforeach; endif; else: echo "" ;endif; ?>
//                        {value:335, name:'直达',selected:true},
                                    ]
                                }
                            ]
                        };
                        myChart.setOption(option);
                    </script>

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


<script src="/res/Public/Admin/hplus/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/cropper/cropper.min.js"></script>
<script src="/res/Public/Admin/hplus/js/demo/form-advanced-demo.min.js"></script>
</body>
</html>