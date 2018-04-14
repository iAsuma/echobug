layui.use(['element', 'form','laypage','asuma'], function(){
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
  	
  	$(document).on('click', '.closeBtn',function(){
  		var id = $(this).parents('tr').data('id');
  		layer.prompt({
  			formType:2,
  			title:'请填写关闭BUG（'+id+'）的理由',
  			closeBtn: false, 
  			btnAlign: 'c',
  			maxlength:200
			},
			function(value, index){
	  			$.post($('#common_b').data('urla'),{id:id, notes: value}, function(data){
	  				if(data == 1){
	  					layer.msg('关闭成功');
	  					func.displayList($('#currpage').val());
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
  	
  	$(document).on('click', '.change_btn',function() {
		var id = $(this).parents('tr').data('id');
		$.post($('#common_b').data('urlb'), {id:id}, function(data){
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
  	
  	asuma.tabletips();
  	asuma.marktips();
});