layui.use(['form'], function(){
	var form = layui.form();
	
	$('.field-content img').on('click', function(){
		layer.photos({
			photos: $('.field-content'),
			anim: 0,
			shade: 0.4
		});
  	});
});