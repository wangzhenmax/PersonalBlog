<?php
namespace controllers;
class IndexController{
    public function index(){
        view("index.index");
    }
       // 显示登录
    public function login()
    {      
        view('admin/login');
    }
     // 接收登录
    public function doLogin(){
        // if(strcasecmp($_SESSION['validateCode'],$_POST['captcha'])!=0){
        //     echo $_SESSION['validateCode']."---",$_POST['captcha'];
        //    die("验证码输入错误");
        // }
        $model = new \models\Admin;
        $emails = $_POST['emails'];
        $password = $_POST['password'];
        if(isset($_SESSION[$emails])){
            if($_SESSION[$emails]>=3){
                die("你已经错了三次了");
            }
        }else{
            $_SESSION[$emails] = 0;
        }
        $user = $model->find($emails,$password);
        if(!$user){
            $_SESSION[$emails]++;
            redirect("/index/login");
        }else{
            // var_dump($_SESSION);
            redirect("/admin/index");
        }
    }
}