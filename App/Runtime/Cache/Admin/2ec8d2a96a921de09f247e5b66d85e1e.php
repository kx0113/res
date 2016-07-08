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
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>列表<a style="margin: 0 0 0 10px;" href="<?php echo U(CONTROLLER_NAME . '/add',array('par'=>$par));?>">[创建]</a></h5>
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
            <div class="row row-lg">
                <div class="col-sm-12">
                    <form class="form-horizontal" role="form"  id="form1" enctype="multipart/form-data" action="" method="get">
                        <div class="form-group" id="data_5">
                            <p class="search_title" style="margin: 0 0 0 15px;">选择时间</p>
                            <div class="fl input-daterange input-group" id="datepicker">
                            <input type="text" class=" form-control" name="start_time" value="<?php echo ($fdata["start_time"]); ?>" />
                            <span class="input-group-addon">到</span>
                            <input type="text" class=" form-control" name="end_time" value="<?php echo ($fdata["end_time"]); ?>" />
                            <div class="clb"></div>
                        </div>
                        </div>

                        <div class="fl margin0 margin01500">
                            <p class="search_title"><?php echo ($tit["gc_id3"]); ?></p>
                            <p class="fl margin0">
                                <select class="form-control" name="gc_id3" required aria-required="true">
                                    <option value="">- 请选择 -</option>
                                    <?php if(is_array($goodsclass["class3"])): foreach($goodsclass["class3"] as $key=>$class3): ?><option <?php if($fdata['gc_id3'] == $class3['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class3["id"]); ?>" ><?php echo ($class3["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </p>
                            <div class="clb"></div>
                        </div>

                        <div class="fl margin0 margin01500">
                            <p class="search_title"><?php echo ($tit["gc_id6"]); ?></p>
                            <p class="fl margin0">
                                <select class="form-control" name="gc_id6" required aria-required="true">
                                    <option value="">- 请选择 -</option>
                                    <?php if(is_array($goodsclass["class6"])): foreach($goodsclass["class6"] as $key=>$class6): ?><option <?php if($fdata['gc_id6'] == $class6['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class6["id"]); ?>" ><?php echo ($class6["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </p>
                            <div class="clb"></div>
                        </div>

                        <?php if($par == 1): ?><div class="fl margin0 margin01500">
                            <p class="search_title"><?php echo ($tit["gc_id2"]); ?></p>
                            <p class="fl margin0">
                                <select class="form-control" name="gc_id2" required aria-required="true">
                                    <option value="">- 请选择 -</option>
                                    <?php if(is_array($goodsclass["class2"])): foreach($goodsclass["class2"] as $key=>$class2): ?><option <?php if($fdata['gc_id2'] == $class2['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class2["id"]); ?>" ><?php echo ($class2["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </p>
                            <div class="clb"></div>
                        </div><?php endif; ?>

                        <div class="fl margin0 margin01500">
                            <p class="search_title"><?php echo ($tit["gc_id1"]); ?></p>
                            <p class="fl margin0">
                                <select class="form-control" name="gc_id1" required aria-required="true">
                                    <option value="">- 请选择 -</option>
                                    <?php if(is_array($goodsclass["class1"])): foreach($goodsclass["class1"] as $key=>$class1): ?><option <?php if($fdata['gc_id1'] == $class1['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class1["id"]); ?>" ><?php echo ($class1["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </p>
                            <div class="clb"></div>
                        </div>

                        <div class="clb"></div>
                        <div style="margin:7px 0 0  0;">
                            <div  class=" margin0 fl  margin01500">
                                <p class="search_title"><?php echo ($tit["name"]); ?></p>
                                <p class="fl margin0">
                                    <input type="text" class=" form-control " name="name" value="<?php echo ($fdata["name"]); ?>" />
                                </p>
                                <div class="clb"></div>
                            </div>
                            <div  class=" margin0 fl  margin01500">
                                <p class="search_title"><?php echo ($tit["num"]); ?></p>
                                <p class="fl margin0">
                                    <input type="text" class=" form-control " name="num" value="<?php echo ($fdata["num"]); ?>" />
                                </p>
                                <div class="clb"></div>
                            </div>
                            <div  class=" margin0 fl  margin01500">
                                <p class="search_title"><?php echo ($tit["remark"]); ?></p>
                                <p class="fl margin0">
                                    <input type="text" class=" form-control " name="remark" value="<?php echo ($fdata["remark"]); ?>" />
                                </p>
                                <div class="clb"></div>
                            </div>
                            <a href="javascript:$('form').submit();" class="btn btn-primary">查询</a>
                            <a href="javascript:location.reload();" class="btn btn-danger">重置</a>
                        </div>
                        <div class="clb"></div>
                    </form><br>
                    <a href="/res/Public/enter.xls" class="btn btn-sm  btn-danger">下载Excel模版</a>
                    <a id="" class="btn btn-sm btn-primary onlay">导入Excel</a><br><br>
                    <script>
                        $(function () {
                            $(".onlay").click(function(){
                                layer.open({
                                    type: 2,
                                    title: '导入Excel',
                                    shadeClose: true,
                                    shade: false,
                                    scrollbar:false,
                                    moveType:0,
                                    maxmin: false, //开启最大化最小化按钮
                                    area: ['400px', '400px'],
                                    content: '<?php echo U("Res/Eimport");?>'
                                });
                            });
                        });
                    </script>
                    <div class="alert alert-info">
                        <span>通过下列查询条件：</span><br>
                        <span>[起始时间：<?php echo ($fdata["start_time"]); ?>]</span>
                        <span>-&nbsp;[结束时间：<?php echo ($fdata["end_time"]); ?>]</span><br>
                        <?php if(!empty($fdata['gc_id3'])): ?><span>[<?php echo ($tit["name"]); ?>：<?php echo (getGoodsClassName($fdata["gc_id3"])); ?>] </span><br><?php endif; ?>
                        <?php if(!empty($fdata['gc_id6'])): ?><span>[<?php echo ($tit["gc_id6"]); ?>：<?php echo (getGoodsClassName($fdata["gc_id6"])); ?>] </span><br><?php endif; ?>
                        <?php if(!empty($fdata['gc_id2'])): ?><span>[<?php echo ($tit["gc_id2"]); ?>：<?php echo (getGoodsClassName($fdata["gc_id2"])); ?>] </span><br><?php endif; ?>
                        <?php if(!empty($fdata['gc_id1'])): ?><span>[<?php echo ($tit["gc_id1"]); ?>：<?php echo (getGoodsClassName($fdata["gc_id1"])); ?>] </span><br><?php endif; ?>
                        <?php if(!empty($fdata['name'])): ?><span>[<?php echo ($tit["name"]); ?>：<?php echo ($fdata["name"]); ?>] </span><br><?php endif; ?>
                        <?php if(!empty($fdata['num'])): ?><span>[<?php echo ($tit["num"]); ?>：<?php echo ($fdata["num"]); ?>] </span><br><?php endif; ?>
                        <?php if(!empty($fdata['remark'])): ?><span>[<?php echo ($tit["remark"]); ?>：<?php echo ($fdata["remark"]); ?>] </span><br><?php endif; ?>

                      <span>金额总数：<b><?php echo ($allgetnumm); ?></b></span><br>
                  <strong><?php echo ($allcount); ?></strong>    <span>条数据</span>
                    </div>

                    <!-- Example Cellstyle -->
                    <div class="example-wrap">
                        <div class="example">
                            <table data-toggle="table" data-url="" data-mobile-responsive="true">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th><?php echo ($tit["name"]); ?></th>
                                    <th><?php echo ($tit["num"]); ?></th>
                                    <?php if(($show['gc_id3']) == "gc_id3"): ?><th><?php echo ($tit["gc_id3"]); ?></th><?php endif; ?>
                                    <?php if(($show['gc_id6']) == "gc_id6"): ?><th><?php echo ($tit["gc_id6"]); ?></th><?php endif; ?>
                                    <?php if(($show['gc_id1']) == "gc_id1"): ?><th><?php echo ($tit["gc_id1"]); ?></th><?php endif; ?>
                                    <?php if(($show['gc_id2']) == "gc_id2"): ?><th><?php echo ($tit["gc_id2"]); ?></th><?php endif; ?>
                                    <?php if(($show['gc_id4']) == "gc_id4"): ?><th><?php echo ($tit["gc_id4"]); ?></th><?php endif; ?>
                                    <?php if(($show['gc_id5']) == "gc_id5"): ?><th><?php echo ($tit["gc_id5"]); ?></th><?php endif; ?>
                                    <th><?php echo ($tit["sub_time"]); ?></th>
                                    <th><?php echo ($tit["add_time"]); ?></th>
                                    <th><?php echo ($tit["add_user"]); ?></th>
                                    <th>区分</th>
                                    <th><?php echo ($tit["operation"]); ?></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                            <td><?php echo ($vo["id"]); ?></td>
                            <td><?php echo ($vo["name"]); ?></td>
                            <td><?php echo ($vo["num"]); ?></td>
                            <?php if(($show['gc_id3']) == "gc_id3"): ?><td><?php echo (getGoodsClassName($vo["gc_id3"])); ?></td><?php endif; ?>
                            <?php if(($show['gc_id6']) == "gc_id6"): ?><td><?php echo (getGoodsClassName($vo["gc_id6"])); ?></td><?php endif; ?>
                            <?php if(($show['gc_id1']) == "gc_id1"): ?><td><?php echo (getGoodsClassName($vo["gc_id1"])); ?></td><?php endif; ?>
                            <?php if(($show['gc_id2']) == "gc_id2"): ?><td><?php echo (getGoodsClassName($vo["gc_id2"])); ?></td><?php endif; ?>
                            <?php if(($show['gc_id4']) == "gc_id4"): ?><td><?php echo (getGoodsClassName($vo["gc_id4"])); ?></td><?php endif; ?>
                            <?php if(($show['gc_id5']) == "gc_id5"): ?><td><?php echo (getGoodsClassName($vo["gc_id5"])); ?></td><?php endif; ?>
                            <td><?php echo (date("Y-m-d",$vo["sub_time"])); ?></td>
                            <td><?php echo (date("Y-m-d H:i:s",$vo["add_time"])); ?></td>
                            <td><?php echo (readUser($vo["add_user"])); ?></td>
                            <td>
                                <select onchange="changis_m(<?php echo ($vo["id"]); ?>,this,'确定要修改？')">
                                    <option value="0">未选择</option>
                                    <option <?php if($vo["is_bm"] == 1): ?>selected = "selected"<?php endif; ?> value="1">B</option>
                                    <option <?php if($vo["is_bm"] == 2): ?>selected = "selected"<?php endif; ?> value="2">M</option>
                                </select>
                            </td>
                            <td>
                                <a href="<?php echo U(CONTROLLER_NAME . '/edit',array('par'=>$par,'id'=>$vo['id']));?>">[编辑]</a>
                             <!--   <a onclick="saveStatus_D(<?php echo ($vo["id"]); ?>,0,'确定要删除？');" href="javascript:void(0);">[删除]</a>-->
                            </td>
                        </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                            <ul  class="fy_li">
                                <?php echo ($page); ?>
                            </ul>
                        </div>
                    </div>
                    <!-- End Example Cellstyle -->
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function changis_m(id,obj,msg){
        var i = $(obj).val();
        var data = "id="+id+"&par=is_bm&status="+i;
        if(confirm(msg)) {
            AjaxSubmit('delete_data', data,1);
        }
        return false;
        //alert(i);
    }
    //单条数据删除
    function saveStatus_D(id,status,msg){
        var data = "id="+id+"&par=index&status="+status;
        if(confirm(msg)) {
            AjaxSubmit('delete_data', data,1);
        }
        return false;
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


<script src="/res/Public/Admin/hplus/js/plugins/datapicker/bootstrap-datepicker.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/cropper/cropper.min.js"></script>
<script src="/res/Public/Admin/hplus/js/demo/form-advanced-demo.min.js"></script>
</body>
</html>