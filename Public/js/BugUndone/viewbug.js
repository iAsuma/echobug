layui.use(['element', 'form','asuma'], function(){
	var form = layui.form(),asuma=layui.asuma,
	func = {
		url: function(){
			var url = $('#common').data('reload');
			var ihash = $('#common').data('hash');
			if(ihash){
				url += "#!list="+ihash;
			}
			return url;
		}
	};
	
	$('.field-content img').on('click', function(){
		layer.photos({
			photos: $('.field-content'),
			anim: 0,
			shade: 0.4
		});
  	});
	
	$('.returnBtn').on('click', function(){
		var url = func.url();
		window.location.href = url;
	});
	
	$(document).on('click', '.closeBtn',function(){
		var obj = $(this);
  		var id = $('#bugid').val();
  		layer.prompt({
  			formType:2,
  			title:'请填写关闭BUG（'+id+'）的理由',
  			closeBtn: false, 
  			btnAlign: 'c',
  			maxlength:200
			},
			function(value, index){
	  			$.post(obj.data('urla'),{id:id, notes: value}, function(data){
	  				if(data == 1){
	  					layer.msg('关闭成功');
	  					window.location.href = func.url();
	  					layer.close(index);
	  				}else if(data == 0){
		  				layer.msg('Bug不存在', {icon: 2});
		  			}else if(data == -1){
		  				layer.msg('操作失败');
		  			}else{
		  				layer.msg('操作异常');
		  			}
	  			});
			});
  	});
	
	form.on('switch(changeStatus)', function(data){
  		var subObj = $(this);
  		if(data.elem.checked == true){
  			$.post($('#common').data('url'), {id:$('#bugid').val(),status:data.value}, function(code){
  				form.render('checkbox');
	  			if(code == 1){
	  				if(data.value == 1){
	  					window.location.href = func.url();
	  				}else{
	  					window.location.reload();	
	  				}
	  				
	  			}else if(code == '0'){
	  				layer.msg('BUG不存在', {icon: 2});
	  			}else if(code == '-1'){
	  				layer.msg('操作失败');
	  			}else{
	  				layer.msg('操作异常');
	  			}
	  		});
  		}
		
  	});
});
