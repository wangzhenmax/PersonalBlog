<?php
namespace controllers;

use models\Lists;

class ListsController{
    // 列表页
    public function list()
    {
        view('list/list');
    }

}