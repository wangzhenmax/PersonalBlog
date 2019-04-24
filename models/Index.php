<?php
namespace models;
class   Index extends Base
{
    // 设置这个模型对应的表
    protected $table = 'blog';
    // 主页文章
    public function blogAll(){
        return $data = $this->getAll("SELECT a.*, b.name as blogType  from $this->table as a left join type as b on a.cat_3 = b.id where a.banner != 1 AND a.recommend != 1 AND is_show = 1 order by a.id desc ");
    }
    // 获取轮播图的文章
    public function getBanner(){
        return $data = $this->getAll("SELECT title , id, cover_big from $this->table where cover_big != ''   AND banner = 1 AND is_show = 1 order by id desc ");
    }
    // 获取推荐的文章
    public function getRecom(){
        return $data = $this->getAll("SELECT content,title , id ,cover_big ,cover_md from $this->table where recommend = 1 AND is_show = 1 order by id desc ");
    }
    // 获取点击排行
    public function getTop(){
        return $data = $this->getAll("SELECT content,title , id, cover_big ,cover_md from $this->table where is_show = 1 order by look desc limit 5");
    }
    // 简版主页
    public function getAllBlog(){
        return $data = $this->getAll("SELECT id, update_at ,title from $this->table where is_show = 1 order by created_at desc");
    }
    // 获取分类
    public function getType($id){
        return $data = $this->getAll("SELECT name ,id,cover ,parent_id from type where parent_id = {$id}");
    }
    // 获取分类文章
    public function getCat_2($id){
        return  $data = $this->getAll("SELECT a.*, b.name as blogType  from $this->table as a left join type as b on a.cat_3 = b.id WHERE cat_2 = {$id} AND is_show = 1 order by a.id desc  ");
    }
    public function getCat_3($id){
        return  $data = $this->getAll("SELECT a.*, b.name as blogType  from $this->table as a left join type as b on a.cat_3 = b.id WHERE cat_3 = {$id} AND is_show = 1 order by a.id desc  ");
    }
    // 获取指定文章()
    public function getBlog($id){
        return $data = $this->getOne("SELECT a.* , b1.name cat2 , b2.name cat3  FROM $this->table as a  left join type as b1 on a.cat_2 = b1.id left join type as b2 on a.cat_3 = b2.id   where a.id = {$id} AND a.is_show = 1");
    }
    // 获取文章的id 标题
    public function getIdTitle($id){
        return $data = $this->getOne("SELECT id , title FROM $this->table where id = {$id} AND is_show = 1");
    }
    // 获取相关文章
    public function getRele($id){
        return $data = $this->getAll("SELECT id , title from $this->table where cat_3 = {$id} AND is_show = 1 order by id desc limit 6");
    }
    // 获取前端页面的文章
    public function getTypeWeb(){
        return $data = $this->getAll("SELECT a.*, b.name as blogType  from $this->table as a left join type as b on a.cat_3 = b.id WHERE cat_2 = 2 AND is_show = 1 order by a.id desc  ");
    }
    // 获取前端页面的文章
    public function getTypePhp(){
        return $data = $this->getAll("SELECT a.*, b.name as blogType  from $this->table as a left join type as b on a.cat_3 = b.id WHERE cat_2 = 3 AND is_show = 1 order by a.id desc  ");
    }
    // 获取其他的文章
    public function getTypeQita(){
        return $data = $this->getAll("SELECT a.*, b.name as blogType  from $this->table as a left join type as b on a.cat_3 = b.id WHERE cat_2 = 16 AND is_show = 1 order by a.id desc  ");
    }
    // 增加访问量
    public function addLook($id){
        $data = $this->_db->prepare("UPDATE $this->table set look =  look + 1 where id = {$id} AND is_show = 1"); return $data->execute();
    }
    // ajax 点赞
    public function ajaxZan($id){
        $data = $this->_db->prepare("UPDATE $this->table set likes =  likes + 1 where id = {$id} AND is_show = 1"); return $data->execute();
    }
    // 获取所有文章制作静态化
    public function getAllBlogHtml(){
        return $data = $this->getAll("SELECT id from  $this->table where is_show = 1 order by id desc");
    }
    // 获取文章赞
    public function getLike($id){
        return $data = $this->getAll("SELECT likes FROM $this->table where id = {$id} AND is_show = 1");
    }
    // 判断是否来访过
    public function hasIp($ip){
         return $data = $this->getAll("SELECT ip FROM user_ip where ip like '{$ip}' limit 1");
    }
    // 记录Ip地址
    public function addIp($cip,$time){
        $data = $this->_db->prepare( "INSERT INTO  user_ip VALUES(null,'{$cip}',{'$time'})"); return $data->execute();
    }
    // 返回IP数量
    public function ipNum(){
        return $data = $this->getAll("SELECT COUNT(*) as num FROM user_ip");
    }
    // 网站访问量 
    public function IndexLookNum(){
        return $data = $this->getAll("SELECT num FROM look_num");
    }
    // 增加访问量
    public function addIndexLookNum(){
        $data = $this->_db->prepare("UPDATE look_num set num =  num + 1"); return $data->execute();
    }
    // 用户访问日志
    public function addLog($str){
        $time=date("Y-m-d H:i:s");
        $data = $this->_db->prepare( "INSERT INTO  user_log VALUES(null,'{$str}',null)"); return $data->execute();
    }
    // 返回文章标题
    public function infoTitle($id){
        return $data = $this->getAll("SELECT title  FROM $this->table where id = {$id}");
    }
}
