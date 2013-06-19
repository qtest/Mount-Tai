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

<style type="text/css">
.content p {
	line-height: 40px;
	border-bottom: 1px solid #999;
	overflow: hidden;
}

.content p .label-notification {
	font-size:14px;
	padding: 2px 22px 2px 0;
	background-color: transparent;
	margin: 0 0 0 5px;
	width: 150px;
	text-align: right;
	float: left;
}
.content p .input_text{width:25%;}
.bulk-actions{padding-left:100px;}
</style>
</head>
<body style="background: none;">
	<div class="content">
		<form id="form" name="form" action="<?=U(MODULE_NAME.'/'.ACTION_NAME)?>" method="post">
			<br />
			<p>
				<span class="label-notification">博客留言邮件提醒</span>
				<input type="radio" value="1" name="is_email"> 是 <input checked type="radio" value="0" name="is_email"> 否
			</p>
			<br />
			<p>
				<span class="label-notification">邮件smtp服务器</span> <input id="icp"
					class="input_text" type="text" name="smtpServer" />*
			</p>
			<br />
			<p>
				<span class="label-notification">端口</span> <input id="icp"
					class="input_text" type="text" name="smtpPort" />*
			</p>
			<br />
			<p>
				<span class="label-notification">发送邮件登录名</span> <input id="icp"
					class="input_text" type="text" name="emailSendName" />*
			</p>
			<br />
			<p>
				<span class="label-notification">发送邮件登录密码</span> <input id="icp"
					class="input_text" type="text" name="emailSendPwd" />*
			</p>
			<br />
			<p>
				<span class="label-notification">接收邮件地址</span> <input id="icp"
					class="input_text" type="text" name="emailEnd" />*
			</p>
			<br />
			<div class="divline"></div>
			<br />
			<div class="clear"></div>
			<div class="bulk-actions">
				<a href="#" class="easyui-linkbutton" iconCls="icon-save" onclick="javascript:$('#form').submit()">保存修改</a>
			</div>
		</form>
	</div>
</body>
</html>