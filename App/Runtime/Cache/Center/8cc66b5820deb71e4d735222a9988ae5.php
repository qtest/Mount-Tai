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

<script src="__PUBLIC__/js/index_load.js" type="text/javascript"></script>
<script language="javascript">
$(document).ready(function(){
	$('.bgloader').fadeOut(500);
});
</script>
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
		                href: '<?=U('Center/Index/getMyManeyInfo') ?>'  
		            });  
		        } 
			});
        });
		<?php if(isset($_SESSION ['cmp'])){?>
		function myOrder(){
			new Maya.Box({
				text : "我的进行中订单",
				url : "<?=U(MODULE_NAME.'/getMyOrder')?>",
				overlayAlpha : .6,
				width : '98%',
				height : 600,
				btns : [{
						text : "  确定  ",
						isCancel : true
					}]
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
        function editPwd(){
        	new Maya.Box({
        		text : "修改密码",
        		url : "<?=U ( "Manager/editPwd" )?>",
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
                                    "<?=U ( "Login/loginOut" )?>",
                                    function(data){
                                            if(data.data=="success"){
                                            	Maya.Msg({
                    	        	                type : "success",
                    	        	                msg : data.info,
                    	        	                sec : 1.5,
                    	        	                call : function(){
                    	        	                        window.location="<?=U ( "7788ysw/Index/index" )?>";
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
        		url : "<?=U ( "MyUser/charge" )?>",
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
        <?php }?>
    </script>
<body class="easyui-layout">
	<noscript>
		<div
			style="position: absolute; z-index: 100000; height: 2046px; top: 0px; left: 0px; width: 100%; background: white; text-align: center;">
			<img src="__PUBLIC__/images/noscript.gif" alt='抱歉，请开启脚本支持！' />
		</div>
	</noscript>
	<!-- top -->
	<div data-options="region:'north'" title="" border="false"
		style="height: 90px; overflow: hidden;">
		<div id="top">
			<div id="menu">
				<table style="width: 100%;" border="0" cellpadding="0"
					cellspacing="0">
					<tr>
						<td width="50"></td>
						<td align="left">
						<?php if(isset($_SESSION ['cmp'])){?>
							你好，<?=$_SESSION ['cmp'] ['u_manager']?>
							[<a href="#" onclick="logoutClick()">退出</a>] <span class="hr">|</span>
							<a href="<?php echo U("7788ysw/Index/index");?>">返回首页</a>
						<?php }else{?>
						<a href="#" onclick="login()">登录</a> <span class="hr">|</span> <a
							href="#" onclick="regist()">注册</a>
						<?php }?></td>
						<td width="50"></td>
						<td align="right">
					<?php if(isset($_SESSION ['cmp'])){?>
						<a href="#" id="myManey" class="easyui-tooltip">账户余额&nbsp;[<span id="account"><?=round ($lastManey,2)?></span>]</a>
						[<a href="#" onclick="chongzhi()">充值</a>] <span class="hr">|</span>
							<a href="#" onclick="myOrder()">订单提醒&nbsp;[<span
								id="shopping-amount"><?=$myOrder?></span>]
						</a> <span class="hr">|</span> <a href="#" onclick="editPwd()">修改密码</a>
					<?php }?></td>
						<td width="30"></td>
					</tr>
				</table>
			</div>
			<div id="logo"></div>
		</div>
	</div>
<div class="bgloader"></div>
<!-- left -->
<div data-options="region:'west'" border="true" title="菜单"  split="true"
	style="width: 170px; overflow: hidden;">
	<div class="easyui-accordion" data-options="fit:false,border:false" style="background: 0 !important; padding: 0px;">
	<div title="自助下单" iconcls="icon-sys" style="padding: 0px;">
		<ul>
			<?php foreach($proArr as $row):?>
				<li>
					<div>
						<a link="<?=U('QuotedPrice/index').'/type/'.$row['p_type'].'/id/'.$row['id']?>"><span
							class="icon icon-log"></span><?=$row['p_name']?>自助报价</a>
					</div>
				</li>
			<?php endforeach;?>
		</ul>
	</div>
	<?php if(isset($_SESSION ['cmp'])){?>
	<div title="报价及订单记录" iconcls="icon-sys" style="padding: 0px;">
		<ul>
			<li>
				<div>
					<a link="<?=U('DataList/offerList')?>"><span class="icon icon-log"></span>我的报价</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('DataList/orderList')?>"><span class="icon icon-log"></span>我的订单</a>
				</div>
			</li>
		</ul>
	</div>
	<div title="数据统计" iconcls="icon-sys" style="padding: 0px;">
		<ul>
			<!-- <li>
				<div>
					<a link="<?=U('Report/offerList')?>"><span class="icon icon-log"></span>报价统计概览</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Report/orderList')?>"><span class="icon icon-log"></span>订单统计概览</a>
				</div>
			</li> -->
			<li>
				<div>
					<a link="<?=U('Report/record')?>"><span class="icon icon-log"></span>资金变动记录</a>
				</div>
			</li>
		</ul>
	</div>
	<div title="个人信息管理" iconcls="icon-sys" style="padding: 0px;">
		<ul>
			<li>
				<div>
					<a link="<?=U('MyUser/index')?>"><span class="icon icon-log"></span>个人信息维护</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('MyUser/accountInfo')?>"><span class="icon icon-log"></span>账户信息</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('MyUser/recharge')?>"><span class="icon icon-log"></span>充值预付款</a>
				</div>
			</li>
		</ul>
	</div>
	<?php }?>
	<div title="实用工具" iconcls="icon-sys" style="padding: 0px;">
		<ul>
			<li>
				<div>
					<a link="<?=U('Tools/zhijia')?>"><span class="icon icon-log"></span>纸价吨令转换</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Tools/shujihoudu')?>"><span class="icon icon-log"></span>书籍厚度计算</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Tools/zhiban')?>"><span class="icon icon-log"></span>常用制版尺寸表</a>
				</div>
			</li>
		</ul>
	</div>
</div>
</div>
<!-- center 无锡市宸铭商务印刷有限公司-印刷报价系统-->
<div data-options="region:'center'" title="" border="true"  split="false"
	style="overflow: hidden;">
	<div id="tabs" class="easyui-tabs" data-options="fit:true,border:false,plain:true"
		style="overflow: hidden;">
		<div title="首页" id="home" data-options="closable:false" style="overflow: hidden;">
			<iframe  frameborder="0" allowtransparency="true" scrolling="no" style="width: 100%; height: 100%;" src="<?=U("Index/sysHome")?>"></iframe>
		</div>
	</div>
</div>
<?php if($_SESSION ['cmp']['g_manager'] == 1){?>
<div data-options="region:'east'" border="true" split="true" title="管理员菜单"
	style="width: 160px;overflow: hidden;">
	<script type="text/javascript">
		$(function(){
			$("#tt2").tree({
       			method:'GET',
                animate: true,
               // lines:true,
                url : "<?=U('Index/getJosnData4Index')?>",
				onLoadError:function(){
					parent.Maya.Msg("视频设备列表加载失败，请稍后再试。");
				},
				onBeforeExpand:function(node){
					$('#tt2').tree('options').url = "<?=U('Index/getJosnData4Index')?>/id/"+node.id+"/type/"+node.attributes;
					//alert("index.php?s=/System/treeJson/id/"+node.id+"/type/"+node.attributes);
				},
				onClick:function(node){
                	if(node.attributes == 'rcu' ){
                		addTab(node.text, "<?=U('Report/dashboardView/')?>/id/"+node.id);
                    }else{
                    	$(this).tree('toggle', node.target);
                    }
				}
            });
		});
</script>
<div class="easyui-accordion" data-options="fit:false,border:false"
	style="background: 0 !important; padding: 0px;">
	<div title="报价项目设置" iconCls="icon"
		style="padding: 0px;">
		<ul>
			<?php foreach($proArr as $row):?>
				<li>
					<div>
						<a link="<?=U('Prefer/index').'/id/'.$row['id']?>"><span
							class="icon icon-log"></span><?=$row['p_name']?>设置</a>
					</div>
				</li>
			<?php endforeach;?>
		</ul>
	</div>
	<div title="基础资料管理" iconCls="icon" style="padding: 0px;">
		<ul>
			<li>
				<div>
					<a link="<?=U('Product/index')?>"><span class="icon icon-log"></span>报价项目管理</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Paper/index')?>"><span class="icon icon-log"></span>纸张规格设置</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Machine/index')?>"><span class="icon icon-log"></span>通用印刷机设置</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Process/index')?>"><span class="icon icon-log"></span>后加工设置</a>
				</div>
			</li>
			<!-- <li>
				<div>
					<a link="<?=U('Product/paperLoss')?>"><span class="icon icon-log"></span>损耗设置</a>
				</div>
			</li> -->
			<li>
				<div>
					<a link="<?=U('Service/index')?>"><span class="icon icon-log"></span>账户设置</a>
				</div>
			</li>
		</ul>
	</div>
	<div title="会员管理" iconCls="icon" style="padding: 0px;">
		<ul>
			<li>
				<div>
					<a link="<?=U('Manager/index')?>"><span class="icon icon-log"></span>会员列表</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Manager/uGroup')?>"><span class="icon icon-log"></span>会员组设置</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Manager/chargeManey')?>"><span class="icon icon-log"></span>会员充值</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Manager/chartAdmin')?>"><span class="icon icon-log"></span>会员充值统计</a>
				</div>
			</li>
		</ul>
	</div>
	<div title="单据管理" iconCls="icon" style="padding: 0px;">
		<ul>
			<li>
				<div>
					<a link="<?=U('InvoiceManager/index')?>"><span class="icon icon-log"></span>订单审核</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('InvoiceManager/produceingLabelList')?>"><span class="icon icon-log"></span>加工单</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('InvoiceManager/makeDeliveryLabel')?>"><span class="icon icon-log"></span>送货单制单</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('InvoiceManager/deliveryList')?>"><span class="icon icon-log"></span>送货单列表</a>
				</div>
			</li>
		</ul>
	</div>
	<div title="查询统计" iconCls="icon" style="padding: 0px;">
		<ul>
			<li>
				<div>
					<a link="<?=U('Statistics/offerList')?>"><span class="icon icon-log"></span>报价记录</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Statistics/chartAdmin')?>"><span class="icon icon-log"></span>报价统计</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Statistics/orderList')?>"><span class="icon icon-log"></span>结单列表</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('Statistics/chartAdmin')?>"><span class="icon icon-log"></span>订单统计</a>
				</div>
			</li>
		</ul>
	</div>
	<div title="系统设置" iconCls="icon" style="padding: 0px;">
		<ul>
			<li>
				<div>
					<a link="<?=U('Manager/managerList')?>"><span
						class="icon icon-log"></span>系统用户管理</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('System/index')?>"><span class="icon icon-log"></span>系统参数管理</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('System/emailInfo')?>"><span class="icon icon-log"></span>邮件提醒设置</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('System/adInfo')?>"><span class="icon icon-log"></span>广告管理</a>
				</div>
			</li>
			<li>
				<div>
					<a link="<?=U('System/emailSend')?>"><span class="icon icon-log"></span>邮件发送</a>
				</div>
			</li><!--  -->
		</ul>
	</div>
</div>
</div>
<?php }?>
<!--  background: url(images/foot_bg.png) repeat-x; background: url(images/bg.jpg) repeat;-->

<div region="south" title="" split="false" border="false"
	style="height: 33px;/*background: url(__PUBLIC__/images/tabs-sprite.gif) repeat-x right -201px;*/">
	<div id="footer" style="text-align: center; line-height: 33px; color: #444;">
            版权所有 © 2012-<?=date('Y')?> 
            <a href="#"><?php echo C('MYCOMPANY');?></a>
</div>
<script type="text/javascript">
// onclick="about()"
function about(){
	new Maya.Box({
		text : "技术支持",
		skin: "default",
		url : "__PUBLIC__/About/about.html",
		win : parent,
		width : 600,
		height : 220,
		//iframeScroll : "no",
		btns : [
			{
				text : "    确定    ",
				onClick : function(w){
    				w.close();
				}	
			}
		]
	});
}
</script>
</div>
<div id="mm" class="easyui-menu" style="width: 150px;">
	<div id="mm-tabclose">关闭</div>
	<div id="mm-tabcloseall">全部关闭</div>
	<div id="mm-tabcloseother">除此之外全部关闭</div>
	<div class="menu-sep"></div>
	<div id="mm-tabcloseright">当前页右侧全部关闭</div>
	<div id="mm-tabcloseleft">当前页左侧全部关闭</div>
	<div class="menu-sep"></div>
	<div id="mm-fresh">刷新</div>
</div>
</body>
</html>