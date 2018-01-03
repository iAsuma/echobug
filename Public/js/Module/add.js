layui.use(['element', 'form', 'asuma'], function(){
  	var form = layui.form(),asuma=layui.asuma;
  	var func = {
  			members : function(id){
  				if(!id) return false;
  	  			$.post($('#pjt_sel').data('url'),{pid:id},function(data){
  	  				$('#director_sel').html(data);
  	  				form.render('select');
  	  			});
  	  		},
  			insertLi: function(str1, str2, str3, id){
  				var listr = '<li class="new"><span>'+str1+'</span><span>'+str2+'</span><span>'+str3+'</span><button class="delbtn" type="button" data-id="'+id+'"></button></li>';
  				$('.now-mod').prepend(listr);
  				setTimeout(function() {
					layer.msg('添加成功', {time: 1050, offset: '62px'});
  				},1050);
  			},
  			removeLi:function(obj){
  				var liObj = obj.parent('li');
  				liObj.toggleClass('new remove');
  				setTimeout(function() {
  					liObj.remove();
  					layer.msg('删除成功', {time: 1050, offset: '62px'});
  				},1050);
  			}
  	}
  	
  	func.members($('.takepjt').val());
  	
  	form.on('select(projectChange)', function(datas){
  		if($('.takepjt').data('utype') != 0) return false;
  		func.members(datas.value);
  	})
  	
  	$('#addMoudle').removeClass('noFinished', false);
  	
  	$(document).on('click', '.delbtn', function(){
  		var obj = $(this);
  		var mod_str = obj.parent('li').find('span:eq(0)').html();
  		obj.css('right', 0);
  		layer.confirm('删除模块：'+mod_str+'？', function(index){
  			obj.css('right', '');
  			$.post($('.now-mod').data('url'), {id:obj.data('id')}, function(code){
  				if(code == 1){
  					func.removeLi(obj);
  				}else{
  					layer.msg('系统错误',{offset: '62px'});
  				}
  				layer.close(index);
  			});
  		},function(){
  			obj.css('right', '');
  		});
  	});
  	
  	form.on('submit(saveBtn)', function(data){
  		var subObj = $(this);
  		if(subObj.hasClass('noFinished')){
  			layer.msg('请稍后添加...', {offset: '62px'});
  			return false;
  		}
  		
  		$('#addMoudle').addClass('noFinished');
  		
		$.post(subObj.data('url'), data.field, function(code){
			var nameObj = $('input[name=module_name]');
			var projectObj = $('select[name=project]');
			var directorObj = $('select[name=director]');
			
			setTimeout(function() {
				$('#addMoudle').removeClass('noFinished');
			},1050);
			
			if(code > 0){
				func.insertLi(nameObj.val(), projectObj.find('option:selected').text(), directorObj.find('option:selected').text(), code);
				nameObj.val('');projectObj.val('');directorObj.val('');
				form.render();
				return true;
			}
			
			var msgData = [
			               {code: '-100', msg: '模块名称不能为空', obj: nameObj},
			               {code: '-101', msg: '模块名称最好在3到10个字以内啊', obj: nameObj},
			               {code: '-200', msg: '项目获取失败', obj: projectObj},
			               {code: '-300', msg: '负责人获取失败', obj: directorObj},
			               {code: '-1', msg: '添加失败', obj: subObj},
			               {code: '-2', msg: '系统错误', obj: subObj}
			               ]
			
			asuma.formErrorTips(code, msgData);
		});
  	});
});