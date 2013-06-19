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

<script id="srt" language="javascript">
$(document).ready(function(){
	$('#btndel').linkbutton('disable');	
	$('.number').numberbox({min:0,precision:0});
	initDataGrid("tbl","","<?=U("Prefer/getAllPM4Json")."/id/".$_GET['id']?>","#dbTool");
	$('.plnum').numberbox({min:0,precision:0});
	$('.percent').numberbox({min:-100,max:100,precision:2});
	$('.valu').numberbox({precision:2});
	$(".plnum").change(function(){
		var id = $(this)[0].id;
		var nid = parseInt(id.split("_")[1]) + 1;
		var nval = parseFloat($(this).val(),10) + 1;
		if(nval < $("#min_"+(nid-1)).val() ){
			nval = parseFloat($("#min_"+(nid-1)).val(),10) + 1;
			$(this).val(nval-1);
		}
		$("#min_"+nid).val(nval);
	});
	$("#srt").remove();
});

function profitNumber(){
	$.post(
			"<?=U(MODULE_NAME.'/profitNumber')?>",
			$("#formLossNum").formSerialize(),
			function(data){
				if(data.status=="1"){
					Maya.Msg({
						type : "success",
						msg : data.info,
						call : function(){
                        	freshIframe();
						}	
					});
					
				}else{
					Maya.Msg(data.info);
				}
			},
			"json"
		);
	return true;
}
function printerSelect(id){
	new Maya.Box({
		text : "通用印刷机",
		url : "<?=U(MODULE_NAME.'/updataPinter')?>/id/"+id,
		win : parent,
		width : 900,
		height : 220,
		btns : [
    		{
				text : "    确定    ",
				onClick : function(w){
					w.getIframe().submitForm(w);
					// _self.close();
				}	
			},
			{
				text : "    取消    ",
				onClick : function(w){
    				w.close();
				}	
			}
		]
	});
}

function  del(id){
	var selected = $('#tbl').datagrid('getSelections');
	//var id = getSelected();
	//if(!id){
	//	return;
	//}
	var idStr = "";
	var title = "";
	for(var i = 0 ; i < selected.length;i++){
		title += "<b>"+(i+1)+".</b>"+selected[i].m_name;
		title += i+1 == selected.length ? "" : ";&nbsp;&nbsp;&nbsp;";
		title += (i+1)%3 == 0 ? "<br />" : "";
		idStr += selected[i].id;
		idStr += i+1 == selected.length ? "" : ",";
	}
	new Maya.Box({
		text : "删除记录",
		chtml : "<b>确定要删除以下绑定的印刷机？</b><br />"+title+"",
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
							"<?=U(MODULE_NAME.'/updataPinter')?>",
							{
								type:'del',
								id:id,
								idStr:idStr
							},
							function(data){
								if(data.status=="1"){
									parent.Maya.Msg({
										type : "success",
										msg : "操作成功",
										call : function(){
											w.close();
											freshIframe();
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
function formatStatus(val,row){
	if (val == 0){  
        return '<a href="#" title="停用" iconCls="icon-no" plain="true"><img src="__PUBLIC__/images/icons/cross_circle.png" /></a>';  //
    } else {  
        return '<a href="#" title="启用" iconCls="icon-no" plain="true"><img src="__PUBLIC__/images/icons/tick_circle.png" /></a>';    
    }  
}
</script>
<style type="text/css">
html {
	background: none;
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
	line-height: 40px;
	overflow: hidden;
}

.cont-attr .attr {
	
}

.cont-attr .attr p {
	overflow: hidden;
}

.cont-attr .attr p .label {
	padding: 2px 22px 2px 0;
	background-color: transparent;
	margin: 0 0 0 5px;
	width: 100px;
	text-align: right;
	float: left;
}

.cont-attr .attr p .input {
	margin: 7px 0 0 5px;
	text-align: left;
	float: left;
}
</style>
</head>
<body style="background: none;">
	<div id="tabs" class="easyui-tabs" data-options="fit:true,plain:true"
		style="overflow: hidden; height: auto; background: #fff;">
		<?php if($proRs['p_category'] == 6):?>
		<div title="标准报价价格设置" iconCls='icon-perf' id="home"
			style="overflow: hidden;">
			<!-- 标准报价价格设置 表格 -->
			
		</div>
		<?php endif;?>
		<div title="服务承诺" iconCls='icon-perf' id="home"
			style="overflow: hidden;">
			<form id="form" name="form" method="post">
				<input type="hidden" name="service" id="service" value="service" />
				<input type="hidden" name="id" id="id" value="<?=$_GET['id']?>" />
				<div class="content">
					<div
						style="padding: 10px; height: auto; background: #F9F9F9; border: 1px solid #d4d4d4;">
						<ul class="cont-attr">
							<li class="attr">
								<p>
									<span class="label">服务承诺</span> <span class="input"> <textarea
											name="service" class="input_text"
											style="height: 40px; width: 300px;" id="service"><?=$rs['s_service']?></textarea>
									</span>
								</p>
							</li>
							<li class="attr">
								<p>
									<span class="label">出货时间</span> <span class="input"> <textarea
											name="shipTime" class="input_text"
											style="height: 40px; width: 300px;" id="shipTime"><?=$rs['s_shipTime']?></textarea>
									</span>
								</p>
							</li>
						</ul>
						<div class="clear"></div>
					</div>
					<div class="page-contain">
						<table width="100%">
							<tr>
								<td width="150"><a href="javascript:void(0);"
									onclick="javascript:$('form').submit()" iconCls="icon-add"
									class="easyui-linkbutton">保存</a></td>
								<td align="center">&nbsp;</td>
							</tr>
						</table>
					</div>
				</div>
			</form>
		</div>
		<div title="印刷机设置" iconCls='icon-perf' id="home"
			data-options="closable:false" style="overflow: hidden;">
			<div class="content">
				<div id="dbTool">
					<a href="#" class="easyui-linkbutton" iconCls="icon-add"
						plain="true" onclick="printerSelect(<?=$_GET['id']?>)">添加</a> <a
						id="btndel" href="#" class="easyui-linkbutton"
						iconCls="icon-cancel" plain="true" onclick="del(<?=$_GET['id']?>)">删除</a>
				</div>
				<table id="tbl" style="height: 300px;">
					<thead>
						<tr>
							<th field="m_type" width="80" align="left"><strong>类型</strong></th>
							<th field="m_name" width="150" align="center"><strong>机器名</strong></th>
							<th field="m_color" width="80" align="center"><strong>颜色</strong></th>
							<th field="m_maxLength" width="80" align="center"><strong>最大印刷尺寸</strong></th>
							<th field="m_maxThick" width="80" align="center"><strong>印刷厚度</strong></th>
							<th field="m_price" width="60" align="center"><strong>开机费</strong></th>
							<th field="m_lastDate" width="150" align="center"><strong>更新时间</strong></th>
							<th field="m_status" width="40" align="center"
								formatter="formatStatus"><strong>状态</strong></th>
							<th field="m_desc" width="150" align="left"><strong>备注</strong></th>
						</tr>
					</thead>
				</table>
			</div>
		</div>
		<div title="放张设置" iconCls='icon-perf' id="home"
			data-options="closable:false" style="overflow: hidden;">
			<form id="formLoss" name="formLoss" method="post">
				<input type="hidden" name="service_Loss" id="service_Loss"
					value="loss" /> <input type="hidden" name="id_Loss" id="id_Loss"
					value="<?=$_GET['id']?>" />
				<div class="content">
					<div
						style="padding: 10px; height: auto; background: #F9F9F9; border: 1px solid #d4d4d4;">
						<ul class="cont-attr">
							<li class="attr">
								<p>
									<span class="label">最低放张</span> <span class="input"><input
										type="text" id="pl_min" name="pl_min"
										class="input_text number" style="width: 60px;"
										value="<?=$rsLoss['pl_min']?>" /> </span>
								</p>
							</li>
							<li class="attr">
								<p>
									<span class="label">基础放张</span> <span class="input"> 每 <input
										type="text" id="baseNum" name="baseNum"
										class="input_text number" style="width: 60px;"
										value="<?=$rsLoss['pl_papers']?>" /> 印,放张 <input type="text"
										id="baseLoss" name="baseLoss" class="input_text number"
										style="width: 60px;" value="<?=$rsLoss['pl_loss']?>" /><br />
										<font style="color: #438787;">备注：基础放张 = （计算所得张数 / 放张印数）x 放张数 +
											多出不足印数按比例计算放张数</font>
									</span>
								</p>
							</li>
							<li class="attr">
								<p>
									<span class="label">颜色比例基数</span> <span class="input"> <input
										type="text" id="baseColor" name="baseColor"
										class="input_text number" style="width: 60px;"
										value="<?=$rsLoss['pl_colorNum']?>" /><br /> <font
										style="color: #438787;">备注：颜色比例放张 =（颜色数-1）x 颜色放张比例基数</font>
									</span>
								</p>
							</li>
						</ul>
						<div class="clear"></div>
					</div>
					<div class="page-contain">
						<table width="100%">
							<tr>
								<td width="150"><a href="javascript:void(0);"
									onclick="javascript:$('#formLoss').submit()" iconCls="icon-add"
									class="easyui-linkbutton">保存</a></td>
								<td align="center"><font style="color: #C30000;">说明：总用纸量 =
										计算所得张数 + 基础放张 + 颜色比例放张</font></td>
							</tr>
						</table>
					</div>
				</div>
			</form>
		</div>
		<div title="利润值设置（数量）" iconCls='icon-perf' id="home"
			data-options="closable:false" style="overflow: hidden;">
			<form id="formLossNum" name="formLossNum" method="post">
				<input type="hidden" name="plNum" id="plNum" value="num" />
				<input type="hidden" name="id_Num" id="id_Num" value="<?=$_GET['id']?>" />
				<div class="content">
				<table class="tb" style="text-align: center; width: 600px;margin:0 auto;">
						<thead>
							<tr>
								<th width="120">&nbsp;</th>
								<th colspan="2" align="center" width="180">数量范围</th>
								<th colspan="2" align="center">利润设置</th>
							</tr>
						</thead>
						<tr>
							<td>&nbsp;</td>
							<td align="center">最小值</td>
							<td align="center">最大值</td>
							<td align="center">百分比</td>
							<td align="center">固定值</td>
						</tr>
						<?php
 $title = array('一','二','三','四','五'); for($i = 0; $i < 5; $i ++) : ?>
						<tr>
							<td>第<?=$title[$i]?>阶段</td>
							<td colspan="2">
								<input type="text" id="min_<?=$i?>" name="min_<?=$i?>" readonly class="input_text" style="width: 80px" value="<?=$i==0?0:$numArr[$i]['fi_min']?>" />~ 
								<input type="text" id="max_<?=$i?>" name="max_<?=$i?>" style="width: 80px"  <?=$i==4 ? 'readonly class="input_text" value="999999999"':'class="input_text plnum" value="'.$numArr[$i]['fi_max'].'"'?>/>
								</td>
							<td><input type="text" id="pes_<?=$i?>" name="pes_<?=$i?>" class="input_text percent" style="width: 60px" value="<?=$numArr[$i]['fi_percent']?>" />%</td>
							<td><input type="text" id="val_<?=$i?>" name="val_<?=$i?>" class="input_text valu" style="width: 80px" value="<?=$numArr[$i]['fi_value']?>" /></td>
						</tr>
						<?php endfor;?>
						<tr>
							<td style="height:40px;">说明：</td>
							<td colspan="4" align="left">系统会根据客户输入的数量选择对应的利润率，先算百分比再算固定值。<br />可以设置负数。</td>
						</tr>
						<tr>
							<td style="height:40px;padding-right:50px;" align="right" colspan="5"><a href="javascript:void(0);"
									onclick="javascript:profitNumber()" iconCls="icon-add"
									class="easyui-linkbutton">保存</a></td>
						</tr>
					</table>
				</div>
			</form>
		</div>
	</div>
</body>
</html>