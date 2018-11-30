<?php
namespace libs;
class Captcha{
    //用于生成英文的验证码
   // $n 验证码个数
   //$font 字母大小
   //$imgWidth  验证码幕布宽
   //$imgHeight  验证码幕布高度

   		private static $instance;
		private function __clone(){
        }
        private function __construct(){

        }
        public static function getInstance(){
			if(!Captcha::$instance  instanceof self){
				Captcha::$instance = new self;
			}
			return Captcha::$instance;
		}
    static function en_captcha($n=4,$font=20,$imgWidth=200,$imgHeight=60){
        session_start();
        header("Content-type:image/png");
        $xx =0;//越大字母越靠右
        $yy =10;//越大字母越向下

        //准备工作
        //1、无序的数组（小写字母、大写字母、0-9数字）
        $arr = array_merge(range('a','z'),range('A','Z'),range(0,9));
        // 打乱顺序
        shuffle($arr);
        // 从数组中随机取4为
        // array_rand($arr,$n);  一次性从$n中随机产生 $n个下标，返回值是一个数组
        $indexArr = array_rand($arr,$n);  //随机产生4个下标放到 $indexArr数组中
        // var_dump($indexArr);
        //遍历 随机下标的数组，并且根据下标 依次从 $arr中取出随机的字符
        $str = "";
        foreach ($indexArr as $value) {
            $str .= $arr[$value];
        }
        $_SESSION['validateCode'] = $str;

        //2、创建画布
        $img = imagecreatetruecolor($imgWidth,$imgHeight);
        //3、创建背景颜色
        $backColor = imagecolorallocate($img,mt_rand(0,150),mt_rand(0,150),mt_rand(0,150));
        //4、填充背景
        imagefill($img, 0, 0, $backColor);
        
        //6、绘制文字内容到画布上
        $fontWidth = imagefontwidth($font);
        $fontHeight = imagefontheight($font);
        //字符串的宽度
        $strWidth = $fontWidth * strlen($str);
        //计算空白,并且四舍五入
        $space =  round($imgWidth/($n+1));
        for($i=1;$i<=$n;$i++){
            $x = $i*$space;
            $y = mt_rand($font,$imgHeight-$fontHeight);
            //5、创建画笔颜色
            $fontColor = imagecolorallocate($img, mt_rand(155,255), mt_rand(155,255), mt_rand(155,255));
            //绘制字符串
            // imagestring($img, $font, $x, $y, $str[$i-1], $fontColor);
            // imagettftext(画布, 字体大小(像素), 角度, 开始的x, 开始y, 颜色, "字体文件", 字符串)
            imagettftext($img, $font, mt_rand(-30,60), $x+$xx, $y+$yy, $fontColor, "font/arial.ttf", $str[$i-1]);
        }

        //7、绘制一些随机的线条
        for($i=0;$i<10;$i++){
            //定义线的颜色
            $lineColor = imagecolorallocate($img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
            imageline($img, mt_rand(0,$imgWidth), mt_rand(0,$imgHeight), mt_rand(0,$imgWidth), mt_rand(0,$imgHeight), $lineColor);
        }

        //8、添加噪点
        for($i=0;$i<$imgWidth*$imgHeight*0.05;$i++){
            //定义点的颜色
            $pointColor = imagecolorallocate($img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
            imagesetpixel($img, mt_rand(0,$imgWidth), mt_rand(0,$imgHeight), $pointColor);
        }

        ob_clean();
        imagepng($img);

    }

    //用于生成英文的验证码
    // $n 验证码个数
    //$font 字母大小
    //$imgWidth  验证码幕布宽
    //$imgHeight  验证码幕布高度
    static function zh_captcha($n=4,$font=20,$imgWidth=200,$imgHeight=60){
        
        session_start();
        header("Content-type:image/png");
        //需求：实现一个可以定制位数的图形验证码
        $n = 4;
        $font = 20;
        $imgWidth = 200;
        $imgHeight = 60;
        //定义一个中文的字符串
        $string = "们以我到他会作时要动国产的一是工就年阶义发成部民可出能方进在了不和有大这主中人上为来分生对于学下级地个用同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然如应形想制心样干都向变关问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩康遵牧遭幅园腔订香肉弟屋敏恢忘编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航衣孙龄岭骗休借"; 

        //字符串转换为 数组
        // str_split(字符串,长度); 返回值是一个数组
        $arr = str_split($string,3);
        // array_rand(数组,$n个数)  随机从数组中取出4个下标,返回值还是数组
        $indexArr = array_rand($arr,$n);

        //遍历，根据随机的下标依次取出对应的汉字
        $str = array();  //$str 是一个数组了
        foreach ($indexArr as $value) {
            //依次向数组中放入内容
            $str[]= $arr[$value];
        }
        //数组转换为字符串
        $code = implode($str, "");
        //把中文的字符串保存到session中
        $_SESSION['validateCode'] = $code;

        //2、创建画布
        $img = imagecreatetruecolor($imgWidth,$imgHeight);
        //3、创建背景颜色
        $backColor = imagecolorallocate($img,mt_rand(0,150),mt_rand(0,150),mt_rand(0,150));
        //4、填充背景
        imagefill($img, 0, 0, $backColor);
        
        //6、绘制文字内容到画布上
        $fontWidth = imagefontwidth($font);
        $fontHeight = imagefontheight($font);
        //字符串的宽度
        $strWidth = $fontWidth * strlen($str);
        //计算空白,并且四舍五入
        $space =  round($imgWidth/($n+1));
        for($i=1;$i<=$n;$i++){
            $x = $i*$space;
            $y = mt_rand($font,$imgHeight-$fontHeight);
            //5、创建画笔颜色
            $fontColor = imagecolorallocate($img, mt_rand(155,255), mt_rand(155,255), mt_rand(155,255));
            //绘制字符串
            // imagestring($img, $font, $x, $y, $str[$i-1], $fontColor);
            // imagettftext(画布, 字体大小(像素), 角度, 开始的x, 开始y, 颜色, "字体文件", 字符串)
            imagettftext($img, $font, mt_rand(-30,60), $x, $y+5, $fontColor, "font/msyh.ttc", $str[$i-1]);
        }

        //7、绘制一些随机的线条
        for($i=0;$i<10;$i++){
            //定义线的颜色
            $lineColor = imagecolorallocate($img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
            imageline($img, mt_rand(0,$imgWidth), mt_rand(0,$imgHeight), mt_rand(0,$imgWidth), mt_rand(0,$imgHeight), $lineColor);
        }

        //8、添加噪点
        for($i=0;$i<$imgWidth*$imgHeight*0.05;$i++){
            //定义点的颜色
            $pointColor = imagecolorallocate($img, mt_rand(0,255), mt_rand(0,255), mt_rand(0,255));
            imagesetpixel($img, mt_rand(0,$imgWidth), mt_rand(0,$imgHeight), $pointColor);
        }

        ob_clean();
        imagepng($img);

    }

}
?>