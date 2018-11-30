<?php
namespace controllers;

use models\Admin_user;

class Admin_userController{
    // 列表页
    public function index()
    {
        $model = new Admin_user;
        $data = $model->findAll();
        view('admin_user/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        view('admin_user/create');
    }

    // 处理添加表单
    public function insert()
    {
        $model = new Admin_user;
        $model->fill($_POST);
        $model->insert();
        redirect('/admin_user/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $model = new Admin_user;
        $data=$model->findOne($_GET['id']);
        view('admin_user/edit', [
            'data' => $data,    
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $model = new Admin_user;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/admin_user/index');
    }

    // 删除
    public function delete()
    {
        $model = new Admin_user;
        $model->delete($_GET['id']);
        redirect('/admin_user/index');
    }
}