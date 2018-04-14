layui.use(['element', 'form', 'asuma'], function(){
  	var form = layui.form(),asuma=layui.asuma;
  	
  	form.on('submit(saveBtn)', function(data){
  		var subObj = $(this);
		$.post(subObj.data('url'), data.field, function(code){
			if(code == 1){
				layer.msg('添加成功');
				window.location.href = subObj.data('reload');
				return true;
			}
			
			var msgData = [
			               {code: '-100', msg: '部门名称不能为空', obj: $('input[name=dep_name]')},
			               {code: '-101', msg: '部门名称最好在3到10个字以内啊', obj: $('input[name=dep_name]')},
			               {code: '-1', msg: '添加失败', obj: subObj},
			               {code: '-2', msg: '系统错误', obj: subObj},
			               {code: '0', msg: '请勿重复操作', obj: subObj},
			               ]
			
			asuma.formErrorTips(code, msgData);
		});
  	});
  	
  	$('.returnBtn').on('click', function(){
		window.location.href = $(this).data('reload');
	});
});