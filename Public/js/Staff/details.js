layui.use(['element', 'form', 'asuma'], function(){
  	var form = layui.form(),asuma=layui.asuma,
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
  	
  	$('.returnBtn').on('click', function(){
		var url = func.url();
		window.location.href = url;
	});
  	
  	if($('input[name=level]:checked').val()){
  		asuma.cardtips($('input[name=level]:checked').data('tips'));
  	}
  	
  	form.on('radio(levelsel)', function(data){
  		asuma.cardtips($(this).data('tips'));
  	})
  	
  	form.on('submit(saveBtn)', function(data){
  		var subObj = $(this);
		$.post(subObj.data('url'), data.field, function(code){
			if(code == 1){
				layer.msg('添加成功');
				window.location.href = func.url();
				return true;
			}
			
			var msgData = [
			               {code: '-100', msg: '成员姓名不能为空', obj: $('input[name=member_name]')},
			               {code: '-101', msg: '成员名称最好在3到18个字符以内啊', obj: $('input[name=member_name]')},
			               {code: '-200', msg: '账号不能为空', obj: $('input[name=member_account]')},
			               {code: '-201', msg: '账号只能为邮箱或邮箱格式错误', obj: $('input[name=member_account]')},
			               {code: '-202', msg: '账号只能为邮箱或邮箱格式错误', obj: $('input[name=member_account]')},
			               {code: '-203', msg: '该账号已存在', obj: $('input[name=member_account]')},
			               {code: '-300', msg: '请选择部门', obj: $('select[name=department]')},
			               {code: '-400', msg: '请选择成员属性', obj: $('select[name=memberi_type]')},
			               {code: '-500', msg: '请填写成员职称', obj: $('input[name=memberi_job]')},
			               {code: '-501', msg: '职称最好在3到24个字符以内啊', obj: $('input[name=memberi_job]')},
			               {code: '-600', msg: '请填写成员电话', obj: $('input[name=cellphone]')},
			               {code: '-601', msg: '电话格式错误', obj: $('input[name=cellphone]')},
			               {code: '-701', msg: '请填写正确的QQ', obj: $('input[name=qq]')},
			               {code: '-2', msg: '添加失败', obj: subObj},
			               {code: '-2', msg: '系统错误', obj: subObj},
			               {code: '0', msg: '请勿重复操作', obj: subObj},
			               ]
			
			asuma.formErrorTips(code, msgData);
		});
  	});
});