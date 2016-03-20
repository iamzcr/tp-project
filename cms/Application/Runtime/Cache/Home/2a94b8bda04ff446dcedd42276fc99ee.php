<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE HTML>
<html>
<head>
    <title><?php echo ($deep_title); ?></title>
    <link href="/zcr/deep_cms/2/Public/Home/css/style.css" rel="stylesheet" type="text/css"  media="all" />

    <link href="/zcr/deep_cms/2/Public/Home/css/slider.css" rel="stylesheet" type="text/css"  media="all" />
    <script  type="text/javascript"  src="/zcr/deep_cms/2/Public/Home/js/jquery.min.js"></script>
    <script type="text/javascript" src="/zcr/deep_cms/2/Public/Home/js/jquery.easing.1.3.js"></script>
    <script type="text/javascript" src="/zcr/deep_cms/2/Public/Home/js/camera.min.js"></script>
    <script type="text/javascript">
        jQuery(function(){
            jQuery('#camera_wrap_1').camera({
                height: '500px',
                pagination: false,
            });
        });
    </script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $(".scroll").click(function(event){
                event.preventDefault();
                $('html,body').animate({scrollTop:$(this.hash).offset().top},1200);
            });
        });
    </script>
</head>
<body>
<div class="header">
    <div class="wrap">
        <div class="logo">
            <!--<a href="index.html"><img src="/zcr/deep_cms/2/Public/Home/images/logo.png" title="logo" /></a>-->
            <a href="index.html"><img src="/zcr/deep_cms/2/Public/Upload/base/<?php echo ($deep_logo); ?>" style="width: 209px;height: 36px;" title="logo" /></a>
        </div>
        <div class="top-nav">
            <ul>
                <li class="active"><a href="<?php echo U('/');?>">首页</a></li>
                <?php if(is_array($category_show_list)): $i = 0; $__LIST__ = $category_show_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('category/index',array('category_id'=>$list['category_id']));?>"><?php echo ($list["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                <?php if(is_array($column_show_list)): $i = 0; $__LIST__ = $column_show_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$column_list): $mod = ($i % 2 );++$i;?><li><a href="<?php echo U('Column/index',array('column_id'=>$column_list['column_id']));?>"><?php echo ($column_list["name"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
                <li><a href="<?php echo U('Message/index');?>">联系我们</a></li>
                <div class="clear"> </div>
            </ul>
        </div>
        <div class="clear"> </div>
    </div>
    <!---End-header---->
</div>


<!--start-image-slider---->
<div class="slider">
    <div class="camera_wrap camera_azure_skin" id="camera_wrap_1">
        <div data-src="/zcr/deep_cms/2/Public/Home/images/slider3.jpg">  </div>
        <div data-src="/zcr/deep_cms/2/Public/Home/images/slider2.jpg">  </div>
        <div data-src="/zcr/deep_cms/2/Public/Home/images/slider1.jpg">  </div>
        <div data-src="/zcr/deep_cms/2/Public/Home/images/slider2.jpg">  </div>
    </div>
    <div class="clear"> </div>
</div>
<!--End-image-slider---->
<div class="copyrights">Collect from <a href="http://www.cssmoban.com/"  title="��վģ��">��վģ��</a></div>
<!---start-content---->
<div class="content">
    <div class="top-grids">
        <div class="wrap">
            <div class="top-grid">
                <a href="#"><img src="/zcr/deep_cms/2/Public/Home/images/icon1.png" title="icon-name" /></a>
                <h3>室内装修</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                <a class="button" href="#">Read More</a>
            </div>
            <div class="top-grid">
                <a href="#"><img src="/zcr/deep_cms/2/Public/Home/images/icon2.png" title="icon-name" /></a>
                <h3>室外装修</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.  </p>
                <a class="button" href="#">Read More</a>
            </div>
            <div class="top-grid last-topgrid">
                <a href="#"><img src="/zcr/deep_cms/2/Public/Home/images/icon4.png" title="icon-name" /></a>
                <h3>建材售卖</h3>
                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
                <a class="button" href="#">Read More</a>
            </div>
            <div class="clear"> </div>
        </div>
    </div>

</div>
<!---End-content---->


<!---start-copy-right----->
<div class="copy-right">
    <div class="top-to-page">
        <a href="#top" class="scroll"> </a>
        <div class="clear"> </div>
    </div>
    <p>Design by <a href="http://www.szhankaijc.com/" title="深圳汉凯建材有限公司" target="_blank">深圳汉凯建材有限公司</a></p>
</div>
<!---End-copy-right---->
<!----End-wrap---->
</div>
</body>
</html>