<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/28 13:38
 * |  Mail: Overcome.wan@Gmail.com
 * |  Help: https://pay.weixin.qq.com/wiki/doc/api/wxa/wxa_api.php?chapter=9_1
 * |  Desc: 微信支付 【商户号和商户密码】
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;


use app\api\service\Pay as PayService;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePositiveInt;
use app\common\controller\BaseApiController;


class PayController extends BaseApiController
{
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'getPreOrder']
    ];

    // 生成预订单信息 $id = 551
    public function getPreOrder($id = '')
    {
        // orderNo/orderId
        (new IDMustBePositiveInt())->goCheck();

        $pay = new PayService($id);
        $res = $pay->pay();
        return json($res);
    }

    //回调处理转发
    public function redirectNotify()
    {
        // 通知频率 15/15/30/180/1800/1800/1800/3600 单位：秒
        // 1、检测库存量，超卖
        // 2、真实的更改数据库状态,修改 status 状态值
        // 3、减少库存
        // 4、如果成功处理，返回给微信成功处理消息。否则，返回给返回微信处理失败消息

        // 特点：POST接受，XML格式，不会携带参数
        $notify = new WxNotify();
        $notify->Handle();
    }

    public function receiveNotify()
    {
        $xmlData = file_get_contents('php://input');
        Log::error($xmlData);
        $result = curl_post_raw('https://www.tinywan.com/api/v1/pay/redirectNotify?XDEBUG_SESSION_START=13133',
            $xmlData);
        return $result;
    }
}