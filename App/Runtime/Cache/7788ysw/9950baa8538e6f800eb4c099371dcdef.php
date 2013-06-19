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
	//$('.bgloader').fadeOut(500);
});
function submitForm(){
	<?php if($_SESSION['cmp']): ?>
	//console.info($("#form").formSerialize());
	$.post(
			"<?=U('Center/QuotedPrice/stickerPrice')?>",
			$("#form").formSerialize(),
			function(data){
				if(data.status=="1"){
					Maya.Msg({
						type : "success",
						msg : data.info,
						call : function(){
							showResult(data.data);
						}
					});
				}else{
					Maya.Msg({
						msg : data.info,
						call : function(){
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
		url : "<?=U('Center/QuotedPrice/result')?>/id/"+id,
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

function upFile(id){
	new Maya.Box({
		text : "上传文件",
		url : "<?=U('QuotedPrice/upFile')?>/id/"+id,
		win : parent,
		width : 750,
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
function change(node){
	//console.info(node);return;
	$.get(
			"<?=U('Center/QuotedPrice/getKeZhong')?>",
			{id:node.id,proId:'<?=$_GET['id']?>'},
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

function showCheck(the,str,com,nm){
	if (the.checked){
		$("#"+str).css("display","inline");
		if(com != undefined && com != ''){
			$("#"+com).combobox('enable');
		}
		if(nm != undefined && nm != ''){
			$("#"+nm).numberbox('enable');
		}
	}else{
		$("#"+str).css("display","none");
		if(com != undefined && com != ''){
			$("#"+com).combobox('disable');
		}
		if(nm != undefined && nm != ''){
			$("#"+nm).numberbox('disable');
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
body{font-size:12px;}
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
	width: 290px;
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
	width: 220px;
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
<script type="text/javascript">
		$(document).ready(function(){
			$("#logo").css("height","61px");
			$('#myManey').tooltip({
				content: $('<div></div>'),
				onShow: function(){
					$(this).tooltip('arrow').css('left', 20);  
               		$(this).tooltip('tip').css('left', $(this).offset().left);  
		        },  
		        onUpdate: function(cc){  
		            cc.panel({  
		                width: 150,  
		                height: 'auto',  
		                border: false,  
		                href: '<?=U('Index/getMyManeyInfo') ?>'  
		            });  
		        } 
			});
        });
        <?php if($_SESSION['cmp']): ?>
        function myOrder(){
			new Maya.Box({
				text : "我的进行中订单",
				url : "<?=U('Index/getMyOrder')?>",
				overlayAlpha : .6,
				width : '98%',
				height : 600,
				btns : [{
						text : "  确定  ",
						isCancel : true
					}]
			});
		}
        function editPwd(){
        	new Maya.Box({
        		text : "修改密码",
        		url : "<?=U ( "Center/Manager/editPwd" )?>",
        		width : 330,
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
	    				text : "    取消    "	,
        				isCancel : true	
        			}
        		]
        	});	
        }
        function logoutClick(){
        	new Maya.Box({
        		text : "退出登录",
        		chtml : "确定要退出登录吗？",
        		isAlert : true,
        		iframeAuto : false,
        		overlayAlpha : .5,
        		iframeScroll : "auto",
        		inlineAuto : false,
        		type : "inline",
        		btns : [
        			{
        				text : "退出"	,
        				onClick : function(){
        					$.get(
                                    "<?=U ( "Center/Login/loginOut" )?>",
                                    function(data){
                                            if(data.data=="success"){
                                            	Maya.Msg({
                    	        	                type : "success",
                    	        	                msg : data.info,
                    	        	                sec : 1.5,
                    	        	                call : function(){
                    	        	                        window.location="<?=U ( "Index/index" )?>";
                    	        	                }
                    	        	       		 });
                                            }else{
                                                    
                                            }
                                    },
                                    "json"        
                            )
        				}
        			},
        			{
        				text : "取消"	,
        				isCancel : true
        			}
        		]
        	});	
        }
        function chongzhi(){
        	new Maya.Box({
        		text : "充值预付款",
        		url : "<?=U ( "Center/MyUser/charge" )?>",
        		width : 500,
        		height : 220,
        		btns : [
            		{
            			text : "    提交    ",
        				onClick : function(w){
        					w.getIframe().submitForm(w);
        					// _self.close();
        				}
        			},{
        				text     : "    取消    ",
        				isCancel : true	
        			}
        		]
        	});
        }
        <?php endif;?>
    </script>
<body>
	<!--  --><div id="top_header">
		<table border="0" align="center" cellpadding="0" cellspacing="0"
			style="margin: 0 auto;">
			<tr>
				<td>欢迎您进入 <strong>7788印刷网</strong></td>
				<td align="right" width="600">
					<?php if(isset($_SESSION ['cmp'])){?>
						你好，<?=$_SESSION ['cmp'] ['u_manager']?>[<a href="#" onclick="logoutClick()">退出</a>]
						<span class="hr">|</span>
						<a href="#" id="myManey" class="easyui-tooltip">账户余额&nbsp;[<span id="account"><?=round ($lastManey,2)?></span>]</a>
						[<a href="#" onclick="chongzhi()">充值</a>]
						<span class="hr">|</span>
						<a href="#" onclick="myOrder()">订单提醒<!-- &nbsp;[<span id="shopping-amount"><?=$myOrder?></span>] --></a>
						<span class="hr">|</span> <a href="#" onclick="editPwd()">修改密码</a>
						<span class="hr">|</span>
						<a href="<?php echo U("Center/Index/index");?>">后台管理</a>
					<?php }else{?>
						<a href="<?=U('Customer/login')?>">登录</a>
						<span class="hr">|</span>
						<a href="<?=U('Customer/register')."#register"?>">注册</a>
					<?php }?>
				</td>
			</tr>
		</table>
	</div>
	<div id="header">
		<table border="0">
			<tr>
				<td style="padding-left: 0px;width:500px;height:70px; font-size: 34px; color: #096177; font-family: '黑体';">
					<div style="width: 300px; height: 70px; background: url(__PUBLIC__/Images/logo.png) left center no-repeat;"></div>
				</td>
				<td align="right" valign="top">
					<!-- <div
						style="width: 310px; line-height: 29px; height: 29px; background-color: #096177; padding: 4px; _zoom: 1; overflow: hidden;">
						<div
							style="background-color: #fff; width: 240px; float: left; text-align: left">
							<input type="text"
								style="line-height: 29px; height: 29px; _height: 27px; padding: 0; margin: 0; border: 0px none; width: 100%; box-shadow: inset 3px 3px 0px rgba(0, 0, 0, .1); padding-left: 5px;" />
						</div>
						<div
							style="height: 29px; width: 60px; float: left; text-align: center; color: #fff; font-weight: bold; font-size: 14px; padding-left: 5px">搜索</div>
					</div> -->
				</td>
			</tr>
		</table>
	</div>
	<!--  -->
	<div class="menu_nav">
		<div class="navigator">
			<ul class="main_menu">
				<!-- <li class="active"><a href="index.html">印刷网<span>/ ysw</span></a></li>
				<li><a href="portfolio.html">解决方案<span>/ solutions</span></a>
					<ul>
						<li><a href="portfolio.html">印刷报价系统</a></li>
						<li><a href="portfolio.html">印刷<span>erp</span></a></li>
					</ul>
				</li>
				<li><a href="portfolio.html">产品服务<span>/ service</span></a>
					<ul>
						<li><a href="portfolio.html">印刷报价系统</a></li>
						<li><a href="portfolio.html">印刷<span>erp</span></a></li>
					</ul></li>
				<li><a href="portfolio.html">客户案例<span>/ customer</span></a></li>
				<li><a href="blog.html">新闻<span>/ news</span></a>
					<ul>
						<li><a href="index.html">行业新闻<span>/ news</span></a></li>
						<li><a href="index-wideslider.html">企业新闻<span>/ news</span></a></li>
					</ul></li>
				<li><a href="portfolio.html">关于<span>/ about</span></a>
					<ul>
						<li><a href="contact.html">关于我们<span>/ about</span></a></li>
						<li><a href="contact.html">联系我们<span>/ contact</span></a></li>
					</ul>
				</li> -->
				<li <?=MODULE_NAME == "Index" ? 'class="active"':''?>><a href="<?=U('Index/index')?>">印刷网<span>/ 7788ysw</span></a></li>
				<li <?=MODULE_NAME == "Solutions" ? 'class="active"':''?>><a href="<?=U('Solutions/index')?>">解决方案<span>/ solutions</span></a>
					<!-- <ul>系统相关<span>/ directions</span>
						<li><a href="portfolio.html">解决方案<span>/ solutions</span></a></li>
						<li><a href="portfolio.html">下载试用<span>/ download</span></a></li>
					</ul></li> -->
				<li <?=MODULE_NAME == "Support" ? 'class="active"':''?>><a href="<?=U('Support/question')?>">技术支持<span>/ support</span></a>
					<ul>
						<li><a href="<?=U('Support/question')?>">常见问题<span>/ Q&A</span></a></li>
						<li><a href="<?=U('Support/information')?>">印刷知识<span>/ information</span></a></li>
					</ul>
				</li>
				<!-- <li class="active"><a href="portfolio.html">客户案例<span>/ customer</span></a>
					<ul>
						<li><a href="portfolio.html">客户评价<span>/ comments</span></a></li>
						<li><a href="portfolio.html">成功案例<span>/ customer</span></a></li>
					</ul></li>
				<li><a href="blog.html">新闻<span>/ news</span></a>
					<ul>
						<li><a href="index.html">行业新闻<span>/ industry</span></a></li>
						<li><a href="index-wideslider.html">企业新闻<span>/ company</span></a></li>
					</ul></li>
				<li><a href="portfolio.html">关于<span>/ about</span></a>
					<ul>
						<li><a href="contact.html">关于我们<span>/ about</span></a></li>
						<li><a href="contact.html">联系我们<span>/ contact</span></a></li>
					</ul></li> -->
			</ul>
			<ul class="main_menu_right">
				<li><a href="<?=U('QuotedPrice/index')?>">我要报价</a></li>
			</ul>
		</div>
	</div>
<div style="width:90%;margin:0 auto;padding-top: 20px;*zoom: 1; position: relative;">
	<div class="main_panel" style="width:220px; position: absolute; left: 0; top: 20px;clear:both;">
		<div class="accordion-body">
			<div class="accordion-inner">
				<ul>
					<li class="header">
						<span>请选择报价项目</span>
					</li>
					<?php foreach($productArr as $row):?>
						<li <?=$_GET['id'] == $row['id'] ? 'class="active"' : ''?>>
							<div>
								<a href="<?=U('QuotedPrice/index').'/type/'.$row['p_type'].'/id/'.$row['id']?>"><span
									class="icon"></span><?=$row['p_name']?>自助报价</a>
							</div>
						</li>
					<?php endforeach;?>
				</ul>
			</div>
		</div>
	</div>
	<div class="main_panel" style="margin-left:240px;clear:both;">
		<div class="accordion-body">
			<div class="accordion-inner">
				<form id="form" name="form">
					<input type="hidden" name="id" id="id" value="<?=$_GET['id']?>" />
					<?php if(empty($_GET['id'])):?>
						<div style="height:400px;font-size:25px;"><br />
							<span id="span_zs" style="line-height:60px;">可自定义尺寸、数量、后加工，报价经专业运算而成</span>
							<img src="__PUBLIC__/Plugin/slider/images/slide_1.jpg" alt="" />
						</div>
					<?php else:?>
					<div id="form_add_point" class="content">
	<div class="easyui-panel" fit="true" title="" style="padding: 10px; background: #F9F9F9; height: 350px; overflow: hidden;">
		<div class="easyui-layout" data-options="fit:true">
			<div data-options="region:'north',split:true" style="height: 200px; padding: 10px">
				<ul class="cont-attr">
						<?php foreach ($attrArr as $row):?>
							<li class="attr">
						<table>
							<tr>
								<td class="label"
									<?=$row['p_type'] != 2 && strpos($row['a_name'],'尺寸') !== false ? 'valign="top"' : ""?>><?=strpos($row['a_name'],'尺寸') === false ? $row['a_name'] : $row['a_name']."(mm)"?></td>
								<td class="input">
									<?php if(strpos($row['a_name'],'尺寸') !== false){?>
										<?php if($row['p_type'] == 2):?>
										<input name="length" type="text"
									class="input_text easyui-numberbox"
									data-options="min:5,precision:0" id="length" value="5" size="5" />(长)
									X <input name="width" type="text"
									class="input_text easyui-numberbox"
									data-options="min:5,precision:0" id="width" value="5" size="5" />(宽)
										<?php else:?>
										<select id="<?=$row['id']?>" name="<?=$row['id']?>"
									class="easyui-combobox" style="width: 160px; height: 25px;"
									data-options="required:false,editable:false,valueField: 'id',textField: 's_name',panelHeight:'auto',url: '<?=U('QuotedPrice/getAttrJsonData')?>/type/<?=$row['id']?>/proId/<?=$_GET['id']?>'"></select><br />
									<span class="attrDiy"><label><input name="isdiy"
											type="checkbox" id="isdiy" value="1"
											onclick="showCheckAttr(this,'ccxy','<?=$row['id']?>');"><font
											color="#FF0000">自定义</font></label><span id="ccxy"
										style="display: none; margin: 5px 0 0 10px;"> <input name="xx"
											type="text" id="xx" value="5" class="inputs easyui-numberbox"
											data-options="min:5,precision:0,required:false"> X <input
											name="yy" type="text" id="yy" value="5"
											class="inputs easyui-numberbox"
											data-options="min:5,precision:0,required:false">
									</span></span>
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
 $type = ""; $name = ""; $clic = 'onclick="showCheck(this'; $must = ""; $size = ""; $type = $row ['combo_name'] != "" ? "radio" : "checkbox"; $name = $row ['combo_name'] != "" ? $row ['combo_name'] : $row ['id']; $clic .= $row ['sizeDIY'] == 1 ? ",'diy_" . $row ['id'] . "'" : ",''"; $clic .= $row ['pp_process_attr'] != "" ? ",'pro_attr_" . $row ['id'] . "'" : ",''"; $clic .= $row ['numDIY'] == 1 ? ",'pro_nm_" . $row ['id'] . "'" : ",''"; $clic .= ');'; $must = $row ['ismust'] == 1 ? "checked='checked'" : ""; $clic .= $row ['ismust'] == 1 ? 'checkMust(this)"' : '"'; ?>
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
					onclick="javascript:submitForm();" plain="true" iconCls="icon-add"
					class="easyui-linkbutton">自助报价</a></td>
			</tr>
		</table>
	</div>

					<?php endif;?>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="clear"></div>
<div id="footer">
	Copyright © 2012 - <?php echo date("Y")?> <a href="javascript:void(0)"
		id="ysAbout">7788ysw.com</a> 保留所有权利
</div>
</body>
</html>
</body>
</html>