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
use app\api\validate\IDMustBePositiveInt;
use app\common\controller\BaseApiController;
use think\Loader;

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
        $wxOrder = \WxPayApi::unifiedOrder();
        return json($wxOrder);
    }
}