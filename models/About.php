<?php
namespace models;

class About extends Base
{
    // 设置这个模型对应的表
    protected $table = 'admin_user';
    // 设置允许接收的字段
    protected $fillable = ['email,password'];
}