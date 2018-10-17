<?php
namespace controllers;
use models\Blog;
class IndexController {
    public function index(){
        $model = new Blog;
        $data = $model->blogAll();
        foreach($data as $k=> $v){
            $num = floor(rand(0,1));
            if($num=1){
                $img = $this->getImgs($v['content'],0);
                $data[$k]['blog_img'] = $img;
            }
            if($v['cover_md']!=null){
            $data[$k]['cover_md'] = json_decode($v['cover_md']);
            };
        }
     

        view("index.index",[
            'data'=>$data
        ]);
    }
    // 列表页
     public function info()
    {
        view('info/info');
    }
       // 显示登录
    public function login()
    {      
        view('admin/login');
    }
     // 列表页
    public function list()
    {
        view('list/list');
    }
    // 时间轴
     public function time()
    {
        view('time/time');
    }
    // 类别
     public function share()
    {
        view('share/share');
    }
    // 自我介绍
    public function about()
    {
        view('about/about');
    }
     public function getImgs($content,$order='ALL'){  
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/"; 
    preg_match_all($pattern,$content, $matches);
    $pattern="/(http:\/\/.*)\" alt/"; 
    preg_match_all($pattern,$matches[1][0], $matches1);
    return $matches1[1];
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