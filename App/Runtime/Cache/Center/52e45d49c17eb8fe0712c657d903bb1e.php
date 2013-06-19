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
	//$(".chosen").chosen();
	$('#btnedit').linkbutton('disable');
	$('#btndel').linkbutton('disable');	
	$('#btnUnsel').linkbutton('disable');
	initDataGrid("tbl","通用纸列表","<?=U('Paper/getAllPAPER4Json')?>","#dbTool");
	
});

function getSelected(num){
	var selected = $('#tbl').datagrid('getSelections');
	//console.info(selected[0].name);
	if(num != undefined){
		if(selected.length == 0){
			parent.Maya.Msg("请选择操作项【单击行即可】");
			return "";
		}
		if(selected.length > num){
			parent.Maya.Msg("所选数量超出操作限制。");
			return "";
		}
	}
	var id = "";
	for(var i = 0 ; i < selected.length;i++){
		id += selected[i].id;
		id += i+1 == selected.length ? "" : ","
	}
	return id;
}

function submitForm(type){
	var id = 0;
	var title = "添加";
	if(type == "edit"){
		var id = getSelected(1);
		if(!id){
			return;
		}
		title = "修改";
	}
	new Maya.Box({
		text : "通用纸"+title,
		url : "<?=U ( "Paper/actionCheckIndex" )?>/type/"+type+"/id/"+id,
		win : parent,
		width : 600,
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

function  del(){
	var selected = $('#tbl').datagrid('getSelections');
	//var id = getSelected();
	//if(!id){
	//	return;
	//}
	var id = "";
	var title = "";
	for(var i = 0 ; i < selected.length;i++){
		title += "<b>"+(i+1)+".</b>"+selected[i].m_name;
		title += i+1 == selected.length ? "" : ";&nbsp;&nbsp;&nbsp;";
		title += (i+1)%3 == 0 ? "<br />" : "";
		id += selected[i].id;
		id += i+1 == selected.length ? "" : ",";
	}
	new Maya.Box({
		text : "删除记录",
		chtml : "<b>确定要删除以下通用纸信息？</b><br />"+title+"",
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
							"<?=U("Paper/actionCheckIndex")?>",
							{
								type:'del',
								id:id
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
function updateAttr(){
	var id = getSelected(1);
	if(!id){
		return;
	}
	new Maya.Box({
		text : "通用纸规格设置",
		url : "<?=U ( "Paper/setAttr" )?>/id/"+id,
		win : parent,
		width : 720,
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
function formatStatus(val,row){
	if (val == 0){  
        return '<a href="#" title="停用" iconCls="icon-no" plain="true"><img src="__PUBLIC__/images/center/icons/cross_circle.png" /></a>';  //
    } else {  
        return '<a href="#" title="启用" iconCls="icon-no" plain="true"><img src="__PUBLIC__/images/center/icons/tick_circle.png" /></a>';    
    }  
}
</script>
<div id="dbTool">
		<a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="submitForm('add')">添加</a>
		<a href="#" id="btnedit" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="submitForm('edit')">修改</a>
		<a href="#" id="btndel" class="easyui-linkbutton" iconCls="icon-cancel" plain="true"  onclick="del()">删除</a>|
		<!-- <a href="#" class="easyui-linkbutton" iconCls="icon-outdepot" plain="true" >导出</a> -->
		<a href="#" id="btndel" class="easyui-linkbutton" iconCls="icon-perf" plain="true" onclick="updateAttr()">规格设置</a>
		<a href="#" id="btnUnsel" class="easyui-linkbutton" iconCls="icon-back" plain="true"  onclick="$('#tbl').datagrid('clearSelections')">取消选中</a>
		<span style="color: #FF0000; margin-left: 30px;">提示：单击行即可选中(或取消)。</span>
</div>
<div class="content">
	<!-- <div class="tag">筛选</div>
	<div class="tagc">
		<form id="form" name="form" >
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td height="30" align="right">纸名称：</td>
					<td><select name="paper_name" id="paper_name" class="easyui-combobox" panelHeight="200"
						style="width: 150px; margin-top: 5px;">
							<option value="">不限</option>
							<?php foreach ($paperSel as $key): ?>
								<option value="<?= $key['id'] ?>"
								<?php if($_GET['dep_name'] == $key['id']){echo 'selected="selected"';}?>><?= $key['name'] ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td><a href="#" class="easyui-linkbutton" iconCls="icon-search"
						onclick="javascript:filterData();">搜索</a>
					</td>
				</tr>
			</table>
		</form>
	</div> -->
	<table id="tbl">
		<thead>
			<tr>
				<th field="m_name" width="150" align="left"><strong>纸名称</strong></th>
				<th field="type_name" width="120" align="left"><strong>纸类型</strong></th>
				<th field="total" width="60" align="center"><strong>规格数</strong></th>
				<th field="m_price" width="100" align="center"><strong>正度单价(默认)</strong></th>
				<th field="m_dPrice" width="100" align="center"><strong>大度单价</strong></th>
				<th field="m_unit" width="40" align="center"><strong>单位</strong></th>
				<th field="m_status" width="40" align="center" formatter="formatStatus"><strong>状态</strong></th>
				<th field="m_desc" width="150" align="left"><strong>备注</strong></th>
			</tr>
		</thead>
	</table>
</div>
</body>
</html>