<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>SB Admin 2 - Bootstrap Admin Theme</title>

    <!-- Bootstrap Core CSS -->
    <link rel="stylesheet" type="text/css" href="/zcr/deep_cms/2/Public/Admin/sb-admin/bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <!-- MetisMenu CSS -->
    <link rel="stylesheet" type="text/css" href="/zcr/deep_cms/2/Public/Admin/sb-admin/bower_components/metisMenu/dist/metisMenu.min.css" />
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="/zcr/deep_cms/2/Public/Admin/sb-admin/dist/css/sb-admin-2.css" />

    <!-- Custom Fonts -->
    <link rel="stylesheet" type="text/css" href="/zcr/deep_cms/2/Public/Admin/sb-admin/bower_components/font-awesome/css/font-awesome.min.css" />

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-md-offset-4">
            <div class="login-panel panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">请登录</h3>
                </div>
                <div class="panel-body">
                    <form role="form" method="post">
                            <div class="form-group">
                                <input class="form-control" placeholder="登录邮箱" name="manager[email]"/>
                            </div>
                            <div class="form-group">
                                <input class="form-control" placeholder="密码" name="manager[password]" type="password"/>
                            </div>
                            <button  type="button" class="btn btn-lg btn-success btn-block submit" data-url="<?php echo U('Login/login');?>">登录</button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
<!-- jQuery -->
<script type="text/javascript" src="/zcr/deep_cms/2/Public/Admin/sb-admin/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap Core JavaScript -->
<script type="text/javascript" src="/zcr/deep_cms/2/Public/Admin/sb-admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Metis Menu Plugin JavaScript -->
<script type="text/javascript" src="/zcr/deep_cms/2/Public/Admin/sb-admin/bower_components/metisMenu/dist/metisMenu.min.js"></script>
<!-- Custom Theme JavaScript -->
<script type="text/javascript" src="/zcr/deep_cms/2/Public/Admin/sb-admin/dist/js/sb-admin-2.js"></script>
<script type="text/javascript" src="/zcr/deep_cms/2/Public/Admin/Js/page/login.js"></script>
</body>
</html>