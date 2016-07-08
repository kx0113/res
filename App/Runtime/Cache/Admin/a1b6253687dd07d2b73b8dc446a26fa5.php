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

                    <form class="form-horizontal m-t" id="commentForm" method='post'  action="" name="form" enctype="multipart/form-data">

                        <input type="hidden" name="id" value="<?php echo ($list["id"]); ?>">
                        <input type="hidden" name="par" value="<?php echo ($par); ?>">

                        <?php if(($show['name']) == "name"): ?><div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo ($tit["name"]); ?>：</label>
                            <div class="col-sm-5">
                                <input value="<?php echo ($list["name"]); ?>" id="name" name="name" class="form-control" type="text" required aria-required="true">
                            </div>
                        </div><?php endif; ?>

                        <?php if(($show['num']) == "num"): ?><div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo ($tit["num"]); ?>：</label>
                            <div class="col-sm-5">
                                <input id="num" value="<?php echo ($list["num"]); ?>" name="num" class="form-control" type="text" required aria-required="true">
                            </div>
                        </div><?php endif; ?>

                        <?php if(($show['sub_time']) == "sub_time"): ?><div class="form-group" id="data_1">
                            <label class="col-sm-3 control-label"><?php echo ($tit["sub_time"]); ?>：</label>
                            <div class="col-sm-5">
                                <div class="input-group date">
                                    <!--<span class="input-group-addon"><i class="fa fa-calendar"></i></span>-->
                                    <input id="sub_time"  <?php if(!empty($list['sub_time'])): ?>value="<?php echo (date("Y-m-d",$list["sub_time"])); ?>"<?php endif; ?>  name="sub_time" class="laydate-icon form-control layer-date " onclick="ldate()">
                                    <!--<input type="text" name="sub_time" id="sub_time" class=" form_date form-control" value="<?php echo (date("Y-m-d",$list["sub_time"])); ?>">-->
                                </div>
                            </div>
                        </div><?php endif; ?>

                        <?php if(($show['gc_id1']) == "gc_id1"): ?><div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo ($tit["gc_id1"]); ?>：</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="gc_id1" required aria-required="true">
                                    <option value="" >- 请选择 -</option>
                                    <?php if(is_array($goodsclass["class1"])): foreach($goodsclass["class1"] as $key=>$class1): ?><option <?php if($list['gc_id1'] == $class1['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class1["id"]); ?>" ><?php echo ($class1["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div><?php endif; ?>

                        <?php if(($show['gc_id2']) == "gc_id2"): ?><div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ($tit["gc_id2"]); ?>：</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="gc_id2" required aria-required="true">
                                        <option value="" >- 请选择 -</option>
                                        <?php if(is_array($goodsclass["class2"])): foreach($goodsclass["class2"] as $key=>$class2): ?><option <?php if($list['gc_id2'] == $class2['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class2["id"]); ?>" ><?php echo ($class2["name"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div><?php endif; ?>

                        <?php if(($show['gc_id3']) == "gc_id3"): ?><div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo ($tit["gc_id3"]); ?>：</label>
                            <div class="col-sm-5">
                                <select class="form-control" name="gc_id3" required aria-required="true">
                                    <option value="" >- 请选择 -</option>
                                    <?php if(is_array($goodsclass["class3"])): foreach($goodsclass["class3"] as $key=>$class3): ?><option <?php if($list['gc_id3'] == $class3['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class3["id"]); ?>" ><?php echo ($class3["name"]); ?></option><?php endforeach; endif; ?>
                                </select>
                            </div>
                        </div><?php endif; ?>

                        <?php if(($show['gc_id4']) == "gc_id4"): ?><div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ($tit["gc_id4"]); ?>：</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="gc_id4" required aria-required="true">
                                        <option value="" >- 请选择 -</option>
                                        <?php if(is_array($goodsclass["class4"])): foreach($goodsclass["class4"] as $key=>$class4): ?><option <?php if($list['gc_id4'] == $class4['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class4["id"]); ?>" ><?php echo ($class4["name"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div><?php endif; ?>
                        <?php if(($show['gc_id5']) == "gc_id5"): ?><div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ($tit["gc_id5"]); ?>：</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="gc_id5" required aria-required="true">
                                        <option value="" >- 请选择 -</option>
                                        <?php if(is_array($goodsclass["class5"])): foreach($goodsclass["class5"] as $key=>$class5): ?><option <?php if($list['gc_id5'] == $class5['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class5["id"]); ?>" ><?php echo ($class5["name"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div><?php endif; ?>
                        <?php if(($show['gc_id6']) == "gc_id6"): ?><div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo ($tit["gc_id6"]); ?>：</label>
                                <div class="col-sm-5">
                                    <select class="form-control" name="gc_id6" required aria-required="true">
                                        <option value="" >- 请选择 -</option>
                                        <?php if(is_array($goodsclass["class6"])): foreach($goodsclass["class6"] as $key=>$class6): ?><option <?php if($list['gc_id6'] == $class6['id']): ?>selected="selected"<?php endif; ?>value="<?php echo ($class6["id"]); ?>" ><?php echo ($class6["name"]); ?></option><?php endforeach; endif; ?>
                                    </select>
                                </div>
                            </div><?php endif; ?>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否：</label>
                            <div class="col-sm-5">
                                <label class="radio-inline">
                                    <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">未选择</label>
                                <label class="radio-inline">
                                    <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">B</label>
                                <label class="radio-inline">
                                    <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">M</label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">是否500以上：</label>
                            <div class="col-sm-5">
                                <label class="radio-inline">
                                    <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">否</label>
                                <label class="radio-inline">
                                    <input type="radio" value="option2" id="optionsRadios2" name="optionsRadios">是</label>
                            </div>
                        </div>

                        <?php if(($show['remark']) == "remark"): ?><div class="form-group">
                            <label class="col-sm-3 control-label"><?php echo ($tit["remark"]); ?>：</label>
                            <div class="col-sm-5">
                                <textarea id="remark" name="remark" class="form-control" rows="5" ><?php echo ($list["remark"]); ?></textarea>

                            </div>
                        </div><?php endif; ?>

                        <div class="form-group">
                            <div class="col-sm-5 col-sm-offset-3">
                                <button class="margin01000 btn btn-primary" type="submit">提交</button>
                                <button class="btn btn-danger" type="reset">重置</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
<?php if($list["id"] == ''): ?>$(".form_date").attr('value',now);<?php endif; ?>
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


<script src="/res/Public/Admin/hplus/js/plugins/validate/jquery.validate.min.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/validate/messages_zh.min.js"></script>
<script src="/res/Public/Admin/hplus/js/demo/form-validate-demo.min.js"></script>
<script src="/res/Public/Admin/hplus/js/plugins/layer/laydate/laydate.js"></script>
<script>
    function ldate(){
        laydate({istime: true, format: 'YYYY-MM-DD'})
    }
</script>
<script src="/res/Public/Admin/hplus/js/plugins/cropper/cropper.min.js"></script>
<script src="/res/Public/Admin/hplus/js/demo/form-advanced-demo.min.js"></script>
</body>
</html>