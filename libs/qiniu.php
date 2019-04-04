<?php
require('./vendor/autoload.php');

use Qiniu\Storage\UploadManager;
use Qiniu\Auth;

$pdo = new \PDO("mysql:host=118.89.221.52;dbname=blog",'root','123456');

$client = new \Predis\Client([
    'scheme' => 'tcp',
    'host'   => '118.89.221.52',
    'port'   => 6379,
]);

// 设置 socket 永不超时
ini_set('default_socket_timeout', -1); 

// 上传七牛云
$accessKey = '9lArYEr8OIdogQJYJvCw_eUd_H-JiugM_ukcocM7';
$secretKey = 'rFmjEXGlbhoPQc938uyCezxuW9wFC1zpHI6VqE5Q';
$domain = '//cdn.www.nbplus.wang';
$domains = '//cdn.nbplus.wang';
// 配置参数
$bucketName = 'vue-shop';   // 创建的 bucket(新建的存储空间的名字)

$upManager = new UploadManager();

// 登录获取令牌
$expire = 86400 * 3650; // 令牌过期时间10年
$auth = new Auth($accessKey, $secretKey);
$token = $auth->uploadToken($bucketName, null, $expire);
// 循环监听一个列表
while(true)
{
    echo "监听中.....";
    // 从队列中取数据，设置为永久不超时（如果队列里面是空的，就一直阻塞在这）
    $rawdata = $client->brpop('jxshop:niqui', 0);
    // 处理数据
    $data = unserialize($rawdata[1]); // 转成数组
    // 如果只上传一张图片
    if($data['cover_big']){
        echo " 上传了一张图片 | ";
        // 获取文件名
        $name = ltrim(strrchr($data['cover_big'], '/'), '/');
        // 上传的文件
        $file = dirname(__FILE__)."/../public".$data['cover_big'];
        list($ret, $error) = $upManager->putFile($token, $name, $file);
        // 判断是否成功
        if ($error !== null) {
            // 再将数据放到列表中（lpush：从左侧放）
            $client->lpush('jxshop:niqui', $rawdata[1]); 
        } else {
            // 更新数据库
            $new = $domains.'/'.$ret['key'];
            $sql = "UPDATE blog SET cover_big='{$new}' WHERE id=".$data['id'];
            $pdo->exec($sql);
            // 删除本地文件
            echo 'ok';
        }
    }
    // 如果上传多张图片
    else
    {
        echo " 上传了三张图片 | ";
        
        $cover_md = json_decode($data['cover_md']);
        $coverMd = [];
        foreach($cover_md as $v){
             // 获取文件名
            $name = ltrim(strrchr($v, '/'), '/');
            // 上传的文件
            $file = dirname(__FILE__)."/../public".$v;
            list($ret, $error) = $upManager->putFile($token, $name, $file);
            // 判断是否成功
            if ($error !== null) {
                // 如果失败，重新将数据放回队列
                $client->lpush('jxshop:niqui', $rawdata[1]);            
            } else {
                $new = $domains.'/'.$ret['key'];
                $coverMd[] = $new;
            }
        }
         // 更新数据库
        $cover = json_encode($coverMd);
        $sql = "UPDATE blog SET cover_md='{$cover}' WHERE id=".$data['id'];
        $d = $pdo->exec($sql);
        if($d){
            echo "ok";
        }else{
            echo "不Ok";
        }
    }
}
