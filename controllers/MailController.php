<?php
namespace controllers;

use libs\Mail;

class MailController
{
    public function send()
    {
        $redis = \libs\Predis::getinterface();
        $mailer = new Mail;
        ini_set('default_socket_timeout', -1);
        echo "启动中....等待接收...";
        while(true)
        {
            $data = $redis->brpop('email', 0);
            $message = json_decode($data[1], TRUE);
            $mailer->send($message['title'], $message['content'], $message['from']);
            echo "发送邮件成功！继续等待下一个。\r\n";
        }
    }
}