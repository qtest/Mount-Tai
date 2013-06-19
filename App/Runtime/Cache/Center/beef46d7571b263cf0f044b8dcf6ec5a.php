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

<style type="text/css">
html {
	background: none;
}

.tbb {
	border-collapse: collapse;
}

.gray {
	font-weight: bold;
	background: #f7f7f7;
}

.tbb td {
	padding: 5px;
	font-size: 12px;
	border: 1px solid #999;
}

.tbb th {
	padding: 5px;
	font-size: 13px;
	background: #efefef;
	border: 1px solid #999;
}

.input_text {
	width: 100px;
}
</style>
<script src="__PUBLIC__/js/initcity.js" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$('.bgloader').fadeOut(500);
	$('.input_text').numberbox({min:0,precision:2});
});
function submitForm(w){
	$.post(
			"<?=U("Manager/userManeyChange")?>",
			$("#form").formSerialize(),
			function(data){
				if(data.status=="1"){
					Maya.Msg({
						type : "success",
						msg : "操作成功",
						call : function(){
							w.close();
							freshIframe();
						}	
					});	
				}else{
					Maya.Msg(data.info);	
				}
			},
			"json"
		);
}
</script>
<body>
	<div class="content">
		<div class="bgloader"></div>
		<form id="form" name="form">
			<input id="user_id" name="user_id" type="hidden" value="<?=$_GET['id']?>" />
			<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 0 auto;" class="tbb">
				<tr>
					<td class="gray" height="30" width="100" align="right">所在用户组：</td>
					<td width="150"><?=$rs['g_name']?></td>
					<td class="gray" height="30" width="100" align="right">账户余额：</td>
					<td><?=$rs['curr_maney']?></td>
				</tr>
				<tr>
					<td class="gray" height="50" align="right">未完成订单数：<br />总金额：</td>
					<td><?=$odTotalIng['total']?> <br /> <?=$odTotalIng['sumManey'] == "" ? 0 : $odTotalIng['sumManey']?></td>
					<td class="gray" align="right">已完成订单数：<br />总金额：</td>
					<td><?=$odTotalOver['total']?> <br /> <?=$odTotalOver['sumManey'] == "" ? 0 : $odTotalOver['sumManey']?></td>
				</tr>
			</table>
			
			<table border="0" cellspacing="0" cellpadding="0" style="width: 100%; margin: 10px auto;" class="tbb">
				<tr>
					<td class="gray" align="right">可用额度：</td>
					<td><?=$rs['avai_maney']?></td>
					<td class="gray" height="30" align="right">信用额度：</td>
					<td><input id="min_maney" name="min_maney" type="text" class="input_text"  value="<?=$rs['min_maney']?>" /></td>
				</tr>
				<tr>
					<td class="gray" height="30" align="right">定金比例：</td>
					<td><input id="front_maney_percent" name="front_maney_percent" type="text"
					 class="input_text" value="<?=$rs['front_maney_percent']?>" /> %</td>
					<td class="gray" align="right">优惠比例：</td>
					<td><input id="tip_percent" name="tip_percent" type="text" class="input_text"  value="<?=$rs['tip_percent']?>" /> %</td>
				</tr>
			</table>
		</form>
	</div>
</body>
</html>