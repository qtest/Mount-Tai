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
	initDataGrid("tbl","会员列表","<?=U('Manager/getDataGridData')?>","#dbTool");
	$('#choseStatus').change(function(){
		//console.info($(this));
		if($(this)[0].checked == true){
			filterData('sel');
		}else{
			filterData('');
		}
	});
});

function filterData(type){
	var g_name = $('#g_name').val();
	var u_name = $('#u_name').val();
	//p_name  cate_id type_id  process_id  status
	$.post(
			"<?=U('Manager/getDataGridData')?>",
			{
				type:type,
				g_name:g_name,
				u_name:u_name,
				page:oPage.pageIndex,
				rows:oPage.pageSize
			},
			function(data){
					$('#tbl').datagrid('getPager').pagination({
						onSelectPage : function(pPageIndex, pPageSize) {
							//改变oPage的参数值，用于下次查询传给数据层查询指定页码的数据
							oPage.pageIndex = pPageIndex;
							oPage.pageSize = pPageSize;
							//定义查询条件
							/*var queryCondition = {
									id:id,
									name_index:name_index,
									cons_card_num:cons_card_num,
									dep_name:dep_name,
									team_name:team_name
								};*/
							$.post(
									"<?=U('Manager/getDataGridData')?>",
									{
										type:type,
										g_name:g_name,
										u_name:u_name,
										status:status,
										page:oPage.pageIndex,
										rows:oPage.pageSize
									},
									function(data){
										$("#tbl").datagrid("loadData",data);
									},'json'
							);
							//异步获取数据到javascript对象，入参为查询条件和页码信息
							//var oData = getAjaxDate("orderManageBuz","qryWorkOrderPaged",queryCondition,oPage);
							//使用loadDate方法加载Dao层返回的数据
							//$('#tt').datagrid('loadData',{"total":oData.page.recordCount,"rows":oData.data});
							
						}
					});
					$("#tbl").datagrid("loadData",data);
			},
			"json"
		);
	return false;
}

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

function  delUser(){
	var id = getSelected(1);
	if(!id){
		return;
	}
	new Maya.Box({
		text : "删除记录",
		chtml : "确定要删除该会员吗？",
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
					$.get(
							"<?=U("Manager/userDel")?>",
							{id:id},
							function(data){
								if(data.status=="1"){
									Maya.Msg({
										type : "success",
										msg : data.info,
										call : function(){
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
			},
			{
				text : "取消"	,
				isCancel : true
			}
		],
		onClose:function(){$("#tbl").datagrid("reload");}
	});	
}
function checkUserInfo(){
	var id = getSelected(1);
	if(!id){
		return;
	}
	new Maya.Box({
		text : "会员信息审核",
		url : "<?=U ( "Manager/checkUserInfo" )?>/id/"+id,
		win : parent,
		width : 600,
		height : 220,
		btns : [
    		{
				text : "    审核通过    ",
				onClick : function(w){
					$.post(
							"<?=U("Manager/checkUserInfo")?>",
							{id:id,type:1},
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
									parent.Maya.Msg(data.info);	
								}
							},
							"json"
						);
				}	
			},
    		{
				text : "    审核不通过    ",
				onClick : function(w){
					$.post(
							"<?=U("Manager/checkUserInfo")?>",
							{id:id,type:0},
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
									parent.Maya.Msg(data.info);	
								}
							},
							"json"
						);
				}	
			},
			{
				text : "    取消    ",
				isCancel : true
			}
		],
		onClose:function(){freshIframe();}
	});
}

function userEdit(){
	var id = getSelected(1);
	if(!id){
		return;
	}
	new Maya.Box({
		text : "会员信息修改",
		url : "<?=U ( "Manager/userEdit" )?>/id/"+id,
		win : parent,
		width : 600,
		height : 220,
		btns : [
    		{
				text : "    确定    ",
				onClick : function(w){
					w.getIframe().submitForm(w);
					
				}	
			},
			{
				text : "    取消    ",
				isCancel : true
			}
		],
		onClose:function(){$("#tbl").datagrid("reload");}
	});
}
function userManey(){
	var id = getSelected(1);
	if(!id){
		return;
	}
	new Maya.Box({
		text : "会员账户",
		url : "<?=U ( "Manager/userManeyChange" )?>/id/"+id,
		win : parent,
		width : 600,
		height : 220,
		btns : [
    		{
				text : "    确定    ",
				onClick : function(w){
					w.getIframe().submitForm(w);
					
				}	
			},
			{
				text : "    取消    ",
				isCancel : true
			}
		],
		onClose:function(){$("#tbl").datagrid("reload");}
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
	<!-- <a href="#" class="easyui-linkbutton" iconCls="icon-add" plain="true" onclick="submitForm('add')">添加</a> -->
	<a href="#" id="btnedit" class="easyui-linkbutton" iconCls="icon-edit" plain="true" onclick="userEdit()">修改</a>
	<a href="#" id="btndel" class="easyui-linkbutton" iconCls="icon-cancel" plain="true" onclick="delUser()">删除</a>|
	<a href="#" id="btndel" class="easyui-linkbutton" iconCls="icon-checkOk" plain="true" onclick="checkUserInfo()">审核</a>
	<a href="#" id="btndel" class="easyui-linkbutton" iconCls="icon-checkOk" plain="true" onclick="userManey()">会员账户</a>
	<a href="#" id="btnUnsel" class="easyui-linkbutton" iconCls="icon-back" plain="true" onclick="$('#tbl').datagrid('clearSelections')">取消选中</a>
	<span style="color: #FF0000; margin-left: 30px;">提示：单击行即可选中(或取消)。</span>&nbsp;&nbsp;
	<label style="color: #1A77C9; margin-left: 30px;"><input type="checkbox" id="choseStatus" name="choseStatus" />&nbsp;待审核会员</label>
</div>
<div class="content">
	<div class="tag">筛选</div>
	<div class="tagc">
		<form id="form" name="form">
			<table border="0" cellpadding="0" cellspacing="0">
				<tr>
					<td width="100" height="30" align="right">用户组：</td>
					<td>
						<select class="chosen" name="g_name" id="g_name" 
						panelHeight="200" style="width: 150px; margin-top: 5px;">
							<option value="">不限</option>
							<?php foreach ($ug_select as $key): ?>
								<option value="<?= $key['id'] ?>"
								<?php if($_GET['g_name'] == $key['id']){echo 'selected="selected"';}?>><?= $key['g_name'] ?></option>
							<?php endforeach; ?>
						</select>	
					</td>
					<td width="100" height="30" align="right">用户名称：</td>
					<td>
						<select name="u_name" id="u_name" 
						panelHeight="200" style="width: 150px; margin-top: 5px;">
							<option value="">不限</option>
							<?php foreach ($userArr as $key): ?>
								<option value="<?= $key['id'] ?>"
								<?php if($_GET['u_name'] == $key['id']){echo 'selected="selected"';}?>><?= $key['u_name'] ?></option>
							<?php endforeach; ?>
						</select>	
					</td>
					<td width="80" align="right"></td>
					<td><a href="#" class="easyui-linkbutton" iconCls="icon-search"
						onclick="javascript:filterData('');">搜索</a></td>
				</tr>
			</table>
		</form>
	</div>
	<table id="tbl" data-options="singleSelect:true">
		<thead>
			<tr>
				<th field="u_name" width="120" align="left"><strong>会员名称</strong></th>
				<th field="g_name" align="left" width="70"><strong>会员组</strong></th>
				<th field="u_status" align="center" width="40" formatter="formatStatus"><strong>审核</strong></th>
				<th field="u_company" align="left" width="200"><strong>公司名称</strong></th>
				<th field="u_where" align="left" width="60"><strong>省/市</strong></th>
				<th field="u_manager" align="left" width="130"><strong>联系人</strong></th>
				<!-- <th field="u_qq" width="40" align="center"><strong>QQ</strong></th> -->
				<th field="u_maney" width="80" align="center"><strong>账户/可用</strong></th>
				<th field="total" align="center" width="80"><strong>报价数</strong></th>
				<th field="u_logTime" width="130" align="center" ><strong>登录日期</strong></th>
			</tr>
		</thead>
	</table>
</div>
</body>
</html>