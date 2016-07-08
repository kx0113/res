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
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="ibox float-e-margins">
        <div class="ibox-title">
            <h5>列表</h5>
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

                    <div class="example-wrap">
                        <div class="example">
                            <table data-toggle="table" data-url="" data-mobile-responsive="true">
                                <thead>
                                <tr>
                                    <th >ID</th>
                                    <th >名称</th>
                                    <th>所属部门</th>
                                    <th >职位名称</th>
                                    <th >正式入职时间</th>
                                    <th >试用期工资</th>
                                    <th >转正时间</th>
                                    <th >转正工资</th>
                                    <th >保险待遇</th>
                                    <th >离职时间</th>
                                    <th >创建时间</th>
                                    <th >创建用户</th>
                                    <th >操作</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><tr>
                                        <td ><?php echo ($vo["id"]); ?></td>
                                        <td ><?php echo ($vo["name"]); ?></td>
                                        <td ><?php echo ($vo["section"]); ?></td>
                                        <td ><?php echo ($vo["position"]); ?></td>
                                        <td ><?php if(!empty($vo['entry_time'])): echo (date("Y-m-d",$vo["entry_time"])); else: ?>
                                            无<?php endif; ?></td>
                                        <td ><?php echo ($vo["salary_a"]); ?></td>
                                        <td ><?php if(!empty($vo['become_time'])): echo (date("Y-m-d",$vo["become_time"])); else: ?>
                                            无<?php endif; ?></td>
                                        <td ><?php echo ($vo["salary_b"]); ?></td>
                                        <td ><?php echo ($vo["salary_c"]); ?></td>
                                        <td ><?php if(!empty($vo['dimission_time'])): echo (date("Y-m-d",$vo["dimission_time"])); else: ?>无<?php endif; ?></td>
                                        <td ><?php echo (date("Y-m-d H:i:s",$vo["add_time"])); ?></td>
                                        <td><?php echo (readUser($vo["add_user"])); ?></td>
                                        <td >
                                            <a href="<?php echo U(CONTROLLER_NAME . '/editData',array('id'=>$vo['id']));?>">[编辑]</a>
                                            <a onclick="saveStatus_D(<?php echo ($vo["id"]); ?>,0,'确定要删除？');" href="javascript:void(0);">[删除]</a>
                                        </td>
                                    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
                                </tbody>
                            </table>
                            <ul class="fy_li"><?php echo ($page); ?></ul>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
<script>
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


</body>
</html>