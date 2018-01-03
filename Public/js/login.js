$(function(){
	$.submitLogin = function(){
		var obj = $('#loginBtn');
		$.post(obj.data('url'),$('#logindata').serialize(), function(data){
			$('.prompt').hide();
			$('#verifyCode').click();
			switch(data) {
				case '1':
					window.location.href = obj.data('home');
					break;
				case '-100':
					$('input[name=username]').focus();
					$(".prompt").html('用户名不能为空').show();
					break;
				case '-200':
					$('input[name=userpassword]').focus();
					$(".prompt").html('密码不能为空').show();
					break;
				case '-101':
					$('input[name=username]').focus();
					$(".prompt").html('用户名不存在').show();
					break;
				case '-201':
					$('input[name=userpassword]').focus();
					$(".prompt").html('密码错误').show();
					break;
				case '-102':
					$('input[name=username]').focus();
					$(".prompt").html('账号已冻结').show();
					break;
				case '-103':
					$('input[name=username]').focus();
					$(".prompt").html('账号已删除').show();
					break;
				case '-300':
					$('input[name=verification_code]').focus();
					$(".prompt").html('验证码错误').show();
					break;
				case '-1':
					$(".prompt").html('登录失败，请刷新页面').show();
					break;
				default:
					$('input[name=username]').focus();
					$(".prompt").html('登录失败').show();
					break;
			}
		})
	}
	
	$('input[name=username]').focus();
	
	$('#loginBtn').click(function(){
		$.submitLogin();
	});
	
	$('input').keydown(function(event){
		var key = event ? (event.charCode || event.keyCode) : 0;
		key == 13 && $.submitLogin();
	});

	$('#verifyCode').on('click',function(){
		$('#verifyCode img').attr('src', $(this).data('url')+'?'+Math.random());
	})
})