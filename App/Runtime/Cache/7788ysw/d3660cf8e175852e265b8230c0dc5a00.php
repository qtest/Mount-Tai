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

<script src="__PUBLIC__/js/initcity.js" type="text/javascript"></script>
<script language="javascript">
$(function(){
	pc_init();
	//按钮提交
    Maya.BindEvent(_("submit_btn"),"click",function(){
    	submitForm();	
	});
    //回车提交
    Maya.BindEvent(document.body,"keydown",function(e){
            //如果是回车键
            var e=event || e;
            if(e.keyCode==13){
            	submitForm();	
            }
    });
})
function submitForm(){
	Maya.Msg({
	    msg : "正在提交中，请稍后....",
	    sec : 1.5,
  	});
    $.post(
            "<?=U(MODULE_NAME.'/'.ACTION_NAME)?>",
            $("#form").formSerialize(),
            function(data){
                    if(data.data == "success"){
                            Maya.Msg({
                                    type : "success",
                                    msg : data.info,
                                    sec : 1.5,
                                    call : function(){
                                        top.location="<?=U("Index/index")?>";
                                    }
                            });
                    }else{
                            Maya.Msg({
                                    type : "fail",
                                    msg : data.info
                            });
                    }
            },
            "json"
    );
	return false;
}
</script>
<style type="text/css">
<!--
.cont-attr {
	margin: 0;
	width: 500px;
	padding: 0;
	list-style: none;
	display: block;
	zoom: 1;
	overflow: hidden;
	padding: 0;
}

.cont-attr .attr {
	overflow: hidden;
	height: 40px;
	border-bottom: none;
}

.cont-attr .attr p {
	overflow: hidden;
}

.cont-attr .desc {
	height: 25px;
}

.cont-attr .attr .label{
	padding: 0 15px 0 0;
	line-height: 35px;
	background-color: transparent;
	margin: 0 0 0 5px;
	width: 120px;
	text-align: right;
	float: left;
}

.cont-attr .attr .input{
	margin: 0 0 0 5px;
	line-height: 35px;
	text-align: left;
}

.cont-attr .desc .label  {
	line-height: 20px;
}

.cont-attr .desc .input{
	line-height: 20px;
}

.input_text {
	width: 300px;
	height: 30px;
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
<div class="navigator"
	style="margin: 0 auto; padding-top: 20px; *zoom: 1; position: relative;">
	<div class="main_panel" style="clear: both;">
		<div class="accordion-body">
			<div id="register">
				<div
					style="margin: 20px 0; border-bottom: 1px solid #dedede; font-size: 25px; text-align: center; padding: 0 260px 5px 0;">
					用户注册</div>
				<form id="form" name="form">
					<ul class="cont-attr" style="margin: 0 auto;">
						<li class="attr">
							<p>
								<span class="label"><span class="mustIn">*</span>&nbsp;用户名：</span>
								<span class="input"><input name="name" type="text"
									class="input_text easyui-validatebox" data-options="required:true,validType:'length[5,20]'" id="name" /> </span>
							</p>
						</li>
						<li class="attr desc">
							<p>
								<span class="label">&nbsp;</span>
								<span class="input">（5-20位，字母、数字、下划线）</span>
							</p>
						</li>
						<li class="attr">
								<span class="label"><span class="mustIn">*</span>&nbsp;密 码：</span>
								<span class="input"><input name="pwd" type="password"
									class="input_text easyui-validatebox" data-options="required:true,validType:'length[5,20]'" id="pwd" /> </span>
						</li>
						<li class="attr desc">
							<p>
								<span class="label">&nbsp;</span>
								<span class="input">（6-20位，字母、数字）</span>
							</p>
						</li>
						<li class="attr">
								<span class="label"><span class="mustIn">*</span>&nbsp;重复密码：</span>
								<span class="input"> <input name="new_pwd" type="password"
									class="input_text" id="new_pwd" />
								</span>
						</li>
						<li class="attr desc">
							<p>
								<span class="label">&nbsp;</span>
								<span class="input">（确保您记住密码。）</span>
							</p>
						</li>
						<li class="attr">
							<p>
								<span class="label"><span class="mustIn">*</span>&nbsp;公司名称:</span>
								<span class="input"> <input id="company" name="company"
									type="text" class="input_text" />
								</span>
							</p>
						</li>
						<li class="attr desc">
							<p>
								<span class="label">&nbsp;</span>
								<span class="input">（贵公司名称）</span>
							</p>
						</li>
						<li class="attr">
							<p>
								<span class="label">所在地区：</span> <span class="input">
								<select id="province" name="province"></select>
								<select id="city" name="city"></select>
								</span>
							</p>
						</li>
						<li class="attr">
							<p>
								<span class="label">公司电话：</span> <span class="input"><input
									name="tel" type="text" class="input_text" id="tel" /> </span>
							</p>
						</li>
						<li class="attr">
							<p>
								<span class="label"><span class="mustIn">*</span>&nbsp;联系人：</span>
								<span class="input"><input name="manager" type="text"
									class="input_text" id="manager" /> </span>
							</p>
						</li>
						<li class="attr desc">
							<p>
								<span class="label">&nbsp;</span>
								<span class="input">（联系人姓名）</span>
							</p>
						</li>
						<li class="attr">
							<p>
								<span class="label"><span class="mustIn">*</span>&nbsp;联系人电话：</span>
								<span class="input"><input name="phone" type="text"
									class="input_text" id="phone" /> </span>
							</p>
						</li>
						<li class="attr desc">
							<p>
								<span class="label">&nbsp;</span>
								<span class="input">（联系人电话）</span>
							</p>
						</li>
						<li class="attr">
							<p>
								<span class="label">公司地址：</span> <span class="input"><input
									name="addr" type="text" class="input_text" id="addr" /> </span>
							</p>
						</li>
						<li class="attr">
							<p>
								<span class="label"><span class="mustIn">*</span>&nbsp;常用邮箱：</span> <span class="input"><input
									name="email" type="text" class="input_text" id="email" /> </span>
							</p>
						</li>
					</ul>
					<div class="clear"></div>
					<div style="text-align: center; padding: 20px 0 20px 50px;">
						<!-- <label><input <?php echo ($check); ?> name="isremember" type="checkbox"
							id="isremember" value="true"> 记住密码</label> -->
						<a href="#" id="submit_btn" name="submit_btn" class="button"
							style="width: 120px;font-size: 16px; padding: 8px 0;">注&nbsp;&nbsp;册</a>
					</div>
				</form>
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