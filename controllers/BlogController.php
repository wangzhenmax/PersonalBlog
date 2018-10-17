<?php
namespace controllers;

use models\Blog;
use models\Admin;

class BlogController  extends BaseController {
 
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
   
}