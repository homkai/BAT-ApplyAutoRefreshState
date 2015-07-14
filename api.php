<?php 
// 定义类后缀
define('DIR_BASE', './inc/');
// 定义类后缀
define('CLASS_EXT', '.class.php');
// 载入工具库
include DIR_BASE.'tools.php';
// 载入配置
$GLOBALS['__CONFIG__'] = include './config.php';

// 自动加载
spl_autoload_register(function($className) {
	include DIR_BASE.$className.CLASS_EXT;
});

// 路由
route();
function route(){
	$API = new API();
	$action = ltrim(str_replace($_SERVER['SCRIPT_NAME'], '', $_SERVER['PHP_SELF']), '/');
	if(!method_exists($API, $action)){
		exit(show_404());
	}
	call_user_func(array($API, $action));
}