<?php
return array(	
	//'配置项'=>'配置值'
	'URL_MODEL' => 2,
	'SESSION_AUTO_START' => true,
    'URL_CASE_INSENSITIVE' => true,
	'LOAD_EXT_CONFIG' => 'db',
		
	'USER_AUTH_KEY' => 'asuma_lee',
	'USER_COOKIE_KEY' => 'bugmini_lee',
	
	'TOKEN_ON' => true,
	'TOKEN_NAME' => '__hash__',
	'TOKEN_TYPE' => 'md5',
	'TOKEN_RESET' => true,
    
    'IS_HTTPS' => 0, //0为http, 1为https
    'DOMAIN_NAME' => '',//默认跳转首页的域名(不包含http://或https://)
    
    'UPLOAD_TYPE' => 2, //文件上传方式 1为本地或服务器，2为阿里云OSS
    'UNIQUE_SALT_UPLOAD_KEY' => 'unique_salt',
    'UPLOAD_FILE_PATH' => 'E:/www/res/bugmini/', // 上传文件路径
    'UPLOAD_FILE_URL' => 'http://image.haiyunx.com', // 远程访问的域名  //本地 --http://192.168.0.105/res/bugmini
    
    //*************************阿里云OSS相关配置 start**************************************//
	'OSS_ACCESS_ID'=>'QASL2ZaBV9lQEvzx',
	'OSS_ACCESS_KEY'=>'OCGJLCj8Y2Zpvf6QA6E3FNLSXCUhn3',
	'OSS_ENDPOINT'=>'oss-cn-hangzhou.aliyuncs.com',
	'OSS_BUCKET'=>'haiyunimage',
	//*************************阿里云OSS相关配置 end**************************************//
	
	'SHOW_PAGE_TRACE' => true
); 