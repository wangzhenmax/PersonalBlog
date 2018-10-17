<?php
namespace controllers;

use models\Role;

class RoleController{
    // 列表页
    public function index()
    {
        $model = new Role;
        $data = $model->findAll([
            'fields'=>'a.*,GROUP_CONCAT(c.privilege_name) pri_list',
            'join'=>' a left join role_privilege b on a.id = b.role_id left join privilege c on privilege_id = c.id ',
            'groupby'=>'GROUP BY a.id',
        ]);
        view('role/index', $data);
    }

    // 显示添加的表单
    public function create()
    {
        $priModel = new \models\Privilege;
        $data = $priModel->tree();
        view('role/create',$data);
    }

    // 处理添加表单
    public function insert()
    {
        $model = new Role;
        $model->fill($_POST);
        $model->insert();
        redirect('/role/index');
    }

    // 显示修改的表单
    public function edit()
    {
        $id = $_GET['id'];
        $model = new Role;
        $name=$model->findOne($id);
        $priModel = new \models\Privilege;
        $data = $priModel->tree();
        $pri_id = $model->getPriId($id);
            view('role/edit', [
            'name'=>$name,
            'data' => $data, 
            'priId' =>$pri_id   
        ]);
    }

    // 修改表单的方法
    public function update()
    {
        $model = new Role;
        $model->fill($_POST);
        $model->update($_GET['id']);
        redirect('/role/index');
    }

    // 删除
    public function delete()
    {
        $model = new Role;
        $model->delete($_GET['id']);
        redirect('/role/index');
    }
}