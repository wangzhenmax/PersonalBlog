<?php
namespace controllers;
use Flc\Dysms\Client;
use Flc\Dysms\Request\SendSms;
use models\Admin;
use models\Index;
class AdminController  extends BaseController{


    //修改分类名称
    public function edit(){
        $id = $_GET['id'];
        $model = new Admin;
        $data = $model->findOne($id);
        view("type/edit",$data);
    }
    //修改分类
    public function update(){
        $model = new Admin;
        $id = $_GET['id'];
        $name = $_POST['name'];
        if($model->updates($name,$id)){
            redirect("/admin/types");
        }else{
            die('修改失败');
        }
    }

    // 删除分类
    public function delete(){
         $model = new Admin;
         $id = $_GET['id'];
         $model->deleteType($id);
         redirect("/admin/types");
    }
    // 显示分类
    public function types(){
        $model = new Admin;
        $data = $model->getType();
        view("type/type",[
            "data"=>$data
        ]);
    }
    // 显示加分类
    public function addType(){
        $model = new Admin;
        $data = $model->getTypeTop();
        view("type/addtype",[
             "data"=>$data
        ]);
    }
    // 增加分类
    public function doAddType(){
        $model = new Admin;
        $model->fill($_POST);
        $data = $model->insert();
        if($data){
            redirect("/admin/types");
        }
    }
    // 三级联动
    public function ajax_get_cat(){
        $id = $_GET['id'];
        $model = new Admin;
        $data = $model->ajax_get_cat($id);
        echo json_encode($data);
    }
    //验证码
    // public function captcha(){
    //     $en = \libs\Captcha::getInstance();
    //     $en->en_captcha();
    // }
    // 退出登录
    public function logout(){
        $_SESSION = [];
        session_destroy();
        redirect("index/login");
    }
   
    // 显示主页
    public function index(){
        $model = new Index;
        $data['lookNum'] = $this->getLook();
        $data['lookUser'] = $model->ipNum();
        // var_dump($data);    
        view("admin/index",[
              'data'=>$data
        ]);
    }
    // 返回总浏览量和用户人数
    public function getLookNum(){
        $model = new Index;
        $data['lookNum'] = $this->getLook();
        $data['lookUser'] = $model->ipNum();
        echo json_encode($data);
    }
    // 访问次数
   public function getLook(){
       $model = new Index;
       $num = $model->IndexLookNum();
       return $num;    
   }
    // public function register()
    // {
    //     view('admin/register');
    // }
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



    // public function active_user(){
    //     $code = $_GET['code'];
    //     $redis = \libs\Predis::getinterface();
    //     $key = "temp_user:".$code;
    //     $data = $redis->get($key);
    //     if($data){
    //         $redis->del($key);
    //         $data = json_decode($data,true);
    //         $user = new User;
    //         $user->add($data['email'],$data['password']);
    //         header("Location:/admin/index");
    //     }else{
    //       echo " 激活码错误！";
    //     }
    // }
 
}