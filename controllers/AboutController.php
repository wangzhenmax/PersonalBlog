<?php
namespace controllers;

use models\About;

class AboutController{
    // 列表页
    public function about()
    {
        view('about/about');
    }
}