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
$(document).ready(function(){
	$('.bgloader').fadeOut(500);
});
function submitShopping(w,win){
	$.post(
			"<?=U(MODULE_NAME.'/'.ACTION_NAME)?>",
			{id:$('#id').val()},
			function(data){
				if(data.status=="1"){
					Maya.Msg({
						type : "success",
						msg : data.info,
						call : function(){
							freshShoppingAmount("<?=U('Index/freshCarAmount')?>");
							win.upFile(data.data);
							w.close();
						}
					});
				}else{
					Maya.Msg(data.info);
				}
			},
			"json"
		);
}
<?php if($_SESSION ['cmp'] ['g_manager'] == 1):?>
function showAll(w){
	new Maya.Box({
		text : "详细信息(内部)",
		url : "<?=U(MODULE_NAME.'/showAll')?>/id/<?=$_GET['id']?>",
		win : parent,
		width : 750,
		height : 220,
		btns : [
			{
				text : "    确定    ",
				isCancel:true
			}
		]
	});
}
<?php endif; ?>
function upFile(id){
	parent._upfile = new Maya.Box({
		text : "上传文件",
		url : "<?=U(MODULE_NAME.'/upFile')?>/id/"+id,
		win : parent,
		width : 350,
		height : 220,
		btns : [
    		{
				text : "    提交    ",
				onClick : function(w){
					w.getIframe().submitForm(w);
					// _self.close();
				}	
			},
			{
				text : "    暂不上传    ",
				onClick : function(w){
					w.close();
					top._offerResult.close();
				}
			}
		]
	});
}
</script>
<style type="text/css">
html {
	background: #fff;
}

.cont-attr {
	margin: 0;
	padding: 0;
	list-style: none;
	display: block;
	zoom: 1;
	overflow: hidden;
}

.cont-attr li {
	line-height: 30px;
	overflow: hidden;
	text-align: center;
}

.cont-attr .attr {
	
}

.cont-attr .attr p {
	border-bottom: 1px solid #dedede;
	overflow: hidden;
}

.cont-attr .attr p .label {
	font-size: 14px;
	background-color: transparent;
	margin: 0 0 0 15px;
	line-height: 30px;
	width: 100px;
	text-align: left;
	float: left;
}

.cont-attr .attr p .input {
	font-weight: 800;
	font-size: 14px;
	line-height: 30px;
	margin: 0 0 0 5px;
	text-align: left;
	float: left;
}

.cont-attr .process {
	width: 48%;
	float: left;
}

.cont-attr .process p {
	margin: 0 3px 3px 0;
	border-bottom: 1px solid #dedede;
	overflow: hidden;
}

.cont-attr .process p .input {
	font-size: 14px;
	font-weight: 700;
	margin: 0 0 0 5px;
	text-align: left;
	float: left;
}

.cont-attr .process p .label {
	font-size: 14px;
	background-color: transparent;
	margin: 0 0 0 15px;
	line-height: 30px;
	width: 100px;
	text-align: left;
	float: left;
}
</style>
</head>
<body style="background: none;">
	<div class="bgloader"></div>
	<form id="form" name="form">
		<input type="hidden" name="id" id="id" value="<?=$_GET['id']?>" />
		<div style="height: 60px; text-align: center; padding-top: 10px;line-height: 30px;">
			<h3 style="font-size: 25px; text-align: center"><?=C('MYCOMPANY')?></h3>
			<b>日期：</b><span><?=date('Y 年 m 月 d 日')?></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<b>编号：</b><span><?=$rs['o_number']?></span>
		</div>
		<div id="form_add_point" class="content">
			<div class="easyui-panel" title=""
				style="padding: 10px; overflow: hidden;">
				<ul class="cont-attr">
					<li class="attr">
						<p>
							<b
								style="font-size: 18px; line-height: 35px; float: left; margin: 0 0 0 10px;"><?=$rs['p_name']?></b>
							<span>报价时间:&nbsp;&nbsp;<?=$rs['o_date']?></span>
						</p>
					</li>
					<li class="process">
						<p>
							<span class="label">数量</span> <span class="input"><?=$rs['o_amount']?></span>
						</p>
					</li>
					<li class="process">
						<p>
							<span class="label">尺寸</span> <span class="input"><?=$rs['o_size']?></span>
						</p>
					</li>
						<?php for ($i=0;$i < count($attrArr);$i++):?>
					<li class="process">
						<p>
							<span class="label"><?=$attrArr[$i]['a_name']?></span> <span
								class="input"><?=$attrValue[$i]?></span>
						</p>
					</li>
						<?php endfor;?>
				</ul>
				<ul class="cont-attr">
					<li class="attr">
						<p>
							<span class="label">后加工工序</span> <span class="input"><?=$processInfo?></span>
						</p>
					</li>
				</ul>
			</div>
			<div class="cleaner_h10"></div>
			<div class="easyui-panel" title=""
				style="padding: 10px; overflow: hidden;">
				<ul class="cont-attr">
					<li class="process">
						<p>
							<span class="label">后加工费用</span> <span class="input">￥ <?=$rs['o_pro_price']?> 元</span>
						</p>
					</li>
					<li class="process">
						<p>
							<span class="label">纸价</span> <span class="input">￥ <?=$rs['o_papePrice']?> 元</span>
						</p>
					</li>
					<?php if($_SESSION ['cmp']['g_manager'] == 1):?>
					<li class="process">
						<p>
							<span class="label">成本费用</span> <span class="input">￥ <?=$rs['o_cost']?> 元</span>
						</p>
					</li>
					<?php else:?>
					<li class="process">
						<p>
							<span class="label">单价</span> <span class="input">￥ <?=getCheckNum4Float($rs['o_price']/$rs['o_amount'])?> 元</span>
						</p>
					</li>
					<?php endif;?>
					<li class="process">
						<p>
							<span class="label">印刷费</span> <span class="input">￥ <?=$rs['o_printPrice']?> 元</span>
						</p>
					</li>
				</ul>
				<ul class="cont-attr">
					<li class="attr">
						<p>
							<span class="label" style="font-weight: 700;">总费用</span> <span
								class="input" style="font-size: 20px; color: #ff0000;">￥ <?=$rs['o_price']?> 元</span>
						</p>
					</li>
				</ul>
			</div>
		</div>
	</form>
</body>
</html>