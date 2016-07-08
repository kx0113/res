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
<script type="text/javascript" charset="utf-8" src="/res/Public/Components/ueditor1_4_3-utf8-php/ueditor.config.js"></script>
<script type="text/javascript" charset="utf-8" src="/res/Public/Components/ueditor1_4_3-utf8-php/ueditor.all.min.js"> </script>
<!--建议手动加在语言，避免在ie下有时因为加载语言失败导致编辑器加载失败-->
<!--这里加载的语言文件会覆盖你在配置项目里添加的语言类型，比如你在配置项目里配置的是英文，这里加载的中文，那最后就是中文-->
<script type="text/javascript" charset="utf-8" src="/res/Public/Components/ueditor1_4_3-utf8-php/lang/zh-cn/zh-cn.js"></script>
<script src="/res/Public/Admin/hplus/js/jquery.min.js?v=2.1.4"></script>
</head>

<body class="gray-bg">
<script type="text/javascript">
    var ue = UE.getEditor('container');

    $().ready(function() {
        $("form").validate({
            submitHandler:function(form){
                var text = ue.getContent();
                var name = $('#name').val();
                if(name == ''){
                    alert('请输入标题！');
                    return false;
                }
                if(text == ''){
                    alert('请输入内容！');
                    return false;
                }
                form.submit();
            }
        });
    });
    //修改
    function savedata(e){
        var url = "<?php echo u(CONTROLLER_NAME . '/editData');?>";
        subDate(e,url);
    }
    //创建新数据
    function adddata(e){
        var url = "<?php echo u(CONTROLLER_NAME . '/add');?>";
        subDate(e,url);
    }
    function subDate(e,url){

        var id= '';
        var text = ue.getContent();
        var name = $('#name').val();
        id += $('#id').val();
        if(name == ''){
            alert('请输入标题！');
            return false;
        }
        if(text !== ''){
            $(e).attr('disabled',true);
            // disabled="disabled"
            var data = "text="+text+"&par=sub&name="+name+"&id="+id;
          //  alert(data);  return false;
            AjaxSubmitf(data,url);
        }else{
            alert('请输入内容！');
            return false;
        }
        // alert(fue);
    }
    function AjaxSubmitf(data,url) {

        $.ajax({
            url: url,
            type: "POST",
            dataType: "json",
            data: data,
            success: function (res) {
                if (res.res == '1') {
                    alert("操作成功");
                    location.href="<?php echo U(CONTROLLER_NAME . '/index');?>";
                } else {
                    alert("操作失败");
                    location.reload();
                }
            }
        });
    }
</script>
    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">

            <div class="col-sm-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php if($list["id"] == ''): ?>添加<?php else: echo ($list["name"]); endif; ?>
                        <input type="hidden" value="<?php echo ($list["id"]); ?>" name="id" id="id" />
                        </h5>
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
                    <div class="ibox-content icons-box">
                        <form role="form" id="form1" method="post" enctype="multipart/form-data" action="">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="float: left;line-height: 35px;" class=" control-label" style="text-align: right;line-height: 32px;">文章标题：</label>
                                <div class="col-sm-8">
                                    <input type="text" value="<?php echo ($list["name"]); ?>" name="name" id="name" class="form-control" placeholder="请输入文章标题">

                                </div>
                            </div>
                            <div style="clear: both"></div>
                        </div><br><br><br><br>
                      <div>  <textarea id="container" style="width:100%;height:400px;" name="text" >
         <?php echo ($list["text"]); ?>
        </textarea></div>
                        <br><br>
                        <a href="javascript:$('#form1').submit();" class="btn btn-primary " type="button"><i class="fa fa-check"></i>
                            &nbsp;提交
                        </a>
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


</body>
</html>