<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/7 13:53
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\im\controller;

use app\common\controller\BaseFrontendController;


class PayController extends BaseFrontendController
{
    public function test()
    {
        $ua = $_SERVER['HTTP_USER_AGENT'];
        if (strpos($ua, 'MicroMessenger')) {
            $type = 'wepay';
            $name = '微信支付';
            //微信支付链接
            $url = 'wxp://f2f09hjzo72AAYEITIBaolV-3cvGrDjE0q7k';
            $icon_img = '<img src="http://ww2.sinaimg.cn/large/005zWjpngy1fojrwgr20oj303k03kglg.jpg" width="48px" height="48px" alt="' . $name . '">';
        } elseif (strpos($ua, 'AlipayClient')) {
            //支付宝链接
            $url = 'HTTPS://QR.ALIPAY.COM/FKX03479QJ0RVOS3UJLQAE';
            header('location: ' . $url);
        } elseif (strpos($ua, 'QQ/')) {
            $type = 'qq';
            $name = 'QQ钱包支付';
            //QQ钱包支付链接
            $url = 'https://i.qianbao.qq.com/wallet/sqrcode.htm?m=tenpay&a=1&u=17878127&ac=E04BE442991E7FFED28B3B5C3E187148F063DC3C6DACAD2983C87B482FC9E7AD&n=薛定谔的猫&f=wallet';
            $icon_img = '<img src="http://ww2.sinaimg.cn/large/005zWjpngy1fojrvmp427j303k03kjrb.jpg" width="48px" height="48px" alt="' . $name . '">';
        } else {
            $type = 'other';
            $name = '打赏作者';
            $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            $icon_img = '<img src="http://ww2.sinaimg.cn/large/005zWjpngy1fojs089x6tj303k03kjr6.jpg" width="48px" height="48px" alt="' . $name . '">';
        }
        $qr_img = '<img src="http://qr.liantu.com/api.php?text=' . urlencode($url) . '">';
    }

    /**
     * 房间列表
     * @return mixed
     */
    public function roomList()
    {
        return $this->fetch();
    }

}