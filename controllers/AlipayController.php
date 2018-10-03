<?php
/* 支付宝支付的控制器 */
namespace controllers;

use Yansongda\Pay\Pay;

class AlipayController
{
    // 支付账号： iqlxca4499@sandbox.com
    // 配置
    public $config = [
        'app_id' => '2016091700531879',
        // 支付宝公钥
        'ali_public_key' => 'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA46ZZLYnk9IE7OrWnrS+cIoFWKsV+h3V0LLPjfxLSQAl+k/GXMu9P4nj4wc0uhBzwpLGyFrgj74fxpfb76wUbR/h7b6h2rHo//jRhEW6d4c25vPMzVMtBctgESItik0xOXQjDLWLtO/+gY+KnZpklgAkbdMpnQp2YZmQKBaf01ExhlDdbzOQQ8KSd5txpxJkrsJg7abY1ZNfMSA2YrFkKgXppu97VP8laVhwzwK7I45iYh5FaJGOFB9VkcELWB0wmnk4DilrTxJGsVEMlekjvrC/Kf8yFO7M8Ciij55bWxnTIysk2kT4738ShlGNi1OOaETK1bHcelZnkJiPkEVWonwIDAQAB',
        // 商户应用密钥
        'private_key' => 'MIIEowIBAAKCAQEA0dIYAbf3eo3HWDVxIXE6sOAF/PObeBXGoxUBQDj1aKHgOajfFr7Es78OOHvJZOFHeig2kqxPCXo83+EmFJXyP9lmxmMqEO+nyq3M3T+eJ3XyqtmJPra972/ohTfsQ0ete5JKMdjXsTneCAoPlJZOI02WLc5pwIXCgh38b/J1I0265WrQ8op0S6rh/qLMwqA4/QjH7p3cvcISQv3D8CBlyJeFDRCPkcyB4qrMnvyKGhyvqUolU+x8FITT8pDzbgfrcHQt2qnx4DXIyfopqexiVfvnblY63SmpqqGCNzE/JhMNmKuNa19t67imz4X+0u0SgwG35NNFPI/98CdWOG6D6wIDAQABAoIBAAeajIIrb42Ca9H3hdUHbHASfkUPcvfkGiG41iXEqqgTDbQpOYRyf2BgMRy856x/OX7kzC1+jxKx2ljmqOGgGXpU35Oa7mBUvwjqSX1PG+UkMhoc7gtWxgVSUePaBfbeNxw+TfAGgrKKfVBfRPdGhyhiXGnmjBCNCGJ8wbQw3ivcSXe90Aitl68BW2Qsz7aBGV1Zge+L3P6976uLUwJbtMMH5C7IzMV4VaTpD3kP8qy0mF5L6yXwJlaGZ3cJ3Dp0iH1pA5/johvAAefT1GOQYrcX+w9NiqIynL/puBy/jtO2AF2iYotrtJrUbpSwnRVWPxWlTm5UOUfeHE1QHcwRc3ECgYEA65rgW7i3ge2JesR2HdC2Vc05h25cZwIUIbY65UVIlwWpIqMBV+NF/fcPDXFLDDhrFVMp+7iErTf9vsLCwwTyZhR/AtiB+8gYuZeLLMCfIkXN0MIJlHf7PvFhgl5XPmaYnUPTBXBBNxiA/ih1ndtQKC9TDCKvdyhvEGZIOiByuEkCgYEA4/vTBkHy0D3lpSYaljonIEFJBfcrTgBZR866nj5UZjvoinBrv67WYAADLvFkxiNO97GbpC3b+bBcnWKKCZKAPQngJTK9yfeMcWk4GYp4qMhszjU8Kj/3ql7fM6S3jlq2r1HaxoUPxIKx2Ql0bo3V/5Kyb2+Ap19S0O2/TMttIpMCgYAPXXBYSvmcvZMDsKBLXXsmqVWhIW9hQF9zu2Cn5xbO0o2vNpY11xqPb3dJ1yOfzmYdY+kPA3+TlF01/ZmaAk6Una2Sz+/aLbh/EgT/jChUodzESoM5bYGzHybOy7xA04wMZYnzhtiZ8T0oVhcljlHx7PQrjG+JA9gKZ8E6GTeiEQKBgHduSJISojzs8Ayf9XWVaUHAcZyqawklrZ+scJ7NUDFuWNeNJST5VlxcJU7GLmCNxqSDamGLlJ0tApeAM4foMz3GmqFh/4J6KByRXk4i+CqTNEyiHq6TbA7YPF01gOMWKnWVj+JHeocbYEeuaEwyCVUlaExwQRltGllQ8tIsDzP5AoGBAIhg6I1Y5HkPTqAKtX/qek40Fv88hqYLZnGOnygudZ0aHY2DpJmSACEyzwSiR7GQtcoYdHVCCbmym+D0PfW90b3uwKPGqXQvQEAI8OKw8L4EOoRtx+0VMMVD26vRJ3ZcNpKABtlFQ5liBOja+gG2kmKsqIrfq9UwASyKJknJIIDa',
        
        // 通知地址
        'notify_url' => 'http://https.tunnel.echomod.cn/alipay/notify',
        // 'notify_url' => 'http://requestbin.fullcontact.com/185uwba1',
        // 跳回地址
        'return_url' => 'http://localhost:8888/alipay/return',
        
        // 沙箱模式（可选）
        'mode' => 'dev',
    ];


    // 跳转到支付宝
    public function pay()
    {
           $sn = $_POST['sn'];
        $order = new \models\Order;
        $data = $order->findBysn($sn);
        if( $data['status'] == 0 )
        {
            $alipay = Pay::alipay($this->config)->web([
                'out_trade_no' => $sn,
                'total_amount' => $data['money'],
                'subject' => '智聊系统用户充值 ：'.$data['money'].'元',
            ]);
            $alipay->send();
        }
        else
        {
            die('订单状态不允许支付~');
        }
    }
    // 支付完成跳回
    public function return()
    {
        $data = Pay::alipay($this->config)->verify();
        echo '<h1>支付成功！</h1> <hr>';
        message("支付成功，点击刷新即可查看余额",2,"/");
    }
    // 接收支付完成的通知
    // public function test(){
    //    $order = new \models\Order;
    //             $order->startTrans();
    //             $orderInfo = $order->findBysn(259022968443629568);
    //             if($orderInfo['status'] == 0)
    //             {
    //                 $data1 = $order->setPaid(259022968443629568);
    //                 $user = new \models\User;
    //                 $data2 = $user->addMoney($orderInfo['money'], $orderInfo['user_id']);
    //                 if($data1&&$data2){
    //                     $order->commit();
    //                     echo 1;
    //                 }else{
    //                     $order->rollback();
    //                     echo 2;
    //                 }
    //             }
    // }
    public function notify()
    {
        $alipay = Pay::alipay($this->config);

        try{
            $data = $alipay->verify(); // 是的，验签就这么简单！

            if($data->trade_status == 'TRADE_SUCCESS' || $data->trade_status == 'TRADE_FINISHED')
            {
                $order = new \models\Order;
                $order->startTrans();
                $orderInfo = $order->findBysn($data->out_trade_no);
                if($orderInfo['status'] == 0)
                {
                    $data1 = $order->setPaid($data->out_trade_no);
                    $user = new \models\User;
                    $data2 = $user->addMoney($orderInfo['money'], $orderInfo['user_id']);
                    if($data1&&$data2){
                        $order->commit();
                    }else{
                        $order->rollback();
                    }
                }
            }
        } catch (\Exception $e) {
            die($e->getMessage());
        }
        $alipay->success()->send();
    }

    // 退款
    public function refund()
    {
        $refundNo = md5( rand(1,99999) . microtime() );
        $sn = $_POST['sn'];
        $user = new \models\Order;
        $money = $user->getMoney($sn);
        try{
            $order = [
                'out_trade_no' => $sn,    // 退款的本地订单号
                'refund_amount' =>$money,              // 退款金额，单位元
                'out_request_no' => $refundNo,     // 生成 的退款订单号
            ];

            // 退款
            $ret = Pay::alipay($this->config)->refund($order);

            if($ret->code == 10000)
            {
                echo '退款成功！';
            }
            else
            {
                echo '失败' ;
            }
        }
        catch(\Exception $e)
        {
            var_dump( $e->getMessage() );
        }
    }
}