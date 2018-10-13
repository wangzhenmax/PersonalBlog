<?php
namespace libs;

class Mail
{
    public $mailer;
    public function __construct()
    {
        // 从配置文件中读取配置
        // $config = config('email');
        // 设置邮件服务器账号
        $transport = (new \Swift_SmtpTransport("smtp.163.com",25))  // 邮件服务器IP地址和端口号
        ->setUsername("17600636164@163.com")       // 发邮件账号
        ->setPassword("xiaoxie123");      // 授权码
        // 创建发邮件对象
        $this->mailer = new \Swift_Mailer($transport);
    }

    /*
    $to:['邮箱地址'，'姓名']
    */
    public function send($title, $content, $to)
    {
        // 从配置文件中读取配置
        $config = config('email');
        // 创建邮件消息
         $config = config('email');
        // 创建邮件消息
        $message = new \Swift_Message();
        $message->setSubject($title)   // 标题
                ->setFrom(["17600636164@163.com" => "123123"])   // 发件人
                ->setTo([
                    "457340@qq.com","457340@qq.com"=>"123",
                ])   // 收件人
                ->setBody($content, 'text/html');     // 邮件内容及邮件内容类型

        // 如果是调试模式就写日志
            // 发送邮件
            $this->mailer->send($message);
        
    }
}