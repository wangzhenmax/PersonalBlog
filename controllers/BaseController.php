<?php
namespace controllers;
class BaseController { 
    public function __construct(){
        // var_dump($_SESSION);die;
        if(!isset($_SESSION['id'])){
            redirect("/index/login");
        }
    }
}
