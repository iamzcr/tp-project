<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Deep_shop后台管理</title>
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
    <!-- 配置文件 -->
    <script type="text/javascript" src="/deep_shop/Public/Common/utf8-php/ueditor.config.js"></script>
    <!-- 编辑器源码文件 -->
    <script type="text/javascript" src="/deep_shop/Public/Common/utf8-php/ueditor.all.js"></script>
    <script type="text/javascript" charset="utf-8" src="/deep_shop/Public/Common/utf8-php/lang/zh-cn/zh-cn.js"></script>
    <!-- 实例化编辑器 -->
    <script type="text/javascript">
        var ue = UE.getEditor('container');
    </script>
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
                    <li><a href="#"><i class="fa fa-user fa-fw"></i><?php echo ($name); ?></a>
                    </li>
                    <li><a href="<?php echo U('Manager/pwd/');?>"><i class="fa fa-gear fa-fw"></i>修改密码</a>
                    </li>
                    <li class="divider"></li>
                    <li><a href="<?php echo U('Manager/logout/');?>"><i class="fa fa-sign-out fa-fw"></i> 登出</a>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="navbar-default sidebar" role="navigation">
            <div class="sidebar-nav navbar-collapse">
                <ul class="nav" id="side-menu">

                    <li>
                        <a href="<?php echo U('Index/index/');?>"><i class="fa fa-dashboard fa-fw"></i>控制台</a>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-gear fa-fw"></i> 基本设置<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo U('Options/logo/');?>">logo设置</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Options/introduce/');?>">商城介绍</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Options/setting/');?>">商城设置</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Options/seo/');?>">seo设置</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Options/service/');?>">服务条款</a>
                            </li>
                        </ul>
                    </li>


                    <li>
                        <a href="#"><i class="fa fa-shopping-cart fa-fw"></i> 商品设置<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo U('Category/index/');?>">分类管理</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Brand/index/');?>">品牌管理</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Tag/index/');?>">标签管理</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Product/index/');?>">商品管理</a>
                            </li>

                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i> 营销设置<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo U('Coupon/index/');?>">优惠管理</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Bonus/index/');?>">红包管理</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Banner/index/');?>">广告管理</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Links/index/');?>">友情链接</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-user fa-fw"></i> 账户设置<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo U('User/index/');?>">用户管理</a>
                            </li>
                            <li>
                                <a href="<?php echo U('Manager/index/');?>">管理员管理</a>
                            </li>
                        </ul>
                    </li>

                    <li>
                        <a href="#"><i class="fa fa-keyboard-o fa-fw"></i> 销售设置<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo U('Order/index/');?>">订单管理</a>
                            </li>
                            <!--<li>-->
                                <!--<a href="<?php echo U('Payment/index/');?>">支付管理</a>-->
                            <!--</li>-->
                            <li>
                                <a href="<?php echo U('Manager/index/');?>">物流管理</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="#"><i class="fa  fa-list fa-fw"></i> 文章设置<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li>
                                <a href="<?php echo U('CmsCategory/index/');?>">分类管理</a>
                            </li>
                            <li>
                                <a href="<?php echo U('CmsPost/index/');?>">文章管理</a>
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
            <h1 class="page-header">新增友情链接</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-body">
                    <form role="form" method="post">
                        <div class="form-group">
                            <label>名字</label>
                            <input class="form-control" name="links[name]" type="text">
                        </div>
                        <div class="form-group">
                            <label>链接</label>
                            <input class="form-control"  name="links[links]" type="text">
                        </div>
                        <input type="submit" class="btn btn-default" value="保存">
                        <a class="btn btn-default" href="<?php echo U('Links/index/');?>">返回</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<!-- /#wrapper -->
<script src="/deep_shop/Public/Admin/sb-admin/bower_components/jquery/dist/jquery.min.js"></script>
<script src="/deep_shop/Public/Admin/sb-admin/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<script src="/deep_shop/Public/Admin/sb-admin/bower_components/metisMenu/dist/metisMenu.min.js"></script>
<script src="/deep_shop/Public/Admin/sb-admin/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
<script src="/deep_shop/Public/Admin/sb-admin/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
<script src="/deep_shop/Public/Admin/sb-admin/dist/js/sb-admin-2.js"></script>
<script>
    $(document).ready(function() {
        $('#dataTables-example').DataTable({
            responsive: true
        });
    });
</script>
</body>
</html>