<?php
namespace controllers;

use models\User;

class UserController{
    // 列表页
    public function index()
    {
        $model = new User;
        $data = $model->findAll();
        view('user/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        view('user/create');
    }

    // 处理添加表单
    public function insert()
    {
        $model = new User;
        $model->fill($_POST);
        $model->insert();
        redirect('/user/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new User;
        $data=$model->findOne($_GET['id']);
        view('user/edit', [
            'data' => $data,    
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $model = new User;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/user/index');
    }

    // 删除
    public function delete()
    {
        $model = new User;
        $model->delete($_GET['id']);
        redirect('/user/index');
    }
}