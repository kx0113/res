<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
    <title>登录</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <script src='/res/Public/Admin/assets/js/jquery-2.0.3.min.js'></script>
<link href="/res/Public/Admin/login/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">
<link href="/res/Public/Admin/login/css/animate.min.css" rel="stylesheet">
<link rel="stylesheet" href="/res/Public/Admin/assets/css/font-awesome.min.css" />
<link href="/res/Public/Admin/login/css/plugins/toastr/toastr.min.css" rel="stylesheet">
<script src="/res/Public/Admin/login/js/plugins/toastr/toastr.min.js"></script>
<link href="/res/Public/Admin/login/css/bootstrap.min.css" rel="stylesheet">
<link href="/res/Public/Admin/login/css/animate.min.css" rel="stylesheet">
<link href="/res/Public/Admin/login/css/style.min.css" rel="stylesheet">
<link href="/res/Public/Admin/login/css/login.min.css" rel="stylesheet">
<link rel="shortcut icon" href="favicon.ico">
<link href="/res/Public/Admin/login/css/style.min862f.css?v=4.1.0" rel="stylesheet">
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

    <link rel="stylesheet" href="/res/Public/Admin/assets/css/xenon-core.css">
    <script src="/res/Public/Admin/assets/js/xenon-custom.js"></script>

    <link rel="stylesheet" href="/res/Public/Admin/assets/css/xenon-core.css">
    <link rel="stylesheet" href="/res/Public/Admin/assets/css/xenon-components.css">
    <script src="/res/Public/Admin/assets/js/TweenMax.min.js"></script>
    <script src="/res/Public/Admin/assets/js/xenon-api.js"></script>
    <script src="/res/Public/Admin/assets/js/xenon-custom.js"></script>
    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />

    <![endif]-->

    <script>
        if(window.top!==window.self){window.top.location=window.location};
    </script>

</head>

<body class="signin">
    <div class="signinpanel">
        <div class="row">
            <div class="col-sm-7">
                <div class="signin-info">
                    <div class="logopanel m-b">
                        <h1 style="font-family: '微软雅黑';"><?php echo ($index_title); ?></h1>
                    </div>
                    <div class="m-b"></div>
                    <h4>欢迎使用 <strong><?php echo ($index_title); ?></strong></h4>
                    <ul class="m-b">
                        <li><i class=" icon-circle-arrow-right"></i> 信息展示位1</li>
                        <li><i class=" icon-circle-arrow-right"></i> 信息展示位2</li>
                        <li><i class=" icon-circle-arrow-right"></i> 信息展示位3</li>

                      <!--  <li><i class=" icon-circle-arrow-right"></i>
                            <a href="<?php echo U('Login/head');?>" class="colorfff" id="head">上传个人头像</a></li>
                        <li><i class=" icon-circle-arrow-right"></i> <a href="<?php echo U('Login/alipayaccount');?>" class="colorfff" id="">核对支付宝账号</a></li>
                      --><!--  <li><i class=" icon-circle-arrow-right"></i> 信息展示位2</li>
                        <li><i class=" icon-circle-arrow-right"></i> 信息展示位3</li>
                        <li><i class=" icon-circle-arrow-right"></i> 信息展示位4</li>-->
                    </ul>
                    <strong>还没有账号？ <a href="<?php echo U('Login/registerpage');?>" id="register">立即注册&raquo;</a></strong>
                </div>
            </div>
            <div class="col-sm-5">
                <form>
                    <h4 class="no-margins">登录：</h4>
                    <p class="m-t-md">登录到<?php echo ($index_title); ?></p>
                    <input type="text" name="username" id="username" class="form-control uname" placeholder="用户名" />
                    <input type="password" name="password" id="password"  class="form-control pword m-b" placeholder="密码" />
                    <!--<a href="#">忘记密码了？</a>-->
                    <button class="btn btn-success btn-block suc">登录</button>
                </form>
            </div>
        </div>
        <div class="signup-footer">
            <div class="pull-left">
                &copy;<?php echo ($admin_name_dsda); ?>
            </div>
        </div>
    </div>
    <script>
    function loader(){
        show_loading_bar({
            pct: 90,
            delay: 1.2,
            finish: function(pct)
            {
                show_loading_bar({
                    pct: 2,
                    delay: 1.7,
                    wait: .5,
                });
            }
        });
    }
        $('.suc').click(function(){
            var $username = $('#username');
            var $password = $('#password');
            if (!$username.val()) {
                toastr.error("<i class='remove_d  icon-warning-sign'></i>您没有输入用户名！");
                show_loading_bar({pct: 40, delay: 2,});
                return false;
            }
            if (!$password.val()) {
                toastr.error("<i class='remove_d  icon-warning-sign'></i>您没有输入密码！");
                show_loading_bar({pct: 88, delay: 2,});
                return false;
            }
            var login_param = {
                username: $username.val(),
                password: $password.val()
            };
            $('.suc').attr('disabled',true);
            $('.suc').html('正在登录...');
            $.ajax({
                url:"<?php echo u(CONTROLLER_NAME . '/loginCheck');?>",
                type:"POST",
                dataType:"json",
                data:login_param,
                success:function(res){
                    if(res.result == 'success'){
                        show_loading_bar({pct: 100, delay: 2,});
                        location.href="<?php echo U('Index/index');?>";
                        return false;
                    }
                    if (res.result == 'status') {
                        toastr.error("<i class='remove_d  icon-warning-sign'></i>账户存在问题，请联系管理员，此条数据已被记录数据库中，数次密码错误您的Ip将会被拉入黑名单！");
                        $('.suc').removeAttr('disabled',true);
                        loader();
                        $('.suc').html('登录');
                        return false;
                    }
                    if(res.result == 'usernull'){
                        $('.suc').removeAttr('disabled',true);
                        loader();
                        $('.suc').html('登录');
                        toastr.error("<i class='remove_d  icon-warning-sign'></i>没有此用户，此条数据已被记录数据库中，数次密码错误您的Ip将会被拉入黑名单！");
                        return false;
                    }
                    if(res.result == 'error'){
                        $('.suc').removeAttr('disabled',true);
                        show_loading_bar({pct: 100, delay: 2,});
                        $('.suc').html('登录');
                        loader();
                         toastr.error("<i class='remove_d  icon-warning-sign'></i>密码错误，此条数据已被记录数据库中，数次密码错误您的Ip将会被拉入黑名单！");
                        return false;
                    }
                }
            });
            return false;

        })
    </script>
    <div class="page-loading-overlay">
        <div class="loader-2"></div>
    </div>
</body>


<!-- Mirrored from www.zi-han.net/theme/hplus/login_v2.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 20 Jan 2016 14:19:52 GMT -->
</html>