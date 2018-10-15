<?php
namespace controllers;
use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
use models\Admin;
class AdminController{
    public function captcha(){
        $en = \libs\Captcha::getInstance();
        $en->en_captcha();
    }
    // 显示登录
    public function login()
    {      
        view('admin/login');
    }
   
    // 接收登录
    public function doLogin(){
        if(strcasecmp($_SESSION['validateCode'],$_POST['captcha'])!=0){
           die("验证码输入错误");
        }
        $model = new Admin;
        $emails = $_POST['emails'];
        $password = $_POST['password'];
        if(isset($_SESSION[$emails.' '])){
            if($_SESSION[$emails.' ']>=3){
                die("你已经错了三次了");
            }
        }else{
            $_SESSION[$emails.' '] = 0;
        }
        $user = $model->find($emails,$password);
        if(!$user){
            $_SESSION[$emails.' ']++;
            redirect("/admin/login");
        }else{
            redirect("/admin/index");
        }
    }
    public function register()
    {
        view('admin/register');
    }
    //接收注册
    // public function doRegister(){
    //    $email = $_POST['email'];
    //     $password = md5($_POST['password']);
    //     $code = md5(rand(1,9999));
    //     $redis = \libs\Predis::getinterface();
    //     $value = json_encode([
    //         'email'=>$email,
    //         'password'=>$password,
    //     ]);
    //     $key = "temp_user:{$code}";
    //     $redis->setex($key,300,$value);
    //     $name = explode('@', $email);
    //     $from = [$email, $name[0]];
    //     $message = [
    //          'title' => '智聊系统-账号激活',
    //         'content' => "点击以下链接进行激活：<br> <a href='http://localhost:9999/user/active_user?code={$code}'>
    //             http://localhost:8888/admin/active_user?code={$code}
        
    //         </a>。",
    //         'from' => $from,
    //     ];
    //     $message = json_encode($message);
    //     $redis = \libs\Predis::getinterface();
    //     $redis->lpush('email', $message);
    //     echo '激活码已发送至你的邮箱请根据相关链接进行激活';
    // }
        //手机短信
    // public function getFlc(){
    // // $phone = $_GET['phone'];
    // $code = rand(100000, 999999);
    // $_SESSION['code'] = $code;
    //  $config = [
    //         'accessKeyId'    => 'LTAIeGaMvNDNUgRO',
    //         'accessKeySecret' => 'GThDNwn7372SKxx6nYufdkYyPwfXFM',
    //     ];
    // $client  = new Client($config);
    // $sendSms = new SendSms;
    // $sendSms->setPhoneNumbers('18662755616');
    // $sendSms->setSignName('许若尘');
    // $sendSms->setTemplateCode('SMS_135445053');
    // $sendSms->setTemplateParam(['code' => $code]);
    // $sendSms->setOutId('demo');
    // print_r($client->execute($sendSms));
    // }

    // public function doRegister(){
    //     if($_POST['email']==$_SESSION['code']){
    //         echo "成功！";
    //     }else{
    //         echo "失败";
    //     }
    // }



    public function active_user(){
        $code = $_GET['code'];
        $redis = \libs\Predis::getinterface();
        $key = "temp_user:".$code;
        $data = $redis->get($key);
        if($data){
            $redis->del($key);
            $data = json_decode($data,true);
            $user = new User;
            $user->add($data['email'],$data['password']);
            header("Location:/admin/index");
        }else{
          echo " 激活码错误！";
        }
    }
    // 显示主页
    public function index(){
        if(!isset($_SESSION['id'])){
            view("/admin/login");
        }else{
            view("/admin/index");
        }
        
    }
}