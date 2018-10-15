<?php

namespace models;


class Admin extends Base
{
    // 设置这个模型对应的表
    protected $table = 'type';
    // 设置允许接收的字段
    protected $fillable = ['name','parent_id'];

    //修改分类
    public function update($name,$id){
        $stmt = $this->_db->prepare("UPDATE type set name = ?  where id = ?");
        return  $stmt->execute([
            $name,
            $id
        ]);
    }
    // 获取所有分类
    public function getTypeTop(){
        $stmt = $this->_db->prepare("SELECT * from type where id = 1 or id = 2 or id  = 3  ");
        $stmt->execute();
        $data  = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $data = $this->_tree($data);

    }
    public function getType(){
        $stmt = $this->_db->prepare("SELECT * from type ");
        $stmt->execute();
        $data  = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        return $data = $this->_tree($data);

    }
    public function _tree($data,$parent_id=0,$lv=0){
        static $arr = [];
        foreach($data as $v){
            if($parent_id==$v['parent_id']){
                $v['lv'] = $lv;
                $arr[] = $v;
                $this->_tree($data,$v['id'],$lv+1);
            }
        }
        return $arr;

    }

    //插入数据
    public function find($emails,$password){
        $stmt = $this->_db->prepare("SELECT * from admin_user where emails = ? AND password = ?");
        $stmt->execute([
         $emails,
         $password
        ]);
        $user = $stmt->fetch(\PDO::FETCH_ASSOC);
        if($user){
            $_SESSION['email']  = $emails;
            $_SESSION['id'] = $user['id'];
            // echo  $_SESSION['id'] ;die;
            return true;
        }else{
            return false;
        }
    }
    public function ajax_get_cat($id=0){
        $stmt = $this->_db->prepare("SELECT * from type where parent_id = ?");
        $stmt->execute([$id]);
        return $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
