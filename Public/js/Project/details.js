layui.use(['element', 'form', 'laydate', 'asuma'], function(){
  	var form = layui.form(),laydate = layui.laydate,asuma=layui.asuma;
  	var func = {
  		members : function(id){
  			$.post($('#dpt_sel').data('url'),{id:id,mid:$('#common').data('mid')},function(data){
  				$('#director_sel').html(data);
  				form.render('select');
  			});
  		},
  		url: function(){
			var url = $('#common').data('reload');
			var ihash = $('#common').data('hash');
			if(ihash){
				url += "#!list="+ihash;
			}
			return url;
		}
  	};
  	
  	var start = {
  			min: laydate.now('-7'),
  			max: '2077-07-13',
  			choose: function(dates){
  				end.min = dates;
  				end.start = dates;
  			}
  	};
  	var end = {
  			min: laydate.now(),
  			max: '2077-07-13',
  			choose: function(dates){
  				start.max = dates;
  			}
  	};
  	
  	func.members($('.takedpt').val());
  	
  	form.on('select(departChange)', function(data){
  		func.members(data.value);
  	})
  	
  	$('#startdate').on('click', function(){
  		start.elem = this;
  		laydate(start);
  	});
  	
  	$('#enddate').on('click', function(){
  		end.elem = this;
  		laydate(end);
  	});
  	
  	form.on('submit(saveBtn)', function(data){
  		var subObj = $(this);
		$.post(subObj.data('url'), data.field, function(code){
			if(code == 1){
				layer.msg('添加成功');
				window.location.href = $('#common').data('reload');
				return true;
			}
			
			var msgData = [
			               {code: '-100', msg: '项目名称不能为空', obj: $('input[name=project_name]')},
			               {code: '-101', msg: '项目名称最好在3到10个字以内啊', obj: $('input[name=project_name]')},
			               {code: '-201', msg: '项目代号最好在3到10个字以内啊', obj: $('input[name=code_name]')},
			               {code: '-300', msg: '请选择名称是否可见', obj: subObj},
			               {code: '-301', msg: '代号不能为空', obj: $('input[name=code_name]')},
			               {code: '-400', msg: '请选择部门', obj: $('.takedpt')},
			               {code: '-500', msg: '请选择项目负责人', obj: $('select[name=director]')},
			               {code: '-600', msg: '请选择开始时间', obj: $('input[name=startdate]')},
			               {code: '-700', msg: '请选择结束时间', obj: $('input[name=enddate]')},
			               {code: '-801', msg: '项目描述最好在3到200个字符以内啊', obj: $('input[name=description]')},
			               {code: '-1', msg: '添加失败', obj: subObj},
			               {code: '-2', msg: '系统错误', obj: subObj},
			               {code: '0', msg: '请勿重复操作', obj: subObj},
			               ]
			
			asuma.formErrorTips(code, msgData);
		});
  	});
  	
  	$('.returnBtn').on('click', function(){
		var url = func.url();
		window.location.href = url;
	});
});