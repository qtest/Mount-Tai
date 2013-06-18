$(document).ready(function() {
	InitLeftMenu();
	tabClose();
	tabCloseEven();
})

// 初始化左侧
function InitLeftMenu() {

	/*
	 * $(".accordion").empty(); var menulist = "";
	 * 
	 * $.each(_menus.menus, function(i, n) { menulist += '<div title="' +
	 * n.menuname + '" icon="' + n.icon + '" style="overflow:auto;">'; menulist += '<ul>';
	 * $.each(n.menus, function(j, o) { menulist += '<li><div><a link="' +
	 * o.url + '" ><span class="icon ' + o.icon + '" ></span>' + o.menuname + '</a></div></li> '; })
	 * menulist += '</ul></div>'; })
	 * 
	 * data-options="url:'<?=U('Report/getJsonData')?>',checkbox:false,animate:true,
	 * onLoadError:function(){ parent.Maya.Msg('基层单位加载失败，请稍后再试。',null,null,800); },
	 * onClick: function (node) { addTab(node.text, '<?=U("Report/dashboardView/")?>/id/'+node.id); }"
	 * 
	 */

	// $(".accordion").append(menulist);
	$('.easyui-accordion li a').click(function() {
		var tabTitle = $(this).text();
		var url = $(this).attr("link");
		addTab(tabTitle, url);
		$('.easyui-accordion li div').removeClass("selected");
		$(this).parent().addClass("selected");

	}).hover(function() {
		$(this).parent().addClass("hover");
	}, function() {
		$(this).parent().removeClass("hover");
	});
}

function addTab(title, url) {
	var _tabPanel = $("#tabs");
	if (!_tabPanel.tabs('exists', title)) {
		_tabPanel.tabs(
						'add',
						{
							title : title,
							content : '<iframe scrolling="auto" frameborder="0" src="'
									+ url
									+ '" style="padding:0;margin:0;border:0;width:100%;height:100%;"></iframe>',
							//href : url,
							closable : true
						});
		tabClose();
	} else {
		_tabPanel.tabs('select', title);

	}
}

function tabClose() {
	/* 双击关闭TAB选项卡 */
	$(".tabs-inner").dblclick(function() {
		var subtitle = $(this).children("span").text();

		$('#tabs').tabs('close', subtitle);
	})

	$(".tabs-inner").bind('contextmenu', function(e) {
		// console.info(e.currentTarget);
		$('#mm').menu('show', {
			left : e.pageX,
			top : e.pageY
		});
		var subtitle = $(this).children("span").text();
		$('#mm').data("currtab", subtitle);

		return false;
	});
}
// 绑定右键菜单事件
function tabCloseEven() {
	// 关闭当前
	$('#mm-tabclose').click(function() {
		var currtab_title = $('#mm').data("currtab");
		$('#tabs').tabs('close', currtab_title);
	})
	// 全部关闭
	$('#mm-tabcloseall').click(function() {
		$('.tabs-inner span').each(function(i, n) {
			var t = $(n).text();
			$('#tabs').tabs('close', t);
		});
	});
	// 关闭除当前之外的TAB
	$('#mm-tabcloseother').click(function() {
		var currtab_title = $('#mm').data("currtab");
		$('.tabs-inner span').each(function(i, n) {
			var t = $(n).text();
			if (t != currtab_title)
				$('#tabs').tabs('close', t);
		});
	});
	// 关闭当前右侧的TAB
	$('#mm-tabcloseright').click(function() {
		var nextall = $('.tabs-selected').nextAll();
		if (nextall.length == 0) {
			// msgShow('系统提示','后边没有啦~~','error');
			// alert('后边没有啦~~');
			return false;
		}
		nextall.each(function(i, n) {
			var t = $('a:eq(0) span', $(n)).text();
			$('#tabs').tabs('close', t);
		});
		return false;
	});
	// 关闭当前左侧的TAB
	$('#mm-tabcloseleft').click(function() {
		var prevall = $('.tabs-selected').prevAll();
		if (prevall.length == 0) {
			// alert('到头了，前边没有啦~~');
			return false;
		}
		prevall.each(function(i, n) {
			var t = $('a:eq(0) span', $(n)).text();
			$('#tabs').tabs('close', t);
		});
		return false;
	});

	// 退出
	$("#mm-fresh").click(function() {
		// $('#mm').menu('hide');
		var currtab_title = $('#mm').data("currtab");
		var _ctab = $('#tabs').tabs('getTab', currtab_title);
		var html = _ctab.html();
		_ctab.html(html);

	})
}