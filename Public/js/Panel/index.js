layui.use(['element'], function(){
  	var element = layui.element();
  	var $pro = $('.ongoing-projects'),
	$length = $('.ongoing-projects li').length;
  	
	if($length == 3) {
		$pro.removeClass('col, col2');
	} else if($length == 2) {
		$pro.addClass('col2').removeClass('col');
	} else if ($length == 1) {
		$pro.addClass('col').removeClass('col2');
	}
	
	$.ajax({
		type: 'post',
		url: $('#report').data('url'),
		data: {},
		dataType: 'json',
		success: function(data) {
			$('#undone').html(data.undone);
			$('#today_undone').html(data.today_undone);
			$('#last_done').html(data.last_done);
			$('#today_done').html(data.today_done);
			$('#last_mysend').html(data.last_mysend);
			
			showGraph.line(graphdiv, data.graph);
		}
	});
});