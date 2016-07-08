<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>上传照片</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <script src='/RES/Public/Admin/assets/js/jquery-2.0.3.min.js'></script>
<link href="/RES/Public/Admin/login/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
<link href="/RES/Public/Admin/login/css/animate.min.css" rel="stylesheet">
<link rel="stylesheet" href="/RES/Public/Admin/assets/css/font-awesome.min.css" />
<link href="/RES/Public/Admin/login/css/plugins/toastr/toastr.min.css" rel="stylesheet">
<script src="/RES/Public/Admin/login/js/plugins/toastr/toastr.min.js"></script>
<link href="/RES/Public/Admin/login/css/bootstrap.min.css" rel="stylesheet">
<link href="/RES/Public/Admin/login/css/animate.min.css" rel="stylesheet">
<link href="/RES/Public/Admin/login/css/style.min.css" rel="stylesheet">
<link href="/RES/Public/Admin/login/css/login.min.css" rel="stylesheet">
<link rel="shortcut icon" href="favicon.ico">
<link href="/RES/Public/Admin/login/css/style.min862f.css?v=4.1.0" rel="stylesheet">
<style>
    .remove_d{font-size:20px;margin:0 10px 0 0;}
    #resgcon{padding:40px;}
    .pb_10{margin:0 0 10px 0;}
    .color666{color:#666;}
    .colorfff{color:#fff;}
</style>
<!--
  toastr.error("<i class='remove_d icon-warning-sign'></i>请选择姓名！");
   toastr.success("<i class='remove_d icon-ok'></i>请选择姓名！");
-->
<script>
   /* toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": true,
        "positionClass": "toast-top-center",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "9000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }*/
    toastr.options = {
        "closeButton": true,
        "debug": false,
        "progressBar": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "showDuration": "400",
        "hideDuration": "1000",
        "timeOut": "7000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    }
</script>

    <link rel="stylesheet" href="/RES/Public/Components/bootstrap-fileinput-master/css/fileinput.min.css" />
    <script src="/RES/Public/Components/bootstrap-fileinput-master/js/fileinput.min.js"></script>

</head>
<body class="gray-bg">

    <div class="wrapper wrapper-content animated fadeInRight">
        <div class="row">



            <div class="col-sm-12" style="margin:0 auto;float:none;">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>上传账户头像</h5>
                        <div class="ibox-tools">
                            <a class="collapse-link">
                                <i class="fa fa-chevron-up"></i>
                            </a>

                        </div>
                    </div>
                    <div class="ibox-content">
<form role="form" id="form1" method="post" enctype="multipart/form-data" action="<?php echo U('upload_head');?>">
                            <div class="form-group" id="input_load" style="">
                                <label class="col-sm-3 control-label">选择头像：</label>
                                <div class="col-sm-8">
                                    <input id="input-dim-1" name="inputdim1[]" type="file" multiple class="file-loading" accept="image/*">
                                </div>
                            </div>
    <input type="hidden" value="" id="uid" name="uid" />
    <div class="form-group" id="subupload" style="">

        <div class="col-sm-4 col-sm-offset-3">
            <br>
            <p style="color:#F00">最佳上传比例 1:1</p>  <br>
            <button class="btn btn-w-m btn-warning " >确定上传</button>
        </div> <div style="clear: both"></div>
    </div>
    <div style="clear: both"></div>
</form>
                            <script>
                                $("#input-dim-1").fileinput({
                                    fileSingle: '单个文件',
                                    filePlural: '多个文件',
                                    browseLabel: '选择文件 &hellip;',
                                    removeLabel: '删除文件',
                                    removeTitle: '删除选中文件',
                                    cancelLabel: '取消',
                                    cancelTitle: '取消上传',
                                    uploadLabel: '上传',
                                    uploadTitle: '上传选中文件',
                                    uploadUrl: false,
                                    initialCaption: "请选择您的照片",
                                    dropZoneTitle: '请选择您的照片',
                                    msgFilesTooMany: '选择上传的文件数量 <b>({n})</b> 超过最大允许的极限 <b>{m}</b>.请重试您的上传!',
                                    uploadAsync: true,
                                    allowedFileExtensions: ["jpg", "png", "gif"],
                                    minImageWidth: 50,
                                    maxFileCount: 1,
                                    showUpload:false,
                                    minImageHeight: 50
                                });
                                $("#input-dim-1").on("fileuploaded", function(event, data) {
                                    alert(data.response["res"]);
                                });
                            </script>

<div style="clear: both"></div>


                </div>

            </div>

        </div>
    </div>
    <script>

        $(".next").click(function(){
            $('.next').attr('disabled',true);
            var user_id = $("#user_id").val();
            var identitynum = $("#identitynum").val();
            if(user_id == '0'){
                toastr.error("<i class='remove_d icon-warning-sign'></i>请选择姓名！");
                $('.next').removeAttr('disabled',true);
                return false;
            }
            if(!identitynum){
                toastr.error("<i class='remove_d icon-warning-sign'></i>请输入身份证号！");
                $('.next').removeAttr('disabled',true);
                return false;
            }
            var data = "user_id="+user_id+"&identitynum="+identitynum
            AjaxVerify(data);
        })
        function AjaxVerify(data) {
            $.ajax({
                url: "<?php echo u(CONTROLLER_NAME . '/verifyUser');?>",
                type: "POST",
                dataType: "json",
                data: data,
                success: function (res) {
                    if (res.res == 'suc') {
                        $("#input_load").show();
                        $("#user_id").attr('disabled',true);
                        $("#identitynum").attr('disabled',true);
                        $('#subOp').hide();
                        $('#subupload').show();
                        $('#uid').val(res.uid);
                        toastr.success("<i class='remove_d icon-ok'></i>核对成功，请继续进行下一步完成本次操作！");
                        return false;
                    }
                    if(res.res == 'err') {
                        toastr.error("<i class='remove_d icon-warning-sign'></i>请检查您的身份证号码是否正确，如有问题请联系人事部！");
                        $('.next').removeAttr('disabled',true);
                        return false;
                    }
                    if(res.res == 'congfu') {
                        toastr.success("<i class='remove_d icon-warning-sign'></i>您已经上传过！");
                        return false;
                    }
                }
            });
        }
    </script>

    <script src="/RES/Public/Admin/login/js/jquery.min.js?v=2.1.4"></script>
<script src="/RES/Public/Admin/login/js/bootstrap.min.js?v=3.3.6"></script>
<script src="/RES/Public/Admin/login/js/content.min.js?v=1.0.0"></script>
<script src="/RES/Public/Admin/login/js/plugins/validate/jquery.validate.min.js"></script>
<script src="/RES/Public/Admin/login/js/plugins/validate/messages_zh.min.js"></script>
<script src="/RES/Public/Admin/login/js/demo/form-validate-demo.min.js"></script>

</body>


<!-- Mirrored from www.zi-han.net/theme/hplus/form_validate.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:16 GMT -->
</html>