<?php
namespace controllers;

use models\Blog;
use models\Admin;
use models\Index;

class BlogController extends BaseController{
    
    // 列表页
    public function index()
    {
        $model = new Blog;
        $data = $model->search();
        view('blog/index', 
        [
            'data'=>$data['data'],
            'btns'=>$data['btns']
        ]);
    }
    // 轮播 推荐
    public function lunbo(){
        $model = new Index;
        $data['lunbo'] = $model->getBanner();
        $data['tuijian'] = $model->getRecom();
        view('blog/lunbo',[
            'data'=>$data
        ]);
    }
    // 显示添加的表单
    public function create()
    {
        $model = new Admin;
        $type = $model->ajax_get_cat();
        view('blog/create',[
            'type'=>$type
        ]);
    }
    // 处理添加表单
    public function insert()
    {
        $model = new Blog;
        $model->fill($_POST);
        $id = $_SESSION['id'];
        $data = $model->insert($id);
        if($data){
            redirect('/blog/index');
        }else{
            echo "失败!";
        }
    }
    // 显示修改的表单
    public function edit()
    {
        $model = new Blog;
        $data=$model->findOne($_GET['id']);
        if($data['cover_md']){
            $data['cover_md'] = json_decode($data['cover_md']);
        };
        $Adminmodel = new Admin;
        $type = $Adminmodel->ajax_get_cat();
        view('blog/edit', [
            'data' => $data,    
            'type' =>$type
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $id = $_GET['id'];
        $model = new Blog;
        $model->fill($_POST);
        $data = $model->update($id);
        redirect('/blog/index');
    }

    // 删除
    public function delete()
    {
        $model = new Blog;
        $model->delete($_GET['id']);
        redirect('/blog/index');
    }



    public function indexHtml(){
        $this->indexJianHtml();
        $this->sidebarHtml();
        $this->webHtml();
        $this->phpHtml();
        $this->qitaHtml();
        $model = new Index;
        $data = $model->blogAll();
        $banner = $model->getBanner();
        //获取排行榜/特别推荐
        foreach($data as $k=> $v){
            if($v['cover_big']=='') {
                if($v['cover_md']!=''){
                     $data[$k]['cover_md'] = json_decode($v['cover_md']);
                echo 0;
                }else{
                     echo 2;
                $num = floor(rand(0,1));
                    if($num==1){
                        $img = $this->getImgs($v['content'],0);
                        $data[$k]['blog_img'] = $img;
                    }
                }
            }
        }
        ob_start();
        view("index.index",[
            'data'=>$data,
            'banner' =>$banner,
        ]);
        $str = ob_get_contents();
        file_put_contents(ROOT.'views/html/index.html',$str);
        redirect('/');
    }
    public function indexJianHtml(){
         $model = new Index;
        $data = $model->getAllBlog();
        ob_start();
        view('time/time',[
            'data'=>$data
        ]);
        $str = ob_get_contents();
        file_put_contents(ROOT.'views/html/indexJian.html',$str);
    }
      public function sidebarHtml(){
        $model = new Index;
        $recom = $model->getRecom();
        $top = $model->getTop();
        $top = $this->getImg($top);
        $recom = $this->getImg($recom);
        $sidebar = [
            'top'=>$top,
            'recom'=>$recom
        ];
        ob_start();
        view("comment/sidebar",[
            'sidebar'=>$sidebar
        ]);
        $str = ob_get_contents();
        file_put_contents(ROOT."views/comment/sidebarHtml.html",$str);
    }
     //  获取前端的文章
     public function webHtml(){
         $model = new Index;
         $data = $model->getTypeWeb();
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
         ob_start();
         view('list/list',[
              'data'=>$data
        ]);
        $str = ob_get_contents();
        file_put_contents(ROOT."views/html/web.html",$str);
     }
     //  获取后端的文章
     public function phpHtml(){
         $model = new Index;
         $data = $model->getTypePhp();
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
         ob_start();
         view('list/list',[
              'data'=>$data
        ]);
        $str = ob_get_contents();
        file_put_contents(ROOT."views/html/php.html",$str);
     }
     //  获取其他的文章
     public function qitaHtml(){
         $model = new Index;
         $data = $model->getTypeQita();
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
         ob_start();
         view('list/list',[
              'data'=>$data
        ]);
        $str = ob_get_contents();
        file_put_contents(ROOT."views/html/qita.html",$str);
     }
           // 判断是否有封面
    public function getImg($data){
        foreach($data as $k=>$v){
            $ret = json_decode($v['cover_md']);
                $data[$k]['cover_md'] = $ret[0];
             if($v['cover_md']==null&&$v['cover_big']==null){
                $_ret = $this->getImgs($v['content']);
                $data[$k]['blog_img'] = $_ret;
            }
        }
        return $data;
    }
      // 获取内容中的图片做封面
      // <p><img src="/ueditor/php/upload/image/20181019/1539918581167759.png" title="1539918581167759.png" alt="image.png"/></p><p>上面是一张截图</p>
    public function getImgs($content,$order='ALL')
    {  
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/"; 
      preg_match($pattern,$content,$match);  
      return $match[1]; 
    }

     public function getImgg($content,$order='ALL')
    {  
    $pattern="/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/"; 
    preg_match_all($pattern,$content, $matches);
    $pattern="/(http:\/\/.*)\" alt/"; 
    preg_match_all($pattern,$matches[1][0], $matches1);
    return $matches1[1];
    }
   
}