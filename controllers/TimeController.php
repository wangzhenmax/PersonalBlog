<?php
namespace controllers;

use models\Time;

class TimeController{
    // 列表页
     public function time()
    {
        view('time/time');
    }

}