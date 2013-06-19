<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <title><?php echo C('MYCOMPANY')."-".C('SYSNAME');?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link href="__PUBLIC__/css/home/home.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/Plugin/jqueryUI/icon.css" rel="stylesheet" type="text/css" />
    <link href="__PUBLIC__/Plugin/jqueryUI/easyui.css" rel="stylesheet" type="text/css" />
    <script src="__PUBLIC__/js/jquery.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/Plugin/jqueryUI/jquery.easyui.min.js" type="text/javascript"></script>
    <script src="__PUBLIC__/Plugin/jqueryUI/easyui-lang-zh_CN.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/jquery.form.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/body_load.js" type="text/javascript"></script>
    <script src="__PUBLIC__/js/custom.js" type="text/javascript"></script>
    <link href="__PUBLIC__/Plugin/mayabox/style.css" rel="stylesheet" type="text/css" />
    <script src="__PUBLIC__/Plugin/mayabox/Maya.Box.js" type="text/javascript"></script>
    <link href="__PUBLIC__/Plugin/mayamsg/mayamsg.css" rel="stylesheet" type="text/css" />
    <script src="__PUBLIC__/Plugin/mayamsg/mayamsg.js" type="text/javascript"></script>
	<!--<link href="__PUBLIC__/chosen/chosen.css" rel="stylesheet" type="text/css" />
	<script src="__PUBLIC__/chosen/chosen.jquery.js" type="text/javascript"></script>
	<link href="__PUBLIC__/Jquey.DatePicker/ui.datepicker.css" rel="stylesheet" type="text/css" />
	<script src="__PUBLIC__/Jquey.DatePicker/ui.datepicker.js" type="text/javascript"></script>
	
	<link href="__PUBLIC__/flexigrid/flexigrid.pack.css" rel="stylesheet" type="text/css" />
	<script src="__PUBLIC__/flexigrid/flexigrid.pack.js" type="text/javascript"></script>-->
	<!--[if IE 6]>
	<script type="text/javascript" src="__PUBLIC__/js/DD_belatedPNG_0.0.8a-min.js"></script>
	<script type="text/javascript">
		//<![CDATA[
		DD_belatedPNG.fix('#task_contain,img,.nav_li,.right_menu');
		 //]]>
	</script>
	<![endif]-->

<script language="javascript">
$(document).ready(function(){
	$('.bgloader').fadeOut(500);
});
function submitOrder(w,win){
	<?php if(count($ofArr) < 1):?>
		Maya.Msg("购物车当前是空的！");
	<?php else:?>
	$.post(
			"<?=U(MODULE_NAME.'/addOrderInfo')?>",
			{id:$('#id').val()},
			function(data){
				if(data.status=="1"){
					Maya.Msg({
						type : "success",
						msg : data.info,
						call : function(){
							w.close();
							win.theNext(data.data);
						}
					});
				}else{
					Maya.Msg(data.info);
				}
			},
			"json"
		);
	<?php endif;?>
}
function del(id){
	new Maya.Box({
		text : "删除记录",
		chtml : "<b>确定要从购物车中删除？</b>",
		win : parent,
		isAlert : true,
		iframeAuto : false,
		overlayAlpha : .5,
		iframeScroll : "auto",
		inlineAuto : false,
		type : "inline",
		btns : [
			{
				text : "确定"	,
				onClick : function(w){
					$.post(
							"<?=U(MODULE_NAME.'/del')?>",
							{id:id},
							function(data){
								if(data.status=="1"){
									parent.Maya.Msg({
										type : "success",
										msg : data.info,
										call : function(){
											w.close();
											setTimeout(function() {
												location.reload();
												freshShoppingAmount("<?=U('Index/freshCarAmount')?>");
											},500);
										}
									});	
								}else{
									parent.Maya.Msg({
				                       type : "fail",
				                       msg : data.info
				               		});
								}
							},
							"json"
						);
  	       		 }
			},
			{
				text : "取消"	,
				isCancel : true
			}
		]
	});	
}
</script>
<style type="text/css">
html {
	background: #fff;
}

.cart-empty {
	height: 98px;
	border: 1px solid #ddd;
}

.cart-empty .message {
	height: 98px;
	padding-left: 341px;
	background: #f3f3f3 url(__PUBLIC__/images/car/cart-empty-bg.png) no-repeat 250px 22px;
}
.cart-empty .message p {
line-height: 98px;
}
</style>
</head>
<body style="background: none; padding: 10px;">
	<div class="bgloader"></div>
	<form id="form" name="form">
		<input type="hidden" name="id" id="id" value="<?=$_GET['id']?>" />
		<div
			style="position: absolute; top: 10px; right: 20px; height: 60px; width: 300px; color: #7A7A7A; text-align: right;">
			<h3 style="font-size: 20px;"><?=C('MYCOMPANY')?></h3>
			<span style="margin: 0 30px 0 0;">因为专业，所以放心</span>
		</div>
		<div
			style="width: 300px; padding: 35px 0 0 30px; color: #444; text-align: left;">
			<h2 style="font-size: 15px;">
				我的进行中订单<span style="margin-left: 20px; font-size: 12px;">共计 <b
					style="color: #f60"><?=count($ofArr)?></b> 件
				</span>
			</h2>
		</div>
		<div class="cleaner_h10"></div>
		<?php if(count($ofArr) < 1):?>
		<div class="cart-inner cart-empty">
			<div class="message">
				<p>
					暂时没有进行中订单，您可以去首页自主报价添加。
				</p>
			</div>
		</div>
		<?php else:?>
		<table width="100%" border="0" cellspacing="0" cellpadding="0"
			class="tb">
			<thead>
				<tr>
					<!-- 会员号 用户名 所在组 公司名称 地区 联系人 QQ 登录/报价  -->
					<th width="30" align="center"></th>
					<th align="left"><strong>项目内容</strong></th>
					<th align="center" width="250"><strong>后加工</strong></th>
					<th align="center" width="110"><strong>数量</strong></th>
					<th align="center" width="110"><strong>价格</strong></th>
					<th align="center" width="110"><strong>文件</strong></th>
					<th align="center" width="110"><strong>当前状态</strong></th>
				</tr>
			</thead>
			<tbody>
			<?php
 $i = 0; foreach ( $ofArr as $row ) : $i ++; ?>
				<tr style="height: 50px;">
					<td align="center"><?=$i?></td>
					<td align="left"><b style="font-size: 14px;"><?= $row['p_name']."<br />"?></b><?=$row['attrInfo']?></td>
					<td align="center"><?= $row['processInfo'] ?></td>
					<td align="center"><?= $row['o_amount'] ?></td>
					<td align="center">￥<?= $row['o_price'] ?></td>
					<td align="center"><?php if(empty($row['o_filePath'])): ?>--<?php else:?><a href="__ROOT__/<?=$row['o_filePath']?>" title="点击下载 "><?=$row['o_fileName']?></a><?php endif;?></td>
					<td align="center"><?= $row['statusInfo'] ?></td>
				</tr>
			<?php endforeach; ?>
        </tbody>
		</table>
		<?php endif;?>
	</form>
</body>
</html>