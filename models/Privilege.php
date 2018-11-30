<?php
namespace models;

class Privilege extends Base
{
    // 设置这个模型对应的表
    protected $table = 'privilege';
    // 设置允许接收的字段
    protected $fillable = ['privilege_name','path','parent_id'];
}