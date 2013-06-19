<?php
return array(
	//'配置项'=>'配置值'
	'MYCOMPANY'         	=>    '无锡市宸铭商务印刷有限公司',
	'SYSNAME'               =>    '印刷报价系统',
	'APP_GROUP_LIST'        =>    '7788ysw,Center',
	'DEFAULT_GROUP'         =>    '7788ysw', //默认分组
	"DB_TYPE"               =>    "mysql",
	"DB_HOST"               =>    "127.0.0.1",
	'DB_NAME'               =>    '7788ysw-view', // 数据库名
	"DB_USER"               =>    "root",//用户名
	"DB_PWD"                =>    "",// 密码
	'DB_PREFIX'             =>    '',// 数据库表前缀
	'DB_CHARSET'            =>    'utf8',
	'DEFAULT_THEME'         =>    '',	// 默认模板主题名称
	'SHOW_ERROR_MSG'        =>    true,    // 显示错误信息
	'OUTPUT_ENCODE'         =>    false, // 页面压缩输出
	'ORDER_UPLOAD_PATH'     =>    'Public/upload/customer/',//订单文件上传地址，以“/”结尾
	//'OFFER_UPLOAD_PATH'     =>    'Public/upload/temp/',//购物车文件上传地址，以“/”结尾
	'TMPL_TEMPLATE_SUFFIX'  =>    '.phtml'    // 默认模板文件后缀
	//'LOG_RECORD'            =>    true,
	//'URL_HTML_SUFFIX'       => 'html'  // URL伪静态后缀设置
);
?>