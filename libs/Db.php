<?php
namespace libs;
class Db
{
    private  static  $obj=null;
    private  $pdo;
    private function __clone(){}
    private function __construct(){
        $this->pdo= new \PDO("mysql:host=118.89.221.52;dbname=blog",'root','wang457340');
        // $this->pdo= new \PDO("mysql:host=127.0.0.1;dbname=blog",'root','123456');
        $this->pdo->exec("SET NAMES utf8");
    }
    public static function getDb(){
       if(self::$obj==null){
            return self::$obj = new self;
        }
        return self::$obj;
    }
    //执行预处理
    public function prepare($sql){
        return $this->pdo->prepare($sql);
    }
    
    //执行exec相关的sql
    public function exec($sql){
        return $this->pdo->exec($sql);
    }
    public function query($sql){
        return $this->pdo->query($sql);
    }
    // 获取最新添加的记录的ID
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    

    

}