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
  	
  	if(curr) $('#currpage').val(curr); 
  	func.displayList(curr);
  	
  	$('#searchBtn').on('click', function(){
  		location.hash="!list=1";
  		$('#currpage').val(1);
  		func.displayList();
  	});
  	
  	$(document).on('click', '.delete_btn', function(){
  		var id = $(this).parents('tr').data('id');
  		asuma.confirm('确定删除该成员？', function(index){
  			$.post($('#common_s').data('urlb'), {id:id}, function(data){
  				if(data == 1){
	  				layer.msg('删除成功');
	  				func.displayList($('#currpage').val());
	  				layer.close(index);
	  			}else if(data == 0){
	  				layer.msg('成员不存在');
	  			}else if(data == -1){
	  				layer.msg('操作失败');
	  			}else{
	  				layer.msg('操作异常');
	  			}
  			});
  		});
  	});
});