// This is main JavaScript file to be used with Crystal Theme
// Developed by Arlind Nushi
var _clearer = null;

// Main Menu
var main_menu, 
	main_menu_items, 
	active_marker, 
	active_item, 
	am_motion_duration, 
	am_easing_type;

jQuery(function($)
{
	_clearer = $('<div />').addClass('clear');
	
	// Main Menu Active Marker
	am_motion_duration = 450; // ms
	am_easing_type = 'easeOutBack';
	
	main_menu = $(".main_menu");
	main_menu_items = main_menu.find('> li');
	//active_marker = $('<div />').addClass('active_marker');
	active_item = main_menu.find('> li.active');
		
	//main_menu.append(active_marker);
	
	setTimeout(function()
	{
		setupMainMenu();
		
	}, 200);
});


function setupMainMenu(animate_new_position)
{
	// Register Positions
	var mpos_left = 0;
	var marker_extra_width = 20;
	var mew_half = parseInt(marker_extra_width/2, 10);
	
	var zindexer = 35;
	
	main_menu_items.each(function()
	{
		var $this = $(this);
		var a_href = $this.find('> a');
				
		var sub_menu = $this.find('> ul');
		var has_sub = sub_menu.length > 0;
		
		if(has_sub)
		{
			a_href.addClass('has_sub');
			$this.data('disable_animation', 1);
					
			// Show Menu Sub
			$this.on('mouseenter', function()
			{
				$this.css({zIndex: zindexer});
				sub_menu.stop().css({zIndex: zindexer}).fadeTo(300, 1);
				zindexer++
			});
			
			$this.on('mouseleave', function()
			{	
				$this.css({zIndex: 25});
				
				sub_menu.stop().css({zIndex: 10}).fadeTo(300, 0, function()
				{
					sub_menu.hide();
				});
			});
			
			var third_level_sub = sub_menu.find('ul');
			
			if(third_level_sub.length > 0)
			{
				third_level_sub.hide();
				
				third_level_sub.parent().on('mouseenter', function()
				{
					third_level_sub.stop().css({zIndex: 11}).fadeTo(300, 1);
				});
				
				third_level_sub.parent().on('mouseleave', function()
				{
					third_level_sub.stop().css({zIndex: 10}).fadeTo(300, 0, function()
					{
						third_level_sub.hide();
					});
				});
			}
		}
		
		var width = $this.width();
		var left_margin = parseInt($this.css('margin-left'), 10);
		
		
		//var marker_padding_left = parseInt(active_marker.css('padding-left'), 10);
		//var marker_padding_right = parseInt(active_marker.css('padding-right'), 10);
		
		//var marker_minus_padding = parseInt((marker_padding_left+marker_padding_right)/2, 10);
		
		
		var to_the_right = mpos_left + left_margin - mew_half;
				
		
		$this.data('left', to_the_right);
		$this.data('width', width + marker_extra_width);
		
		mpos_left += $this.outerWidth() + left_margin;
		
		// Bind Events
		var _width = $this.data('width');
		var _left = $this.data('left');
		
		var def_width = active_item.data('width');
		var def_left = active_item.data('left');
		
		//$this.on('hover', function()
		//{
			//if($this.data('disable_animation')) return;
			
		//	active_marker.stop().animate({left: _left, width: _width}, {duration: am_motion_duration, easing: am_easing_type});
		//});
		
		//main_menu.on('mouseleave', function()
		//{
		//	active_marker.stop().animate({left: def_left, width: def_width}, {duration: am_motion_duration, easing: am_easing_type});
		//});
	});
	
	
	// Fluid Active Menu Item Marker (Setup)
	if(active_item.length > 0)
	{		
		var def_width = active_item.data('width');
		var def_left = active_item.data('left');
		
		//if(animate_new_position)
		//{
		//	active_marker.animate({left: def_left, width: def_width}, {duration: am_motion_duration, easing: am_easing_type});
		//}
		//else
		//{
		//	active_marker.css({left: def_left, width: def_width}).show();
		//}
	}
	
	
}
