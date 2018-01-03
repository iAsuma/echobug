<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="renderer" content="webkit">
	<meta http-equiv="X-UA-Compatible" content="IE=Edge,chrome=1">
	<title>面板</title>
	<link href="/echobug/Public/layui/css/layui.css" rel="stylesheet" />
	<link href="/echobug/Public/iconfont/iconfont.css" rel="stylesheet" />
	<link href="/echobug/Public/css/front.css" rel="stylesheet"/>
	<script src="/echobug/Public/js/jquery-1.9.1.min.js"></script>
	<script src="/echobug/Public/layui/layui.js"></script>
</head>
<body class="col-gray">
	<div class="layui-layout-admin">
		<!--头部-->
		<div class="header-wp">
			<div class="header-inner clearfix">
				<a class="logo" href="<?php echo ($indexurl); ?>">
					<img src="/echobug/Public/img/logo-blue.png" />
				</a>
				<div class="nav">
					<a href="<?php echo U('Panel/index');?>" <?php if(CONTROLLER_NAME == Panel): ?>class="current"<?php endif; ?>>面板</a>
					<a href="<?php echo U('MyView/index');?>" <?php if(CONTROLLER_NAME == MyView): ?>class="current"<?php endif; ?>>我的视图</a>
					<a href="javascript:;">关于</a>
				</div>
				<div class="nav-user">
					<a href="javascript:;">
						<img src="/echobug/Public/img/tx_temp.jpg" />  &nbsp;<?php echo ($uname); ?>
					</a>
					<a href="<?php echo U('BugUndone/index');?>"><i class="iconfont">&#xe607;</i> 控制台</a>
					<a href="<?php echo U('Login/logout');?>"><i class="iconfont">&#xe610;</i> 退了</a>
				</div>
			</div>	
		</div>
		
		<!--内容-->
		
<div class="panel">
	<div class="panel-item">
		<h2>正在进行的项目</h2>
		<a href="javascript:;" class="ques"><i class="iconfont">&#xe603;</i></a>
		<div class="tooltips">
			<em class="tips-arrow"></em>
			<div class="tips-box">
				<ul class="tips-list">
					<li>只会显示研发中和测试中的项目；</li>
					<li>只会显示最先创建的三个项目。</li>
				</ul>
			</div>
		</div>
		<ul class="ongoing-projects clearfix" id="">
			<?php if(empty($pjt)): ?><li><div><span>没有进行中的项目</span></div></li>
			<?php else: ?>
			<?php if(is_array($pjt)): $i = 0; $__LIST__ = $pjt;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><li><div><span><?php echo ($vo['name']); ?></span></div></li><?php endforeach; endif; else: echo "" ;endif; endif; ?>
		</ul>
	</div>
	<div class="panel-item">
		<h2>实时统计</h2>
		<a href="javascript:;" class="ques"><!-- <i class="iconfont">&#xe603;</i> --></a>
		<div class="tooltips">
			<em class="tips-arrow"></em>
			<div class="tips-box">
				<ul class="tips-list">
					<li>
						<span class="tips-left"></span>
						<span class="tips-right"></span>
					</li>
				</ul>
			</div>
		</div>
		<ul class="statistic clearfix">
			<li>
				<p>未处理的</p>
				<p><a href="javascript:;" id="undone">0</a></p>
			</li>
			<li>
				<p>今日未处理的</p>
				<p><a href="javascript:;" id="today_undone">0</a></p>
			</li>
			<li>
				<p>昨日已处理</p>
				<p><a href="javascript:;" id="last_done">0</a></p>
			</li>
			<li>
				<p>今日已处理</p>
				<p><a href="javascript:;" id="today_done">0</a></p>
			</li>
			<li>
				<p>昨日我反馈的</p>
				<p><a href="javascript:;" id="last_mysend">0</a></p>
			</li>
		</ul>
	</div>
	<div class="panel-item">
		<h2>累计统计</h2>
		<div class="cum-statistic">
			<div id="graphdiv" style="width:100%;height:400px;"></div>
		</div>
	</div>
</div>
<input type="hidden" name="report" id="report" data-url="<?php echo U('indexReport');?>">

		
		<div class="footer">
			<p>&copy;&nbsp;2017&nbsp;gotoecho.com</p>
		</div>
		<input type="hidden" id="hiddendom" name="hiddendom" value="0" data-public="/echobug/Public"/>
	</div>
	
	<script src="/echobug/Public/js/asuma.js" type="text/javascript"></script>
	
<script type="text/javascript" src="/echobug/Public/js/z-third/echarts/echarts.min.js"></script>
<script type="text/javascript" src="/echobug/Public/js/z-third/echarts/macarons.js"></script>
<script type="text/javascript" src="/echobug/Public/js/Panel/showcharts.js"></script>
<script type="text/javascript" src="/echobug/Public/js/Panel/index.js"></script>

	<script>
		$('.layui-layout-admin').append('<ul class="layui-fixbar"><li class="layui-icon layui-fixbar-top">&#xe604;</li></ul>');
		var $top = $('.layui-fixbar-top'),$window = $(window);
		$top.on('click', function() {
			$('body, html').animate({ scrollTop: 0 });
		});
		$window.scroll(function() {
			$window.scrollTop() > 100 ? $top.fadeIn() : $top.fadeOut();
		});
	</script>
</body>
</html>