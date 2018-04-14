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
  	
  	asuma.tabletips();
  	asuma.marktips();
});