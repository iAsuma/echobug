$(function(){
	var $tree = $('.site-tree-mobile'),
	$icon = $('.site-tree-mobile i'),
	$side = $('.layui-side'),
	$body = $('.layui-body');
	var func = {
			showMenu : function(obj, type){
				if(type == 1){
					$side.addClass('hide');
					obj.addClass('blue');
					$icon.removeClass('icon-arrow-left');
					$icon.addClass('icon-arrow-right');
					$body.addClass('left0');
				}else{
					$side.removeClass('hide');
					obj.removeClass('blue');
					$icon.addClass('icon-arrow-left');
					$icon.removeClass('icon-arrow-right');
					$body.removeClass('left0');
				}
			}
	}
	
	if(document.cookie.indexOf('bugmini_menu=1') > -1){
		func.showMenu($tree, 1);
	}
	
	$tree.on('click', function() {
		if($(this).hasClass('blue')){
			document.cookie="bugmini_menu=0"; 
			func.showMenu($(this), 0);
		}else{
			document.cookie="bugmini_menu=1";
			func.showMenu($(this), 1);
		}
	})
})