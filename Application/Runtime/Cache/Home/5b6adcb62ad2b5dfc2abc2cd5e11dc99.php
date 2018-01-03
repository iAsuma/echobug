<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="keywords" content="gotoecho,echobug,阿斯玛,BUG管理系统">
	<meta name="description" content="这可能是东半球第二好用的定制bug管理系统">
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>echobug--BUG管理系统 登录</title>
	<link href="/echobug/Public/iconfont/iconfont.css" rel="stylesheet" />
	<link href="/echobug/Public/css/login.css" rel="stylesheet"/>
	<script type="text/javascript" src="/echobug/Public/js/jquery-1.9.1.min.js" ></script>
	<script type="text/javascript" src="/echobug/Public/js/login.js" ></script>
</head>
<body>
<div class="login-panel">
	<div class="login-logo">
		<img src="/echobug/Public/img/logo.png" />
		<span class="version">alpha版</span>
		<em>|&nbsp;bug管理系统</em>
	</div>

	<div class="link"><a href="javascript:;" class="iconfont icon-weibo"></a></div>
	
	<div class="form-item">
		<form id="logindata">
		<div class="prompt" style="display: none;"></div>
		<div class="form-inline">
			<input type="text" placeholder="用户名" name="username"/>
		</div>
		<div class="form-inline">
			<input type="password" placeholder="密码" name="userpassword"/>
		</div>
		<div class="form-inline">
			<input type="text" placeholder="验证码" name="verification_code"/>
			<a href="javascript:;" class="ver-code" id="verifyCode" data-url="<?php echo U('verifyImg');?>">
				<img src="<?php echo U('verifyImg');?>" />
			</a>
		</div>
		<div class="form-inline remember">
			<label class="check-box">
				<input type="checkbox" name="is_remember_user"/>
				<i></i>
				<em>记住用户名密码</em>
			</label>
		</div>
		<div class="form-inline align-center">
			<button type="button" class="btn" id="loginBtn" data-url="<?php echo U('checkUserLogin');?>" data-home="<?php echo U('Panel/index');?>">登 录</button>
		</div>
		</form>
	</div>

	<div class="footer">
		<p>&copy;&nbsp;2017&nbsp;asuma</p>
	</div>
</div>
</body>
</html>