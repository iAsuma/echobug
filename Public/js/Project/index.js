layui.use(['element', 'form','layer','laypage','asuma'], function(){
  	var laypage = layui.laypage, asuma=layui.asuma;
  	var curr = location.hash.replace('#!list=', '');
  	var func = {
  			displayList: function(curr){
  				var load = layer.load(1);
  				$.post($('#tablecontent').data('url'), $('#formcondition').serialize(), function(data){
  					$('#tablecontent').html(data);
  					var pagecount = $('.layui-table').data('page');
  					layer.close(load);
  					asuma.laypage(laypage, pagecount, curr, function(param){
  						$('#currpage').val(param);
  						func.displayList(param);
  					});
  				});
  			}
  	}
  	
  	if(curr) $('#currpage').val(curr); 
  	func.displayList(curr);
  	
  	$('#searchBtn').on('click', function(){
  		location.hash="!list=1";
  		$('#currpage').val(1);
  		func.displayList();
  	});
  	
  	asuma.tabletips();
  	
  	$(document).on('click', '.setting',function() {
		var id = $(this).parents('tr').data('id');
		$.post($('#common_p').data('urla'), {id:id}, function(data){
			layer.open({
				content: data
				,type: 1
				,title: false
				,closeBtn: false
				,area: '518px'
				,success :function(){
					var form = layui.form();
					form.render('checkbox');
					
					form.on('checkbox(porc)', function(data){
						if(data.elem.checked == true){
							$('.project-settings').find('input').prop('disabled', true);
							$(this).prop('disabled', false);
							$(this).siblings('input').prop('checked', false);
							$('.switch-checkbox input').prop('checked', false);
							$('.step span').removeClass('light-blue');
						}else{
							$('.project-settings').find('input').prop('disabled', false);
						}
						form.render('checkbox');
					});
					
					form.on('switch(stepchange)', function(data){
						if(data.elem.checked == true){
							$('.step').find('span:eq('+data.value+')').addClass('light-blue');	
						}else{
							$('.step').find('span:eq('+data.value+')').removeClass('light-blue');
						}
					});
					
					form.on('submit(saveBtn)', function(data){
				  		$.post($(this).data('url'), data.field, function(data){
				  			if(data == 1){
				  				layer.msg('设置成功');
				  				func.displayList($('#currpage').val());
				  			}else if(data == -3){
				  				layer.msg('项目信息错误，或权限不够', {icon: 2});
				  			}else if(data == -1){
				  				layer.msg('操作失败');
				  			}else if(data == -4){
				  				layer.msg('无操作');
				  			}else{
				  				layer.msg('操作异常');
				  			}
				  		});
				  	});
					
					form.on('submit(cancelBtn)', function(data){
						layer.close(setbox);
				  	});
				}
			});
		})
	});
  	
  	$(document).on('click', '.delete_btn', function(){
  		var id = $(this).parents('tr').data('id');
  		asuma.confirm('确定删除该项目？', function(index){
  			$.post($('#common_p').data('urlb'), {id:id}, function(data){
  				if(data == 1){
	  				layer.msg('删除成功');
	  				func.displayList($('#currpage').val());
	  				layer.close(index);
	  			}else if(data == 0){
	  				layer.msg('项目不存在');
	  			}else if(data == -1){
	  				layer.msg('操作失败');
	  			}else{
	  				layer.msg('操作异常');
	  			}
  			});
  		});
  	});
});