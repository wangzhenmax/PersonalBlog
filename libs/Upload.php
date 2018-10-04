<?php
namespace libs;
class Upload
{
    private  function __construct(){}
    private   function __clone(){}
    private static $obj = null;
    public static function make(){
        if(self::$obj==null){
            self::$obj = new self;
        }
        return self::$obj;
    }

    private $_root = ROOT."public/upload/";
    private $_ext = ['/image/jpeg','/image/jpg','/image/ejpeg','/image/png','/image/gif','/image/bmp'];
    private $_maxSize = 1024*1024*2;
    private $_file;
    private $_subDir;
 

    public function upload($name,$subDir){
        $this->_file = $_FILES[$name];
        $this->_subDir = $subDir;
        if(!$this->_checkType()){
            die('type error');
        }
        if(!$this->_checkSize()){
            die("sieze error");
        }
        $dir = $this->_makeDir();
        $name = $this->_makeName();
        move_uploaded_file($this->_file['tmp_name'],$this->_root.$dir.$name);
        return $dir.$name;
    }
    //生成二级目录以及当天时间目录
    private function _makeDir(){
        $dir = $this->_root.$this->_subDir."/".date('Ymd')."/";
        if(!is_dir($dir)){
            mkdir($dir,true,0777);
        }
        $dir = $this->_subDir."/".date('Ymd')."/";
        return $dir;
    }
    //生成随机的名字以及拼接后缀
    private function _makeName(){
        $name = md5(rand(1,9999));
        $ext = strrchr($this->_file['name'],".");
        return $name.$ext;
    }
    //判断类型是否正确
    private function _checkType(){
        return in_array($this->_file['type'],$this->_ext);
    }
    //判断大小是否合法
    private function _checkSize(){
        return $this->_file['size'] < $this->_maxSize;
    }
    
}
