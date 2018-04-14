layui.define(function(exports){
	var asumaObj = {
			tabletips: function(){
				var index = '', msg = '';
				$(document).on('mouseover','.details-content', function(){
					msg = $(this).data('title');
					if(msg == '') return false;
					index = layer.tips(msg, $(this), {
					  tips: [1, '#08c'],
					  time: 0
					});
			  	})
			  	
			  	$(document).on('mouseout','.details-content', function(){
			  		if(msg == '') return false;
		  			layer.close(index);
			  	})
			  	
			  	$(document).on('click','.details-content', function(){
			  		$(this).css('-moz-user-select', 'none');
			  		$(this).css('-webkit-user-select:none', 'none');
			  		$(this).css('-ms-user-select:none', 'none');
			  		$(this).css('-khtml-user-select', 'none');
			  		$(this).bind('selectstart',function(){
			  			return false;
			  		})
			  	})
			  	
			  	$(document).on('dblclick','.details-content', function(){
			  		if(msg == '') return false;
			  		layer.open({
			  	  		title:false,
			  	  		btn:['复制','关闭'],
			  	  		type: 0,
			  	  		btnAlign: 'c',
			  	  		closeBtn: 0,
			  	  		shadeClose: true,
			  	  		content: msg,
			  	  		success:function(){
			  	  			$.getScript($('#hiddendom').data('public')+"/js/z-third/zeroclipboard/jquery.zclip.js", function(){
				  	  			$('.layui-layer-btn0').zclip({
						  	  		path: $('#hiddendom').data('public')+"/js/z-third/zeroclipboard/ZeroClipboard.swf",
						  	  		copy: msg,
						  	  		afterCopy: function(){
						  	  			layer.msg('复制成功');
						  	  		}
						  	  	})
			  	  			});
			  	  		}
			  	  	});
			  	})
			},
			marktips: function(){
				var index = '', msg = '';
				$(document).on('mouseover','.mark-tips', function(){
					msg = $(this).data('title');
					if(msg == '') return false;
					index = layer.tips(msg, $(this), {
					  tips: [1, '#414d5c'],
					  time: 0
					});
			  	})
			  	
			  	$(document).on('mouseout','.mark-tips', function(){
			  		if(msg == '') return false;
		  			layer.close(index);
			  	})
			},
			cardtips: function(content){
				$('.tips-select').html('');
		  		var htmlcontent = '<label class="show">'+content+'</label>';
		  		$('.tips-select').html(htmlcontent);
			},
			formErrorTips: function(code, data, callback){
				for(var key in data){
					if(data[key].code == 'other'){
						layer.msg(data[key].msg, {icon: 5, anim: 6}, callback);
						data[key].obj != 'undefined' && data[key].obj.addClass('layui-form-danger');
						data[key].obj != 'undefined' && data[key].obj.focus();
						break;
					}
					
					if(code == data[key].code){
						layer.msg(data[key].msg, {icon: 5, anim: 6}, callback);
						data[key].obj != 'undefined' && data[key].obj.focus();
						data[key].obj != 'undefined' && data[key].obj.addClass('layui-form-danger');
						break;
					}
				}
				return false;
			},
			confirm: function(msg, callback){
				layer.confirm(msg, {
		  			title: false,
		  			closeBtn: false,
		  			btnAlign: 'c'
		  		}, function(index){
		  			callback(index);
		  		});
			},
			laypage: function(laypage, pages, curr, callback){
				laypage({
			        cont: 'Page',
			        pages: pages,
			        skin: '#1E9FFF',
			        hash: 'list',
			        curr: curr,
			        jump: function(obj, first) {
			            var curr_now = obj.curr;
			            if(!first){
			            	callback(curr_now);
			            }
			        }
				});
			}
	}
	
	exports('asuma', asumaObj);
})

layui.config({
	base: $('#hiddendom').data('public')+'/js/'
}).use('asuma');