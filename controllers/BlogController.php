<?php
namespace controllers;

use models\Blog;

class BlogController{
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
        $model = new Blog;
        $type = $model->getType();
        view('blog/create',[
            'type'=>$type
        ]);
    }

    // 处理添加表单
    public function insert()
    {
        $model = new Blog;
        $data = $model->add();
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
        $type = $model->getType();
        view('blog/edit', [
            'data' => $data,    
            'type' =>$type
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        
        $model = new Blog;
        $model->update($_GET['id']);
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