<?php
namespace models;

class Admin extends Base
{
    // 设置这个模型对应的表
    protected $table = 'admin_user';
    // 设置允许接收的字段
    protected $fillable = ['emails','password'];
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
            return true;
        }else{
            return false;
        }
    }
}
