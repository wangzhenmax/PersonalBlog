<?php
// 使用 redis 保存 SESSION
// ini_set('session.save_handler', 'redis');
// // 设置 redis 服务器的地址、端
// ini_set('session.save_path', 'tcp://127.0.0.1:6379?database=3');
// ini_set('session.gc_maxlifetime', 3600);
session_start();
// 定义常量
define('SLA',DIRECTORY_SEPARATOR);
define('ROOT', dirname(__FILE__) . '/../');
require(ROOT.'vendor/autoload.php');
// 实现类的自动加载
function autoload($class)
{
    $path = str_replace('\\', '/', $class);

    require(ROOT . $path . '.php');
}
spl_autoload_register('autoload');

// 添加路由 ：解析 URL 浏览器上 blog/index  CLI中就是 blog index

if(php_sapi_name() == 'cli')
{
    $controller = ucfirst($argv[1]) . 'Controller';
    $action = $argv[2];
}
else
{
    if( isset($_SERVER['REQUEST_URI']) &&  $_SERVER['REQUEST_URI']!="/")
    {
        $pathInfo = substr($_SERVER['REQUEST_URI'],1);
	
	// 根据 / 转成数组
        $pathInfo = explode('/', $pathInfo);

        // 得到控制器名和方法名 ：
        $controller = ucfirst($pathInfo[0]) . 'Controller';
	if(isset($pathInfo[1])){
        $index = strpos("$pathInfo[1]","?");
	if($index)
        $action = substr( $pathInfo[1],0,$index);
	else
	$action = $pathInfo[1];
	}else {
	$action = 'index';
	}
    }
    else
    {
        // 默认控制器和方法
        $controller = 'IndexController';
        $action = 'index';
    }
}
//为控制器添加命名空间
$fullController = 'controllers\\'.$controller;
@$_C = new $fullController;
@$_C->$action();

// 加载视图
// 参数一、加载的视图的文件名
// 参数二、向视图中传的数据
function view($viewFileName, $data = [])
{
    // 解压数组成变量
    extract($data);

    $path = str_replace('.', '/', $viewFileName) . '.html';

    // 加载视图
    require(ROOT . 'views/' . $path);
}

// 获取当前 URL 上所有的参数，并且还能排除掉某些参数
// 参数：要排除的变量
function getUrlParams($except = [])
{
    // ['odby','odway']
    // 循环删除变量
    foreach($except as $v)
    {
        unset($_GET[$v]);

        // unset($_GET['odby']);
        // unset($_GET['odway']);
    }

    /*
    $_GET['keyword'] = 'xzb';
    $_GET['is_show] = 1

    // 拼出：  keyword=abc&is_show=1
    */

    $str = '';
    foreach($_GET as $k => $v)
    {
        $str .= "$k=$v&";
    }

    return $str;

}
//基本配置
function config($name){
    static $config =null;
    if($config===null){
       $config =  require (ROOT."config.php");
    }
    return $config[$name];
}
//提示消息以及跳转
function message($str,$type,$url,$seconds=5){
    if($type==0){
        echo "<script>alert('{$message}');location.href='{$url}';</script>";
        exit;

    }else if($type==1){
        view('common.success', [
            'message' => $str,
            'url' => $url,
            'seconds' => $seconds
        ]);
    }
    else if($type==2){
        $_SESSION['_MESS_'] = $str;
        redirect($url);
    }
}
//访问指定url
function redirect($url){
    
    header("Location:".$url);
    exit;
}
//返回上一级
function back(){
    redirect($_SERVER['HTTP_REFERER']);
}
//防止跨站伪造
function csrf(){
    if(!isset($_SESSION['token'])){
        $token = md5(rand(1,9999).microtime());
        $_SESSION['token'] = $token;
    };
    return $_SESSION['token'];
}
//过滤数据
function e($content){
    return htmlspecialchars($content);
}
//令牌简写
function csrf_field()
{
    $csrf = isset($_SESSION['token']) ? $_SESSION['token'] : csrf();
    echo "<input type='hidden' name='token' value='{$csrf}'>";
}
