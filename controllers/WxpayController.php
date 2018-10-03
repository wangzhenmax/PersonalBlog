<?php
namespace controllers;

use Yansongda\Pay\Pay;
class WxpayController
{
    protected $config = [
        'app_id' => 'wx426b3015555a46be', // 公众号 APPID
        'mch_id' => '1900009851',
        'key' => '8934e7d15453e97507ef794cf7b0519d',
        'notify_url' => 'http://https.tunnel.echomod.cn/wxpay/notify',
    ];

    public function pay()
    {
        $sn = $_POST['sn'];
        // 取出订单信息
        $order = new \models\Order;
        // 根据订单编号取出订单信息
        $data = $order->findBysn($sn);
        if($data['status']==0){
           $pay = Pay::wechat($this->config)->scan([
            'out_trade_no' => $data['sn'],
            'total_fee' => $data['money']*100, // **单位：分**
            'body' => '智聊系统用户微信支付：'.$data['money'].'元',
        ]);
         if($pay->return_code=="SUCCESS"&&$pay->result_code=='SUCCESS'){
             //字符串转成图片
            view('users.wximage', [
                    'code' => $pay->code_url,
                    'sn' => $sn,
                ]);
         };
        }else{
            die("订单出错!");
        }
    }

    public function notify()
    {
        $log = new \libs\Log("wx");
        $log->log("调用了notify");
        $pay = Pay::wechat($this->config);

        try{
            $data = $pay->verify(); // 是的，验签就这么简单！
            $log->log("执行了try");
            if($data->result_code == 'SUCCESS' && $data->return_code == 'SUCCESS')
            {
                $order = new \models\Order;
                $orderInfo = $order->findBysn($data->out_trade_no);
                if($orderInfo['status'] == 0)
                {
                    $order->startTrans();
                    $data1 = $order->setPaid($data->out_trade_no);
                    $user = new \models\User;
                    $data2 = $user->addMoney($orderInfo['money'], $orderInfo['user_id']);
                    if($data1&&$data2){
                        $order->commit();
                        $log->log("数据库更新完毕");
                    }else{
                        $order->rollback();
                        $log->log("执行了回滚");
                    }
                }
            }

        } catch (Exception $e) {
            $log->log("出错了:". $e->getMessage());
        }
        
        $pay->success()->send();
    }
}