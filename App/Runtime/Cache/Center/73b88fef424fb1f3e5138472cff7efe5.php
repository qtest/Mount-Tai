<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title><?php echo C('MYCOMPANY')."-".C('SYSNAME');?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="__PUBLIC__/Css/center/common.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/Plugin/jqueryUI/icon.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/Plugin/jqueryUI/easyui.css" rel="stylesheet" type="text/css" />
    <script src="__PUBLIC__/Js/jquery.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/Plugin/jqueryUI/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/Plugin/jqueryUI/easyui-lang-zh_CN.js" type="text/javascript"></script>
    <script src="__PUBLIC__/Js/jquery.form.js" type="text/javascript"></script>
    <script src="__PUBLIC__/Js/body_load.js" type="text/javascript"></script>
    <link href="__PUBLIC__/Plugin/mayabox/style.css" rel="stylesheet" type="text/css" />
    <script src="__PUBLIC__/Plugin/mayabox/Maya.Box.js" type="text/javascript"></script>
    <link href="__PUBLIC__/Plugin/mayamsg/mayamsg.css" rel="stylesheet" type="text/css" />
    <script src="__PUBLIC__/Plugin/mayamsg/mayamsg.js" type="text/javascript"></script>
	<!--<link href="__PUBLIC__/chosen/chosen.css" rel="stylesheet" type="text/css" />
	<script src="__PUBLIC__/chosen/chosen.jquery.js" type="text/javascript"></script>-->
	<!--[if IE 6]>
	<script type="text/javascript" src="__PUBLIC__/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		DD_belatedPNG.fix('#task_contain,img,.nav_li,.right_menu');
		 //]]>
	</script>
	<![endif]-->

<script language="javascript">
$(document).ready(function() {
	$(".close").click(function() {
		$(this).parent().fadeTo(400, 0, function() { // Links with the class
														// "close" will close parent
			$(this).slideUp(400);
		});
		return false;
	});
})
</script>
<link rel="stylesheet" href="__PUBLIC__/Css/center/home_style.css"
	type="text/css" media="screen" />
<link rel="stylesheet" href="__PUBLIC__/Css/center/invalid.css" type="text/css"
	media="screen" />
</head>
<body>
	<div id="main-content">
		<?php if($_SESSION['cmp']):?>
		<div class="notification information png_bg">
			<a href="#" class="close"> <img
				src="__PUBLIC__/Images/center/icons/cross_grey_small.png" title="关闭"
				alt="close" /></a>
			<div>
				消息：<b>上次登录时间: <?php echo date ( "Y年m月d日 h点i分", strtotime($_SESSION ['cmp'] ['u_logTime']))?>&nbsp;&nbsp;</b>
			</div>
		</div>
		<?php if($_SESSION['cmp']['g_manager'] ==1):?>
		<div class="notification success png_bg">
			<a href="#" class="close"> <img
				src="__PUBLIC__/Images/center/icons/cross_grey_small.png" title="关闭"
				alt="close" /></a>
			<div>
				消息：本月新注册会员&nbsp;&nbsp;<b><?=$regUser?></b>人
			</div>
		</div>
		<div class="notification attention png_bg">
			<a href="#" class="close"> <img
				src="__PUBLIC__/Images/center/icons/cross_grey_small.png" title="关闭"
				alt="close" /></a>
			<div>
				消息：有 <strong><?=$reCharg?></strong> 笔充值预付款待审核！
			</div>
		</div>
		<?php else:?>
		<!-- <div class="notification attention png_bg">
			<a href="#" class="close"> <img
				src="__PUBLIC__/Images/center/icons/cross_grey_small.png" title="关闭"
				alt="close" /></a>
			<div>
				消息：购物车包含 <strong><?=$shCar?></strong> 件项目报价等待处理
			</div>
		</div> -->
		<?php endif;?>
		<?php endif;?>
		
	</div>
</body>
</html>