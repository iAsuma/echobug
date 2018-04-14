layui.use(['element', 'form', 'layedit','asuma'], function(){
	var form = layui.form(),asuma=layui.asuma,layedit = layui.layedit,
	func = {
		url: function(){
			var url = $('#common').data('reload');
			var ihash = $('#common').data('hash');
			if(ihash){
				url += "#!list="+ihash;
			}
			return url;
		}
	};
	
	$('.field-content img').on('click', function(){
		layer.photos({
			photos: $('.field-content'),
			anim: 0,
			shade: 0.4
		});
  	});
	
	$('.returnBtn').on('click', function(){
		var url = func.url();
		window.location.href = url;
	});
	
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
				layer.msg('修改成功');
				window.location.reload();
				return true;
			}
			
			var msgData = [
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
			               {code: '-3', msg: '该数据不可被修改', obj: subObj},
			               {code: '0', msg: '请勿重复操作', obj: subObj},
			               ]
			
			asuma.formErrorTips(code, msgData);
		});
  	});
  	
});
