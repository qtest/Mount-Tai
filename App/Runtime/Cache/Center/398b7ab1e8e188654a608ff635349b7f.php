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
function submitForm(){
	<?php if($_SESSION['cmp']): ?>
	$('.bgloader').fadeIn(500);
	//console.info($("#form").formSerialize());
	$.post(
			"<?=U(MODULE_NAME.'/stickerPrice')?>",
			$("#form").formSerialize(),
			function(data){
				if(data.status=="1"){
					Maya.Msg({
						type : "success",
						msg : data.info,
						call : function(){
							showResult(data.data);
							$('.bgloader').fadeOut(500);
						}
					});
				}else{
					Maya.Msg({
						msg : data.info,
						call : function(){
							$('.bgloader').fadeOut(500);
						}	
					});	
					
				}
			},
			"json"
		);
	return true;
	<?php else:?>
	loginBtn('<?=U ( "Login/panel" )?>');
	return false;
	<?php endif;?>
}

function showResult(id){
	top._offerResult = new Maya.Box({
		text : "报价结果详细信息",
		url : "<?=U(MODULE_NAME.'/result')?>/id/"+id,
		win : top,
		width : 750,
		height : 220,
		btns : [
			<?php if($_SESSION ['cmp'] ['g_manager'] == 1):?>
			{
				text : ' 详细信息(内部)',
				onClick : function(w){
					w.getIframe().showAll(w);
					// _self.close();
				}	
			},
			<?php endif;?>
    		{
				text : "    生成订单    ",
				onClick : function(w){
					w.getIframe().submitShopping(w,top);
					// _self.close();
				}	
			},
			{
				text : "    取消    ",
				isCancel:true
			}
		]
	});
}

function change(node){
	//console.info(node);return;
	$.get(
			"<?=U(MODULE_NAME.'/getKeZhong')?>",
			{id:node.id,proId:<?=$_GET['id']?>},
			function(data){
				//console.info(data);loadData
				$('.kezhong').combobox('loadData',data);
			},
			"json"
		);
}

function checkMust(e){
	$(e).attr('checked',true);
}

function showCheck(the,str,com,nm,desc){
	if (the.checked){
		$("#"+str).css("display","inline");
		if(com != undefined && com != ''){
			$("#"+com).combobox('enable');
		}
		if(nm != undefined && nm != ''){
			$("#"+nm).numberbox('enable');
		}
		if(desc != undefined && desc != ''){
			$("#"+desc).attr('disabled',false);
		}
	}else{
		$("#"+str).css("display","none");
		if(com != undefined && com != ''){
			$("#"+com).combobox('disable');
		}
		if(nm != undefined && nm != ''){
			$("#"+nm).numberbox('disable');
		}
		if(desc != undefined && desc != ''){
			$("#"+desc).attr('disabled',true);
		}
	}
}
function showCheckAttr(the,str,id){
	if (the.checked){
		$("#"+str).css("display","inline");
		if(id != undefined && id != ''){
			$('#'+id).combobox('clear');
			$('#'+id).combobox('disable');
		}
	}else{
		$("#"+str).css("display","none");
		if(id != undefined && id != ''){
			$('#'+id).combobox('enable');
		}
	}
}
</script>
<style type="text/css">
.cont-attr {
	margin: 0;
	padding: 0;
	list-style: none;
	display: block;
	zoom: 1;
	overflow: hidden;
}

.cont-attr li {
	overflow: hidden;
	text-align: center;
}

.cont-attr .attr {
	border-bottom: 1px solid #999;
	/*line-height: 25px;	*/
	height: 55px;
	width: 300px;
	float: left;
}

.cont-attr .attr p {
	overflow: hidden;
}

.cont-attr .attr .label { /*padding: 0 15px 0 0;
	line-height: 55px;
	background-color: transparent;
	margin: 0 0 0 5px;
	width: 60px;
	text-align: left;
	float: left;*/
	width: 60px;
	text-align: left;
}

.cont-attr .attr .input {
	line-height: 55px;
	margin: 0 0 0 5px;
	text-align: left;
	width: 240px;
	height: 55px;
}

.cont-attr .attr .inputs {
	width: 60px;
}

.cont-attr .attr .attrDiy {
	line-height: 30px;
	margin-left: -30px;
}

.cont-attr .process {
	border-bottom: 1px solid #999;
	line-height: 40px;
	padding: 5px 10px;
	float: left;
}

.cont-attr .process p {
	overflow: hidden;
}

.cont-attr .process p .input {
	margin: 0 0 0 5px;
	text-align: left;
	float: left;
}
</style>
</head>
<body style="background: none;">
	<form id="form" name="form">
		<input type="hidden" name="id" id="id" value="<?=$_GET['id']?>" />
		<div id="form_add_point" class="content">
			<div class="easyui-panel" fit="true" title=""
				style="padding: 10px; background: #F9F9F9; height: 350px; overflow: hidden;">
				<div class="easyui-layout" data-options="fit:true">
					<div data-options="region:'north',split:true"
						style="height: 200px; padding: 10px">
						<ul class="cont-attr">
						<?php foreach ($attrArr as $row):?>
							<li class="attr">
								<table>
									<tr>
										<td class="label"
											<?php ?>><?=strpos($row['a_name'],'尺寸') === false ? $row['a_name'] : $row['a_name']."(mm)"?></td>
										<td class="input">
									<?php if(strpos($row['a_name'],'尺寸') !== false){?>
										<?php if($row['p_type'] == 2):?>
										<input name="length" type="text"
											class="input_text easyui-numberbox"
											data-options="min:5,precision:0" id="length" value="5"
											size="5" />(长) X <input name="width" type="text"
											class="input_text easyui-numberbox"
											data-options="min:5,precision:0" id="width" value="5"
											size="5" />(宽)
										<?php else:?>
										<select id="<?=$row['id']?>" name="<?=$row['id']?>"
											class="easyui-combobox" style="width: 160px; height: 25px;"
											data-options="required:false,editable:false,valueField: 'id',textField: 's_name',panelHeight:'auto',url: '<?=U('QuotedPrice/getAttrJsonData')?>/type/<?=$row['id']?>/proId/<?=$_GET['id']?>'"></select><br />
											<?php if($rs['p_category'] == 5):?>
											<span class="attrDiy"><label><input name="isdiy"
													type="checkbox" id="isdiy" value="1"
													onclick="showCheckAttr(this,'ccxy','<?=$row['id']?>');"><font
													color="#FF0000">自定义</font></label><span id="ccxy"
												style="display: none; margin: 5px 0 0 10px;"> <input
													name="xx" type="text" id="xx" value="5"
													class="inputs easyui-numberbox"
													data-options="min:5,precision:0,required:false"> X <input
													name="yy" type="text" id="yy" value="5"
													class="inputs easyui-numberbox"
													data-options="min:5,precision:0,required:false">
											</span></span>
											<?php endif;?>
										<?php endif;?>
									 <?php }else if(strpos($row['a_name'],'纸张') !== false){?>
										<select id="<?=$row['id']?>" name="<?=$row['id']?>"
											class="easyui-combobox" style="width: 200px; height: 25px;"
											data-options="
													url:'<?=U('QuotedPrice/getAttrJsonData')?>/type/<?=$row['id']?>/proId/<?=$_GET['id']?>',
													required:false,
													editable:false,
													valueField:'id',
													textField:'m_name',
													panelHeight:'auto'<?php if($row['p_type'] == 1){?>,onSelect:change<?php }?>">
										</select>
									 <?php }else if(strpos($row['a_name'],'数量') !== false){?>
									 <input id="<?=$row['id']?>" name="<?=$row['id']?>"
											class="input_text easyui-numberbox"
											data-options="min:0,precision:0,required:false"
											style="width: 130px; height: 25px;" />&nbsp;<?=$rs['unit_name']?>
									<?php }else{?>
									 <select id="<?=$row['id']?>" name="<?=$row['id']?>"
											class="easyui-combobox" style="width: <?=strpos($row['a_name'],'每本页数') !== false ? "70px;" : "160px;"?>; height: 25px;"
											data-options="url:'<?=U('QuotedPrice/getAttrJsonData')?>/type/<?=$row['id']?>/proId/<?=$_GET['id']?>',
											required:false,
											editable:false,
											valueField:'id',
											textField:'a_name',panelHeight:'auto'"></select>
									<?php if(strpos($row['a_name'],'每本页数') !== false){?>
										<label><input name="isdiy_<?=$row['id']?>" type="checkbox"
												id="isdiy_<?=$row['id']?>" value="1"
												onclick="showCheckAttr(this,'numxy_<?=$row['id']?>','<?=$row['id']?>');"><font
												color="#FF0000">自定义</font> </label> <span
											id="numxy_<?=$row['id']?>"
											style="display: none; margin: 5px 0 0 10px;"> <input
												name="num_<?=$row['id']?>" type="text"
												id="num_<?=$row['id']?>" value="10"
												style="width: 40px; height: 25px;"
												class="inputs easyui-numberbox"
												data-options="min:10,precision:0,required:false">
										</span>
									<?php }?>
									<?php }?>
									</td>
									</tr>
								</table>
							</li>
							<?php if($row['p_type'] == 1 && strpos($row['a_name'],'纸张') !== false){?>
							<li class="attr">
								<table>
									<tr>
										<td class="label">克重</td>
										<td class="input"><select id="kz_<?=$row['id']?>"
											name="kz_<?=$row['id']?>" class="easyui-combobox kezhong"
											style="width: 120px; height: 25px;"
											data-options="required:false,editable:false,valueField:'id',textField:'m_name',panelHeight:'auto'"></select></td>
									</tr>
								</table>
							</li>
							<?php }?>
						<?php endforeach;?>
						</ul>
					</div>
					<div data-options="region:'center'" title="后加工工序"
						style="padding: 10px">
						<ul class="cont-attr">
						<?php foreach ($proArr as $row):?>
							<li class="process">
								<p>
									<span class="input">
										<?php
 $type = ""; $name = ""; $clic = 'onclick="showCheck(this'; $must = ""; $size = ""; $type = $row ['combo_name'] != "" ? "radio" : "checkbox"; $name = $row ['combo_name'] != "" ? $row ['combo_name'] : $row ['id']; $clic .= $row ['sizeDIY'] == 1 ? ",'diy_" . $row ['id'] . "'" : ",''"; $clic .= $row ['pp_process_attr'] != "" ? ",'pro_attr_" . $row ['id'] . "'" : ",''"; $clic .= $row ['numDIY'] == 1 ? ",'pro_nm_" . $row ['id'] . "'" : ",''"; $clic .= $row ['hasDesc'] == 1 ? ",'pro_desc_" . $row ['id'] . "'" : ",''"; $clic .= ');'; $must = $row ['ismust'] == 1 ? "checked='checked'" : ""; $clic .= $row ['ismust'] == 1 ? 'checkMust(this)"' : '"'; ?>
										<label> <!-- combo_name, sizeDIY, numDIY, ismust, --> <input
											type="<?=$type?>" name="pro_<?=$name?>" <?=$clic?>
											id="pro_<?=$row['id']?>" value="<?=$row['id']?>" <?=$must?> />
										<?=$row['p_name'];?></label>
									 <?php if($row['sizeDIY'] == 1){?>
										<span id="diy_<?=$row['id']?>" style="display: none;"> <input
											name="<?=$row['id']?>_x" type="text" id="uvx" size="4"
											class="input_text easyui-numberbox"
											data-options="min:0,precision:0" /> × <input
											name="<?=$row['id']?>_y" type="text" id="uvy" size="4"
											class="input_text easyui-numberbox"
											data-options="min:0,precision:0" /> mm
									</span>
										<?php }?>
									 <?php if($row['pp_process_attr'] != "" && strpos($row['p_name'],'打号码') === false){?>
										<input type="text" id="pro_attr_<?=$row['id']?>"
										name="pro_attr_<?=$row['id']?>" class="easyui-combobox"
										style="width: 120px; display: none;"
										data-options="required:false,editable:false,url:'<?=U('QuotedPrice/getProJsonData')?>/type/<?=$row['id']?>/proId/<?=$_GET['id']?>',valueField:'id',textField:'p_name',panelHeight:'auto',disabled:true" />
									 <?php }?>
									 <?php if($row['numDIY'] == 1){?><input
										id="pro_nm_<?=$row['id']?>" name="pro_nm_<?=$row['id']?>"
										class="input_text easyui-numberbox"
										data-options="min:0,precision:0,disabled:true"
										style="width: 50px;" />
									<?php }?>
									 <?php if($row['hasDesc'] == 1){?><input
										id="pro_desc_<?=$row['id']?>" name="pro_desc_<?=$row['id']?>"
										class="input_text" disabled="disabled" style="width: 50px;" />
									<?php }?>
									
									</span>
								</p>
							</li>
						<?php endforeach;?>	
						</ul>
					</div>
				</div>
			</div>
			<div class="page-contain">
				<table width="100%">
					<tr>
						<td align="center">&nbsp;</td>
						<td align="right" width="150"><a href="javascript:void(0);"
							onclick="javascript:submitForm();" plain="true"
							iconCls="icon-add" class="easyui-linkbutton">自助报价</a></td>
					</tr>
				</table>
			</div>
		</div>
	</form>
</body>
</html>