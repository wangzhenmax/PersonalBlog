<?php
namespace controllers;
use models\Index;
class IndexController {
    // ajax 点赞
    public function ajaxZan(){
         $model = new Index;
         $id = $_GET['id'];
         var_dump($model->ajaxZan($id));
    }

    public function index(){
      view("html/index");
    }
    // 详情页
     public function info()
    {
        $id = $_GET['id'];
        view("html/blogs/{$id}");
    }
    // 如果下一条没有 就往上一直拿
    public function getNext($id){
        $model = new Index;
        if($model->getLastOne() > $id )
        {
            $data = $model->getIdTitle($id-0+1);
            if(!$data){
                return $this->getNext($id-0+1);
            }
            return $data;
        }
        return false;
    }
    // 如果上一条没有 就往上一直拿
     public function getPre($id){
        $model = new Index;
        $data = $model->getIdTitle($id-1);
        if(!$data){
            if($id == 0)
                return false;
            return $this->getPre($id-1);
        }
        return $data;
    }
       // 显示登录
    public function login()
    {      
        view('admin/login');
    }
    // 前后端文章
    public function webPhp(){
         if(isset($_GET['type2'])){
            $cat_2 = $_GET['type2'];
           if($cat_2 == 2){
               view("html/web");
           }else if($cat_2==16){
               view("html/qita");
           }
           else{
               view("html/php");
           }
        }
    }
     // 列表页
    public function list()
    {
        $model = new Index;
        $data = [];
        // 如果是从分类过来的 取出分类文章
        if(isset($_GET['type3'])){
            $cat_3 = $_GET['type3'];
            $data = $model->getCat_3($cat_3);
        }
        // 如果是从 前后端 过来的 取出前后端文章
         // 让没有图片的文章 产生两种样式 1 无图 2 从内容中取出图
         foreach($data as $k=> $v){
             if($v['cover_md']==null && $v['cover_big']==null){
                $num = floor(rand(0,1));
                if($num==1){
                    $img = $this->getImgs($v['content'],0);
                    $data[$k]['blog_img'] = $img;
                }
            }
            if($v['cover_md']!=null){
            $data[$k]['cover_md'] = json_decode($v['cover_md']);
            };
        }
        view('list/list',[
              'data'=>$data
        ]);
    }
              // 判断是否有封面
    public function getImg($data){
        foreach($data as $k=>$v){
            $ret = json_decode($v['cover_md']);
                $data[$k]['cover_md'] = $ret[0];
             if($v['cover_md']==null&&$v['cover_big']==null){
                $_ret = $this->getImgs($v['content'],0);
                $data[$k]['blog_img'] = $_ret[0];
            }
        }
        return $data;
    }
      // 获取内容中的图片做封面
    public function getImgs($content,$order='ALL')
    {  
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg]))[\'|\"].*?[\/]?>/"; 
    preg_match_all($pattern,$content, $matches);
    $pattern="/(http:\/\/.*)\" alt/"; 
    preg_match_all($pattern,$matches[1][0], $matches1);
    return $matches1[1];
    }
    // 时间轴
     public function time()
    {
       view("html/indexJian");
    }
    // 类别
     public function share()
    {
        $type_id = $_GET['type'];
        $model = new Index;
        $data = $model->getType($type_id);
        view('share/share',[
            'data'=>$data
        ]);
    }
    // 自我介绍
    public function about()
    {
        view('about/about');
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
        // if(isset($_SESSION[$emails])){
        //     if($_SESSION[$emails]>=3){
        //         die("你已经错了三次了");
        //     }
        // }else{
        //     $_SESSION[$emails] = 0;
        // }
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