<?php
namespace controllers;
class BaseController { 
    public function __construct(){
        date_default_timezone_set('PRC'); 
        if(!isset($_SESSION['email'])&&$_SESSION['email']!="457340@qq.com"){
            redirect("/index/login");
        }
        // if(isset($_SESSION['root'])){
        //     return ;
        // }
        // $path = isset($_SERVER['PATH_INFO']) ? trim($_SERVER['PATH_INFO']) : 'index/index';
        // $whiteList = ['admin/index','index/login'];
        // if(!in_array($path,array_merge($whiteList,$_SESSION['path']))){
        //     die('sorry! you no 权力 Visit');
        // }
    }
}
