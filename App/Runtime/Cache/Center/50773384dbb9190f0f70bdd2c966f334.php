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
	background: #fff;
}
</style>
<script language="javascript">
$(document).ready(function(){
	$('#p_index').numberbox({min:1,precision:0});
	<?php if(isset($rs)):?>
		editStatus();
	<?php endif;?>
});
function editStatus(){
	$("#p_name").attr("value","<?=$rs['p_name']?>");
	//$("#p_category").attr("value","<?=$rs['p_category']?>");
	$("input[name='p_category'][value=<?=$rs['p_category']?>]").attr("checked",true); 
	//$("#p_type").attr("value","<?=$rs['p_type']?>");
	$("#p_unit").attr("value","<?=$rs['p_unit']?>");
	$("#p_index").attr("value","<?=$rs['p_index']?>");
	$("#p_status").attr("value","<?=$rs['p_status']?>");
	//$("input[name='p_status']").attr("checked",true); 
	$("#p_desc").attr("value","<?=$rs['p_desc']?>");
}

function submitForm(w){
	if(!checkWorkerForm()){
		return;
	}

	 $('#form').ajaxSubmit({
		url:"<?=U(MODULE_NAME.'/'.ACTION_NAME)?>",
		success:function(data){
			if(data.status=="1"){
				Maya.Msg({
					type : "success",
					msg : data.info,
					call : function(){
						//parent.location.reload();	
						w.close();
						//console.info("1111");
						freshIframe();
					}	
				});
			}else{
				Maya.Msg(data.info);	
			}
		},
		error:function(){
			Maya.Msg("操作失败！");	
		}
	});
	return;
}
function checkWorkerForm(){
	if($("#p_name").val()==""){
		Maya.Msg("<strong>报价项目名称</strong> 不能为空");
		return false;
	}
	if($("#p_index").val()==""){
		Maya.Msg("<strong>排序</strong> 不能为空");
		return false;
	}
	return true;
}
</script>
</head>
<body>
	<div>
		<form id="form" name="form" method="post"
			enctype="multipart/form-data">
			<input type="hidden" name="type" id="type" value="<?=$_GET['type']?>" />
			<input type="hidden" name="id" id="id" value="<?=$_GET['id']?>" />
			<div class="p10">
				<table border="0" cellpadding="0" cellspacing="0"
					style="margin: 5px;">
					<tr>
						<td width="100" height="30" align="right">报价项目名称：</td>
						<td colspan="3"><input type="text" name="p_name" id="p_name"
							class="input_text" style="width: 95%;" /></td>
					</tr>
					<tr>
						<!-- --> <td height="30" align="right">报价类别：</td>
						<td><label><input name="p_category" id="zb" type="radio" value="5"
								checked="checked" /> 专版</label> <label><input name="p_category"
								id="bz" type="radio" value="6" /> 标准</label></td>
						<td width="100" align="right">状态：</td>
						<td><select name="p_status" id="p_status" style="width: 120px;">
								<option value="1">启用</option>
								<option value="0">停用</option>
						</select></td>
					</tr>
					<tr>
						<td height="30" align="right">计量单位：</td>
						<td><select name="p_unit" id="p_unit" style="width: 120px;">
                          <?php foreach($unitArr as $value):?>
                            <option value="<?=$value['id']?>"><?=$value['u_name']?></option>
                          <?php endforeach;?>
                          </select></td>
						<td align="right">属性类别：</td>
						<td><select name="p_type" id="p_type" style="width: 120px;">
                          <?php foreach($typeArr as $value):?>
                            <option value="<?=$value['id']?>"><?=$value['t_name']?></option>
                          <?php endforeach;?>
                          </select></td>
					</tr>
					<tr>
						<td height="30" align="right">排序：</td>
						<td><input type="text" name="p_index" id="p_index"
							class="input_text" value="1" /></td>
						<td></td>
						<td></td>
					</tr>
					<tr>
						<td height="45" align="right">备注：</td>
						<td colspan="3"><textarea id="p_desc" name="p_desc"
								class="input_text" style="width: 95%; height: 40px;"></textarea></td>
					</tr>
					<tr>
						<td height="30" align="right">示例图片：</td>
						<td colspan="3"><input type="file" name="id_card_images"
							id="id_card_images" style="width: 150px;" class="input_text" /></td>
					</tr>
				</table>
			</div>
			<input type="hidden" name="id_card_images_id" id="id_card_images_id"
				value="<?=$rs['id_card_images_id']?>" /> <input type="hidden"
				name="avatar_images_id" id="avatar_images_id"
				value="<?=$rs['avatar_images_id']?>" />
		</form>
	</div>
</body>
</html>