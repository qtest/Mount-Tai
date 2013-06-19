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

<link href="__PUBLIC__/Plugin/slider/css/slider.css" rel="stylesheet"
	type="text/css" />
<script type="text/javascript"
	src="__PUBLIC__/Plugin/slider/js/slider.js"></script>
<script type="text/javascript">
<!--
jQuery(document).ready(function($)
{
	/*$('.flexslider').flexslider({
        animation: "slide",
        slideshow: true,                //Boolean: Animate slider automatically
        slideshowSpeed: 4000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
        animationSpeed: 600
      });*/
});
//-->
</script>
<style>
<!--
#centerContent {
	clear: both;
	line-height: 2;
	margin: 0 auto;
	padding: 0px 0 0 0;
	position: relative;
	width: 940px;
	min-height: 420px;
}

#contact {
	clear: both;
	border-top: 1px solid #DDDDDD;
	width: 820px;
	height: 160px;
	margin: 0 auto;
	padding-left: 20px;
}

.section {
	clear: both;
	content: ".";
	display: block;
	margin: 20px 0;
	height: 320px;
	border-bottom: 1px dashed #DDDDDD;
}
.section img{
	width:480px;
	-webkit-box-shadow: 0px 1px 3px rgba(0,0,0,.5);
	-moz-box-shadow: 0px 1px 3px rgba(0,0,0,.5);
	box-shadow: 0px 1px 3px rgba(0,0,0,.5);
}
img.left {
	position: relative;
	float: left;
}

div.right {
	position: relative;
	text-align: center;
	float: right;
	width: 300px;
	margin: 80px 100px 0 0;
}

img.right {
	position: relative;
	float: right;
}

div.left {
	position: relative;
	text-align: center;
	float: left;
	width: 360px;
	margin: 100px 100px 0 0;
}

.section p {
	text-indent: 2em;
}

.section p.title {
	text-shadow: 0 1px 0 #dedede;
	text-indent: 2em;
	margin: 0px;
	font-size: 16px;
}

.section p.sub {
	margin: 0px;
	color: #888888;
	text-shadow: 0 1px 0 #FFFFFF;
	font-size: 15px;
}

.section ul {
	
}

.section ul li {
	color: #444;
	text-shadow: 0 1px 0 #FFFFFF;	
	line-height:30px;
	margin-left: 50px;
	list-style-type: circle;
}
-->
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
<div class="navigator">
	<div id="wrap">
		<div id="centerContent">
			<div id="content">
				<div class="section">
					<p class="title">“7788印刷报价软件”是专为印刷生产厂家量身定做的一款自动化ERP印刷软件。可以释放您的双手去做更有价值的事情；<strong>不会再</strong>因为手机占线而丢失新客户；<strong>也不会</strong>因为手工报价存在一定误差或再次报价时与之前报价不符而造成客诉；<strong>更不会</strong>因为做帐中出现遗漏订单而带来的损失。</p>
					<p>这一切就让“7788印刷报价软件”为您去做吧，让您的办公变得轻松自如、更加专业，从而获得更大的市场份额。</p>
					<ul type="circle">
						<li>您是否为每天都需要面对<strong>大量又枯燥</strong>的印刷报价而烦恼呢？</li>
						<li>您在计算过程中是否容易出现<strong>报价错误</strong>呢？</li>
						<li>您是否会因为固定成本的变化而去<strong>翻开账本去查找数据</strong>呢？</li>
						<li>您是否还在为<strong>业务范围的狭小</strong>而着急呢？</li>
						<li>未来的印刷将会向着<strong>电子商务</strong>时代而转型，您还能无动于衷吗？</li>
					</ul>
					<p class="title">美好的未来就在“7788印刷网”一步一步地为您实现。</p>
				</div>
				<div class="section">

					<div class="left">
						<p class="title">自助报价</p>
						<p class="sub">不用双手、不打电话也可为客户报价，将报价过程简化到极致。</p>
					</div>
					<img class="right" src="__PUBLIC__/Images/home/zzbj.jpg">
				</div>
				<div class="section">
					<img class="left" src="__PUBLIC__/Images/home/zxdd.jpg">
					<div class="right">
						<p class="title">在线订单</p>
						<p class="sub">全自动化办公，减轻您的负担。</p>
					</div>
				</div>
				<div class="section">
					<div class="left">
						<p class="title">单据打印</p>
						<p class="sub">全自动模式生成相关单据打印功能。</p>
					</div>
					<img class="right" src="__PUBLIC__/Images/home/djdy.jpg">
				</div>
				<div class="section">
					<img class="left" src="__PUBLIC__/Images/home/czmk.jpg">
					<div class="right">
						<p class="title">财务模块</p>
						<p class="sub">充值、做帐、收款等等再也不是问题。</p>
					</div>
				</div>
				<div class="section">
					<div class="left">
						<p class="title">仓储模块</p>
						<p class="sub">进销存，无需专业人员也可轻松搞定。</p>
					</div>
					<div class="right">
						<p class="title">个性应用</p>
						<p class="sub">
							公司网站建设，实现自动化、简单化、市场化转型；<br />个性定制，可根据客户需求定做其他应用程序
						</p>
					</div>
				</div>
				<!-- <div class="section">
					<img class="left" src="__PUBLIC__/Images/home/zzbj.jpg">
					<div class="right">
						<p class="title">个性应用</p>
						<p class="sub">
							公司网站建设，实现自动化、简单化、市场化转型；<br />个性定制，可根据客户需求定做其他应用程序
						</p>
					</div>
				</div> -->
			</div>
		</div>
	</div>
</div>
<div id="footer">
	Copyright © 2012 - <?php echo date("Y")?> <a href="javascript:void(0)"
		id="ysAbout">7788ysw.com</a> 保留所有权利
</div>
</body>
</html>
</body>
</html>