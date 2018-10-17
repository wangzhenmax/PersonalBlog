<?php
namespace models;

class Role extends Base
{
    // 设置这个模型对应的表
    protected $table = 'role';
    // 设置允许接收的字段
    protected $fillable = ['name'];
    // 添加 / 修改后
    public function _after_write(){
        $id = isset($_GET['id']) ? $_GET['id'] : $this->data['id'];
        // 修改时删原权限
        if(isset($_GET['id'])){
            $stmt = $this->_db->prepare("DELETE from role_privilege where role_id = ?");
            $stmt->execute([$_GET['id']]);
        }
        // 插入新权限
        $stmt  = $this->_db->prepare("INSERT INTO role_privilege(role_id,privilege_id) values(?,?)");
        foreach($_POST['privilege_id'] as $v){
            $stmt->execute([
                $id,
                $v
            ]);
        }
    }
    // 删除中间表的数据
    public function _after_delete(){
        $stmt =$this->_db->prepare("DELETE FROM role_privilege where role_id = ?");
        $stmt->execute([$_GET['id']]);
    }
    // 二维数组转换为一位数组
    public function getPriId($id){
        $stmt = $this->_db->prepare("SELECT privilege_id from role_privilege where role_id  = ? ");
        $stmt->execute([$id]);
        $data = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $ret = [];
        foreach($data as $v){
            $ret[] = $v['privilege_id'];
        }
        return $ret;
    }
}