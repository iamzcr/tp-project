<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Deep_shop商城</title>
    <link href="/deep_shop/Public/Admin/sb-admin/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/deep_shop/Public/Admin/sb-admin/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">
    <link href="/deep_shop/Public/Admin/sb-admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">
    <link href="/deep_shop/Public/Admin/sb-admin/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
    <link href="/deep_shop/Public/Admin/sb-admin/dist/css/sb-admin-2.css" rel="stylesheet">
    <link href="/deep_shop/Public/Admin/sb-admin/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
<div id="wrapper">
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="index.html">
                    <strong>Deep_shop商城</strong>
            </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu dropdown-user">
                    <li><a href="#"><i class="fa fa-user fa-fw"></i>个人中心</a>
                    </li>
                    <li><a href="#"><i class="fa fa-gear fa-fw"></i>修改密码</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('User/logout/');?>"><i class="fa fa-sign-out fa-fw"></i> 登出</a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">
                    <li>
                        <a href="<?php echo U('Index/index/');?>"><i class="fa fa-dashboard fa-fw"></i>后台首页控制台</a>
                    </li>
                    <li>
                        <a href="<?php echo U('User/index/');?>"><i class="fa fa-dashboard fa-fw"></i>用户管理</a>
                    </li>
                    <li>
                        <a href="<?php echo U('Order/index/');?>"><i class="fa fa-dashboard fa-fw"></i>订单管理</a>
                    </li>
                    <li>
                        <a href="<?php echo U('Manager/index/');?>"><i class="fa fa-dashboard fa-fw"></i>管理员设置</a>
                    </li>
                    <li>
                        <a href="<?php echo U('Payment/index/');?>"><i class="fa fa-dashboard fa-fw"></i>支付管理</a>
                    </li>
                    <li>
                        <a href="<?php echo U('Category/index/');?>"><i class="fa fa-dashboard fa-fw"></i>分类管理</a>
                    </li>
                    <li>
                        <a href="<?php echo U('Product/index/');?>"><i class="fa fa-dashboard fa-fw"></i>产品管理</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> Charts<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="flot.html">Flot Charts</a>
                            </li>
                            <li>
                                <a href="morris.html">Morris.js Charts</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="tables.html"><i class="fa fa-table fa-fw"></i> Tables</a>
                    </li>
                    <li>
                        <a href="forms.html"><i class="fa fa-edit fa-fw"></i> Forms</a>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="panels-wells.html">Panels and Wells</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="#">Second Level Item</a>
                            </li>
                            <li>
                                <a href="#">Third Level <span class="fa arrow"></span></a>
                                <ul class="nav nav-third-level">
                                    <li>
                                        <a href="#">Third Level Item</a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="blank.html">Blank Page</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
<div id="page-wrapper">
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">新增分类</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    DataTables Advanced Tables
                </div>
                <div class="panel-body">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>名字</label>
                            <input class="form-control" name="manager[name]" type="text">
                        </div>
                        <div class="form-group">
                            <label>邮箱</label>
                            <input class="form-control"  name="manager[email]" type="text">
                        </div>
                        <div class="form-group">
                            <label>密码</label>
                            <input class="form-control"  name="manager[password]" type="password">
                        </div>

                        <div class="form-group">
                            <label>状态</label>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="manager[if_show]" id="optionsRadios1" value="1" checked>显示
                                </label>
                            </div>
                            <div class="radio">
                                <label>
                                    <input type="radio" name="manager[if_show]" id="optionsRadios2" value="0">隐藏
                                </label>
                            </div>
                        </div>
                        <input type="submit" class="btn btn-default" value="保存">
                        <a class="btn btn-default" href="<?php echo U('Category/index/');?>">返回</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /#wrapper -->
<script src="/deep_shop/Public/Home/sb-admin/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/deep_shop/Public/Home/sb-admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/deep_shop/Public/Home/sb-admin/bower_components/metisMenu/dist/metisMenu.min.js"></script>
<script src="/deep_shop/Public/Home/sb-admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="/deep_shop/Public/Home/sb-admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script src="/deep_shop/Public/Home/sb-admin/dist/js/sb-admin-2.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>
</body>
</html>