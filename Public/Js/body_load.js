function freshIframe() {
	var _ctab = top.$('#tabs').tabs('getSelected');
	var html = _ctab.html();
	//alert("sdvsdvsdvsdvsdvdsvsdv");
	// console.info(html);
	top.setTimeout(function() {
		// alert("dfdfb");
		// console.info("2");
		_ctab.html(html);
		// console.info("3");
	}, 300);
}
function freshShoppingAmount(url,num){
	if(num != undefined){
		top.$('#shopping-amount').html(num);
		return;
	}
	$.post(
			url,{},
			function(data){
				top.$('#shopping-amount').html(data);
			},
			"json"
	);
}

// 弹出信息窗口 title:标题 msgString:提示信息 msgType:信息类型 [error,info,question,warning]
function msgShow(title, msgString, msgType) {
	$.messager.alert(title, msgString, msgType);
}
//给DOM对象添加事件
function addEvent(o,e,f){
	try{
		_(o).attachEvent("on"+e,f);	
	}catch(eve){
		_(o).addEventListener(e,f,false);
	}
}
//设置主复选框的状态改变的时候 更新其子复选框的状态
function setCheckBox(main,child){
		addEvent(main,"click", function(e){
			var mbox;
			if(Maya.isIE()){
				mbox=e.srcElement;
			}else{
				mbox=e.currentTarget;
			}
			var mbox_checked=mbox.checked;
			var children=document.getElementsByName(child);
			var children_len=children.length;
			for(var i=0;i<children_len;i++){
				children[i].checked=mbox_checked;
				if(mbox_checked){
					children[i].parentNode.parentNode.style.backgroundColor="#efefef";
				}else{
					children[i].parentNode.parentNode.style.backgroundColor="";
				}
			}
		}
	);
	$("input[name="+child+"]").click(function(){
				if($(this).attr("checked")){
					$(this).parent().parent().css("backgroundColor","#efefef");
				}else{
					$(this).parent().parent().css("backgroundColor","");
				}
	});
}
//获取name为box的复选框值
function getCheckBoxValue(box,type){
	var value=new Array();
	var box=document.getElementsByName(box);
	var box_len=box.length;
	for(var i=0;i<box_len;i++){
		value.push(box[i].value);
	}
	if(type==undefined){
		return value.toString();
	}else{
		return value;
	}
}

function loginBtn(url) {
	new Maya.Box({
		text : "会员登录",
		win : parent,
		url : url,
		overlayAlpha : .7,
		width : 280,
		height : 220,
		btns : [ {
			text : "    确定    ",
			onClick : function(w) {
				w.getIframe().submitForm(w);
				// _self.close();
			}
		}, {
			text : "    取消    ",
			isCancel : true
		} ]
	});
}

function regisBtn(url) {
	new Maya.Box({
		text : "会员注册",
		win: parent,
		url : url,
		overlayAlpha : .6,
		width : 460,
		height : 220,
		btns : [ {
			text : "  提交  ",
			onClick : function(w) {
				w.getIframe().submitForm(w);
				// _self.close();
			}
		}, {
			text : "  取消  ",
			isCancel : true
		}]
	});
}

var oPage = {pageIndex:1,pageSize:10};
function initDataGrid(id,title,url,toolbar){
	if(toolbar == undefined){
		toolbar = null;
	}
	$('#'+id).datagrid({
		url:url,
		title: title,
		width: '100%',
		idField:'id',
		//fitColumns: true,
		nowrap:true,
		rownumbers:true,
		pagination:true,//分页控件  
		striped:true,
		loadMsg:"数据加载中...",
		/*columns:[[
			{field:'name',title:'姓名',width:60},
			{field:'name_index',title:'姓名索引',width:60,align:'center'},
			{field:'sex',title:'性别',width:40,align:'right'},
			{field:'worker_type_name',title:'工种',width:50,align:'right'},
			{field:'spec_worker_type_name',title:'特殊工种',width:80},
			{field:'department_name',title:'施工单位',width:150,align:'center'},
			{field:'team_name',title:'施工队',width:80,align:'center'},
			{field:'cons_card_code',title:'施工证卡码',width:60,align:'center'},
			{field:'expires_start',title:'有效期始',width:60,align:'center'},
			{field:'expires_end',title:'有效期终',width:60,align:'center'},
			{field:'result_theoretical',title:'理论成绩',width:40,align:'center'},
			{field:'result_actual',title:'实操成绩',width:40,align:'center'}
		]],*/
		onHeaderContextMenu: function(e, field){
			e.preventDefault();
			if (!$('#tmenu').length){
				createColumnMenu(id);
			}
			$('#tmenu').menu('show', {
				left:e.pageX,
				top:e.pageY
			});
		},
		onSelect:function(rowIndex, rowData){
			//console.info(rowData);
			$('#btnedit').linkbutton('enable');
			$('#btndel').linkbutton('enable');
			$('#btnUnsel').linkbutton('enable');
		},
		onUnselectAll:function(){
			$('#btnedit').linkbutton('disable');
			$('#btndel').linkbutton('disable');
			$('#btnUnsel').linkbutton('disable');
		},
		onUnselect:function(){
			//console.info($("#tbl").datagrid('getSelections').length);
			if($('#'+id).datagrid('getSelections').length == 0){
				$('#btnedit').linkbutton('disable');
				$('#btndel').linkbutton('disable');
				$('#btnUnsel').linkbutton('disable');
			}
		},
		rowStyler:function(index,row,css){
			/*if (row.listprice>80){
				return 'background-color:#6293BB;color:#fff;font-weight:bold;';
			}*/
		},
		onLoadError:function(){
			Maya.Msg({
	            type : "fail",
	            msg : "加载数据失败。"
	    	});
		},
		toolbar:toolbar
	});
	//设置分页控件  
	$('#'+id).datagrid('getPager').pagination({  
	    pageSize: 10,//每页显示的记录条数，默认为10  
	    pageList: [5,10,20,50,100],//可以设置每页记录条数的列表  
	    beforePageText: '第',//页数文本框前显示的汉字  
	    afterPageText: '页    共 {pages} 页',  
	    displayMsg: '当前显示 {from} - {to} 条记录   共 {total} 条记录',
	    onBeforeRefresh:function(pageNumber, pageSize){
	        $(this).pagination('loading');
	        //alert('pageNumber:'+pageNumber+',pageSize:'+pageSize);
	        $(this).pagination('loaded');
	     }
	    /*onBeforeRefresh:function(){ 
	        $(this).pagination('loading'); 
	        alert('before refresh'); 
	        $(this).pagination('loaded'); 
	    }*/ 
	}); 
}


//$("#tbl").datagrid("loadData",<?=json_encode($data)?>);
function createColumnMenu(id){
	var tmenu = $('<div id="tmenu" style="width:100px;"></div>').appendTo('body');
	var fields = $('#'+id).datagrid('getColumnFields');
	//console.info(fields);
	for(var i=0; i<fields.length; i++){
		var field = $('#'+id).datagrid('getColumnOption',fields[i]).title;//console.info(fields[i]);
		$('<div iconCls="icon-ok" id="'+ fields[i] +'" />').html(field).appendTo(tmenu);
	}
	tmenu.menu({
		onClick: function(item){
			if (item.iconCls=='icon-ok'){
				$('#'+id).datagrid('hideColumn', item.id);
				tmenu.menu('setIcon', {
					target: item.target,
					iconCls: 'icon-empty'
				});
			} else {
				$('#'+id).datagrid('showColumn', item.id);
				tmenu.menu('setIcon', {
					target: item.target,
					iconCls: 'icon-ok'
				});
			}
		}
	});
}
