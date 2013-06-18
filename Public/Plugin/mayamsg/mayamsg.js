if(typeof(Maya)=="undefined"){
        var Maya=function(){}
}
Maya.Msg=function(o){
	
	var cfg={
	    msg : "提示",
	    type : "hits",
	    call : null,
	    sec : 2
	}
	if(typeof(o)=="string"){
		cfg.msg=o;
	}else{
		for(var p in o){
			cfg[p]=o[p];
		}
	}
	var bw=document.documentElement.clientWidth;
	var bh=document.documentElement.clientHeight;
	var sl=document.body.scrollLeft;
	var st=document.body.scrollTop;
	var end_left=parseInt(bw/2);
	var end_top=parseInt(bh/2);
	if(window._MayaMessage){
		clearTimeout(window._MayaMessage);
	}
	var o="MayaMessage";
	if(document.getElementById(o)){
		document.getElementById(o).style.display="block";
	}else{
		var ele=document.createElement("div");
		ele.id=o;
		document.body.appendChild(ele);
	}
	document.getElementById(o).innerHTML='<div id="MayaMessage_Panel" class="MayaMessage_Bg">'+
								'<div class="MayaMessage_Bg MayaMessage_'+cfg.type+'"></div>'+
								'<div class="MayaMessage_Font">'+cfg.msg+'</div>'+
								'<div class="MayaMessage_Bg MayaMessage_End"></div>'+
							'</div>';
	document.getElementById("MayaMessage_Panel").style.left=end_left+"px";
	document.getElementById("MayaMessage_Panel").style.top=end_top+"px";
	document.getElementById("MayaMessage_Panel").style.marginLeft=-parseInt(document.getElementById("MayaMessage_Panel").offsetWidth/2-19)+(document.body.scrollLeft || document.documentElement.scrollLeft)+"px";
	document.getElementById("MayaMessage_Panel").style.marginTop=-parseInt(document.getElementById("MayaMessage_Panel").offsetHeight/2)+(document.body.scrollTop || document.documentElement.scrollTop)+"px";
	window._MayaMessage=setTimeout(function(){
                document.getElementById(o).style.display='none';
                if(cfg.call!=null)
                        cfg.call();
        },cfg.sec*1000);
}