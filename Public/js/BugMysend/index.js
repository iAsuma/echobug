layui.use(['element','laydate', 'form','laypage','asuma'], function(){
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
  	
  	if(curr) $('#currpage').val(curr); 
  	func.displayList(curr);
  	
  	$('#searchBtn').on('click', function(){
  		location.hash="!list=1";
  		$('#currpage').val(1);
  		func.displayList();
  	});
  	
  	asuma.tabletips();
  	asuma.marktips();
  	
  	$(document).on('click', '.change_btn',function() {
		var id = $(this).parents('tr').data('id');
		$.post($('#common_b').data('urla'), {id:id}, function(data){
			layer.open({
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
				  				layer.msg('移交成功');
				  				func.displayList($('#currpage').val());
				  			}else if(data == '0'){
				  				layer.msg('BUG不存在', {icon: 2});
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
  	
  	$(document).on('click', '.delete_btn', function(){
  		var id = $(this).parents('tr').data('id');
  		asuma.confirm('确定删除该Bug（'+id+'）？', function(index){
  			$.post($('#common_b').data('urlb'), {id:id}, function(data){
  				if(data == 1){
	  				layer.msg('删除成功');
	  				func.displayList($('#currpage').val());
	  				layer.close(index);
	  			}else if(data == 0){
	  				layer.msg('Bug信息错误');
	  			}else if(data == -1){
	  				layer.msg('操作失败');
	  			}else{
	  				layer.msg('操作异常');
	  			}
  			});
  		});
  	});
});