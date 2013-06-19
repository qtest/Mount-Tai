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
	/**/$('.flexslider').flexslider({
        animation: "slide",
        slideshow: true,                //Boolean: Animate slider automatically
        slideshowSpeed: 4000,           //Integer: Set the speed of the slideshow cycling, in milliseconds
        animationSpeed: 600
      });
});
//-->
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
	<div class="slider" style="padding: 0 10px;height: 430px;">
		<!--  background: url(__PUBLIC__/Plugin/slider/images/ad_home.jpg);  --><div class="flexslider">
			<ul class="slides">
				<li>
					<div class="img">
						<img src="__PUBLIC__/Plugin/slider/images/slide_1.jpg" alt="" />
					</div>
				</li>
				<li>
					<div class="img">
						<img src="__PUBLIC__/Plugin/slider/images/slide_2.jpg" alt="" />
					</div>
				</li>
				<li>
					<div class="img">
						<img src="__PUBLIC__/Plugin/slider/images/slide_3.jpg" alt="" />
					</div>
				</li>
			</ul>
		</div>
	</div>
	<div class="clear"></div>
</div>
<div class="navigator">
	<div class="main_content">
		<div style="width: 880px; overflow: hidden; margin: 0 auto;">
			<!-- <div class="main_panel" style="width: 300px; float: left;">
				<div class="accordion-heading">
					<a class="accordion-toggle" data-toggle="collapse"
						data-parent="#accordion2" href="#collapse12"> <i class="icon-edit"></i>
						标题
					</a>
				</div>
				<div class="accordion-body">
					<div class="accordion-inner">
						<p></p>
					</div>
				</div>
			</div>
			<div class="main_panel index_panel">
				<div class="accordion-body">
					<div class="accordion-inner">
						<img src="__PUBLIC__/Plugin/slider/images/ad_auto.jpg" alt="" />
						<span class="span">简单</span>
					</div>
				</div>
			</div>
			<div class="main_panel index_panel">
				<div class="accordion-body">
					<div class="accordion-inner">
						<img src="__PUBLIC__/Plugin/slider/images/ad_auto.jpg" alt="" />
						<span class="span">实用</span>
					</div>
				</div>
			</div>
			<div class="main_panel index_panel" style=" margin: 0;">
				<div class="accordion-body">
					<div class="accordion-inner">
						<img src="__PUBLIC__/Plugin/slider/images/ad_auto.jpg" alt="" />
						<span class="span">高效</span>
					</div>
				</div>
			</div> -->
		</div>
		<div class="clear"></div>
	</div>
</div>
<div id="bottom">
	<div id="down">
		<a onfocus="this.blur()" href="#" target="_self"> 下载试用 </a>
	</div>
	<div id="mail">
		<div>
			<p>QQ：2490477760</p>
			<p>
				技术支持：<a href="mailto:support@7788ysw.com">support@7788ysw.com</a>
			</p>
		</div>
	</div>
	<div id="contact" style="float: right;">
		<a onfocus="this.blur()" href="#" target="_self"> 在线留言 </a>
	</div>
	<div class="clear"></div>
</div>
<div id="footer">
	Copyright © 2012 - <?php echo date("Y")?> <a href="javascript:void(0)"
		id="ysAbout">7788ysw.com</a> 保留所有权利
</div>
</body>
</html>