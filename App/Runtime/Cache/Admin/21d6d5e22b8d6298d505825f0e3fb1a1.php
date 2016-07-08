<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">


    <title></title>
    <meta name="keywords" content="">
    <meta name="description" content="">

    <!--[if lt IE 9]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->

    <link rel="shortcut icon" href="favicon.ico"> <link href="/res/Public/Admin/hplus/css/bootstrap.min14ed.css?v=3.3.6" rel="stylesheet">
    <link href="/res/Public/Admin/hplus/css/font-awesome.min93e3.css?v=4.4.0" rel="stylesheet">

    <link href="/res/Public/Admin/hplus/css/animate.min.css" rel="stylesheet">
    <link href="/res/Public/Admin/hplus/css/style.min862f.css?v=4.1.0" rel="stylesheet">
    <script>if(window.top !== window.self){ window.top.location = window.location;}</script>

</head>

<body class="gray-bg">

    <div class="lock-word animated fadeInDown">
    </div>
    <div class="middle-box text-center lockscreen animated fadeInDown">
        <div>
            <div class="m-b-md">
                <?php if($list["head"] == ''): ?><img alt="image" class="img-circle circle-border" width="200" height="200" src="/res/Public/Admin/hplus/img/p_big1.jpg">
                    <?php else: ?>
                    <img alt="image" class="img-circle circle-border" width="200" height="200" src="<?php echo ($adminheadpath); echo ($list["head"]); ?>"><?php endif; ?>

            </div>
            <h3><?php echo ($list["account"]); ?></h3>
            <p>您需要再次验证密码</p>
            <form class="m-t" role="form" method="post" action="<?php echo U('LockScreen/LockPassword');?>">
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="********" required="">
                </div>
                <button type="submit" class="btn btn-primary block full-width">验证密码</button>
               <br> <a href="<?php echo u('Login/loginOut');?>">[退出当前账户]</a> </form>
        </div>
    </div>
    <script src="/res/Public/Admin/hplus/js/jquery.min.js?v=2.1.4"></script>
    <script src="/res/Public/Admin/hplus/js/bootstrap.min.js?v=3.3.6"></script>

</body>



</html>