layui.use(['element', 'form', 'layedit','asuma'], function(){
  	var form = layui.form(),asuma=layui.asuma,layedit = layui.layedit;
  	var func = {
  			moduleSelect: function(id){
  				$.post($('#childselect').data('urla'),{id: id},function(code){
  					$('#mod_sel').html(code);
  	  				form.render('select');
  	  				func.memberSelect(id);
  		  		});
  			},
  			memberSelect: function(id, modid){
  				$.post($('#childselect').data('urlb'),{id: id, modid: modid},function(code){
  					$('#mem_sel').html(code);
  	  				form.render('select');
  		  		});
  			}
  	}
  	
  	if($('select[name=project]').val()){
  		func.moduleSelect($('select[name=project]').val());	
  	}
  	
  	form.on('select(projectChange)', function(data){
  		func.moduleSelect(data.value);
  	})
  	
  	form.on('select(moduleChange)', function(data){
  		var mid = $('select[name=module] option:selected').data('mid');
  		
  		$('select[name=director]').find('option').prop('selected', false);
  		$('select[name=director] option[value="'+mid+'"]').prop('selected', true);
  		
  		form.render('select');
  	})
  	
  	var uploadObj = $('#uploadinfo');
  	
  	layedit.set({
  		uploadImage: {
  			url: uploadObj.data('url')+'?uid='+uploadObj.data('uid')+'&token='+uploadObj.data('token')+'&timestamp='+uploadObj.data('time')
  		}
	});
  	
  	var editIndex = layedit.build('editor_tool',{hideTool: ['face'], height : 420});
  	
  	form.verify({
  		content: function(){
  			layedit.sync(editIndex);
  		}
  	})
  	
  	form.on('submit(saveBtn)', function(data){
  		var subObj = $(this);
		$.post(subObj.data('url'), data.field, function(code){
			if(code == 1){
				layer.msg('添加成功');
				window.location.href = subObj.data('reload');
				return true;
			}
			
			var msgData = [
			               {code: '-100', msg: '请选择项目', obj: $('select[name=project]')},
			               {code: '-200', msg: '请选择模块', obj: $('select[name=module]')},
			               {code: '-300', msg: '请填写BUG摘要', obj: $('input[name=summary]')},
			               {code: '-301', msg: '即使是摘要，也多写一点么', obj: $('input[name=summary]')},
			               {code: '-302', msg: '摘要不能写那么长', obj: $('input[name=summary]')},
			               {code: '-400', msg: '请填写测试环境', obj: $('input[name=test_environment]')},
			               {code: '-401', msg: '确定正确填写测试环境了么', obj: $('input[name=test_environment]')},
			               {code: '-402', msg: '测试环境不宜太长，如有需求请填入详情内', obj: $('input[name=test_environment]')},
			               {code: '-500', msg: '请选择反馈对象', obj: $('select[name=director]')},
			               {code: '-600', msg: '请选择权重', obj: $('input[name=weight]')},
			               {code: '-700', msg: '请填写BUG详情', obj: $('textarea[name=content]')},
			               {code: '-701', msg: '请详细填写BUG详情', obj: $('textarea[name=content]')},
			               {code: '-702', msg: 'BUG详情内容超出范围', obj: $('textarea[name=content]')},
			               {code: '-1', msg: '添加失败', obj: subObj},
			               {code: '-2', msg: '系统错误', obj: subObj},
			               {code: '0', msg: '请勿重复操作', obj: subObj},
			               ]
			
			asuma.formErrorTips(code, msgData);
		});
  	});
});