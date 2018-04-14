layui.use(['element','layer', 'form','laypage','asuma'], function(){
  	var laypage = layui.laypage,asuma=layui.asuma;
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
  	
  	func.displayList(curr);
  	
  	$(document).on('click', '.modify', function(){
  		var text = $(this).parents('tr').find('td:eq(0)').html();
  		var id = $(this).parents('tr').data('id');
  		layer.prompt({
	  			title:'修改部门名称', 
	  			//value: text,
	  			closeBtn: false, 
	  			btnAlign: 'c', 
	  			success:function(layero){
	  				//修复光标聚焦位置
	  				layero.find('input').val(text);
	  				layero.find('input').focus();
	  			}
  			}, 
  			function(value, index){
	  			$.post($('#common_d').data('urla'),{id:id, name: value}, function(data){
	  				if(data == 1){
	  					layer.msg('修改成功');
	  					func.displayList($('#currpage').val());
	  					layer.close(index);
	  				}else if(data == 0){
		  				layer.msg('部门不存在', {icon: 2});
		  			}else if(data == -1){
		  				layer.msg('操作失败');
		  			}else{
		  				layer.msg('操作异常');
		  			}
	  			});
	  		});
  	});
  	
  	$(document).on('click', '.delete_btn', function(){
  		var id = $(this).parents('tr').data('id');
  		asuma.confirm('部门删除后不可恢复，确定删除？', function(index){
  			$.post($('#common_d').data('urlb'), {id:id}, function(data){
  				if(data == 1){
	  				layer.msg('删除成功');
	  				func.displayList($('#currpage').val());
	  				layer.close(index);
	  			}else if(data == 0){
	  				layer.msg('部门不存在', {icon: 2});
	  			}else if(data == -1){
	  				layer.msg('操作失败');
	  			}else{
	  				layer.msg('操作异常');
	  			}
  			});
  		});
  	});
  	
	$(document).on('click', '.setting',function() {
		var id = $(this).parents('tr').data('id');
		$.post($('#common_d').data('url'), {id:id}, function(data){
			var setbox = layer.open({
				content: data
				,type: 1
				,title: false
				,closeBtn: false
				,success :function(){
					var form = layui.form();
					form.render('select');
					
					form.on('submit(saveBtn)', function(data){
				  		$.post($(this).data('url'), data.field, function(data){
				  			if(data == 1){
				  				layer.msg('修改成功');
				  				func.displayList($('#currpage').val());
				  				//layer.close(setbox);
				  			}else if(data == '0'){
				  				layer.msg('部门不存在', {icon: 2});
				  			}else if(data == '-1'){
				  				layer.msg('操作失败');
				  			}else{
				  				layer.msg('操作异常');
				  			}
				  		});
				  	});
				}
			});
		})
	});
});