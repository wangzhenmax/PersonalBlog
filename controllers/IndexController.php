<?php
namespace controllers;
use models\Index;
class IndexController {
    public function index(){
        $model = new Index;
        $data = $model->blogAll();
        $banner = $model->getBanner();
        //获取排行榜/特别推荐
        $sidebar = $this->sidebar();
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
            'data'=>$data,
            'banner' =>$banner,
            'sidebar'=>$sidebar
        ]);
    }

    // 详情页
     public function info()
    {
        $id = $_GET['id'];
        $model = new Index;
        $data = $model->getBlog($id);
        if(!$data)
            return false;
        $pre =$this->getPre($id);
        $next = $this->getNext($id);
        $relevant = $model->getRele($data['cat_3']);
        $sidebar = $this->sidebar();
        $data = [
            "pre" => $pre?: null,
            "current" => $data?: null,
            "next" => $next?: null,
            "relevant"=>$relevant ? :null,
        ];
        view('info/info',[
            'sidebar'=>$sidebar,
            "data"=>$data
        ]);
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
     // 列表页
    public function list()
    {
        $sidebar = $this->sidebar();
        $model = new Index;
        $data = [];
        // 如果是从分类过来的 取出分类文章
        if(isset($_GET['type3'])){
            $cat_3 = $_GET['type3'];
            $data = $model->getCat_3($cat_3);
        }
        // 如果是从 前后端 过来的 取出前后端文章
        if(isset($_GET['type2'])){
            $cat_2 = $_GET['type2'];
            $data = $model->getCat_2($cat_2);
        }
         // 让没有图片的文章 产生两种样式 1 无图 2 从内容中取出图
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
        view('list/list',[
              'sidebar'=>$sidebar,
              'data'=>$data
        ]);
    }
    // 时间轴
     public function time()
    {
        $model = new Index;
        $data = $model->getAllBlog();
        view('time/time',[
            'data'=>$data
        ]);
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
    // 返回sidebar所需数组
    public function sidebar(){
        $model = new Index;
        $recom = $model->getRecom();
        $top = $model->getTop();
        $top = $this->getImg($top);
        $recom = $this->getImg($recom);
        return $sidebar = [
            'top'=>$top,
            'recom'=>$recom
        ];
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