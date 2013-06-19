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

<script language="JavaScript" type="text/javascript">

</script>
</head>
<body style="background: none;">
	<div class="content">
		<form action="" method="post">
			<br />
			<p>
				<span class="label-notification">网站名称</span> <input id="phone"
					class="text-input small-input datepicker" type="text" name="phone" />
				*
			</p>
			<br />
			<p>
				<span class="label-notification">网站域名</span> <input id="domain"
					class="text-input small-input datepicker" type="text" name="domain" />
				*
			</p>
			<br />
			<p>
				<span class="label-notification">关键字</span>
				<textarea id="keys" class="text-input medium-input datepicker"
					type="text" name="keys"></textarea>
				*
			</p>
			<br />
			<p>
				<span class="label-notification">网站描述</span>
				<textarea id="desc" class="text-input medium-input datepicker"
					type="text" name="desc"></textarea>
				*
			</p>
			<br />
			<p>
				<span class="label-notification">网站备案号</span> <input id="icp"
					class="text-input small-input datepicker" type="text" name="icp" />
				*
			</p>
			<br />
			<div class="divline"></div>
			<br />
			<p>
				<span class="label-notification">关闭网站</span> <input type="radio"
					checked="" value="1" name="is_close_system"> 是 <input type="radio"
					value="0" name="is_close_system"> 否
			</p>
			<br />
			<p>
				<span class="label-notification">关闭原因</span>
				<textarea id="is_close_why"
					class="text-input medium-input datepicker" type="text"
					name="is_close_why"></textarea>
				*
			</p>
			<br />
			<div class="divline"></div>
			<br />
			<div class="clear"></div>
			<div class="bulk-actions">
				<input type="submit" class="button" value="保存修改" title="保存修改"
					style="margin-left: 150px;" />
			</div>
		</form>
	</div>
</body>
</html>