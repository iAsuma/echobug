layui.use(['flow'], function(){
  	var flow = layui.flow;
  	
  	flow.load({
	    elem: '#allbug' //流加载容器
	    ,isAuto: false
	    ,done: function(page, next){ //加载下一页
	      	$.ajax({
	    		type: 'post',
	    		url: $('#allbug').data('url'),
	    		data: {currpage : page},
	    		dataType: 'json',
	    		success: function(data) {
	    			next(data.content, page < data.pages); 
	    		}
	    	});
	    }
  	});
});