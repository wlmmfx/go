<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/19 17:20
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\service;


use app\api\service\Order as OrderService;
use app\common\library\enum\OrderStatusEnum;
use app\common\library\exception\OrderException;
use app\common\library\exception\TokenException;
use app\common\model\WxOrder as WxOrderModel;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('wxpay.WxPay', EXTEND_PATH, '.Api.php');

class Pay
{
    private $orderID;

    private $orderNo;

    public function __construct($orderID)
    {
        if (!$orderID) {
            throw  new Exception("订单号不能为空");
        }
        $this->orderID = $orderID;
    }

    public function pay()
    {
        // 订单号可能不存在
        // 订单号存在，但是，订单号和客户不匹配
        // 订单号存有可能已经被支付过
        $this->checkOrderValid();

        // 进行库存量检测
        $orderService = new OrderService();
        $status = $orderService->checkOrderStock($this->orderID);
        if (!$status['pass']) {
            return $status;
        }
        return $this->makeWxPreOrder($status['orderPrice']);
    }

    public function makeWxPreOrder($orderPrice)
    {
        // open_id
        $openId = Token::getCurrentTokenVar('openid');
        if (!$openId) {
            throw new TokenException();
        }
        // 微信统一下单
        $wxOrderData = new \WxPayUnifiedOrder();
        $wxOrderData->SetOut_trade_no($this->orderID);
        $wxOrderData->SetTrade_type('JSAPI');
        $wxOrderData->SetTotal_fee($orderPrice);
        $wxOrderData->SetBody('零食商贩');
        $wxOrderData->SetOpenid($openId);
        $wxOrderData->SetNotify_url(config('secure.pay_back_url')); //微信支付接口结果
        return $this->getPaySignature($wxOrderData);
    }

    //向微信请求订单号并生成签名
    private function getPaySignature($wxOrderData)
    {
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderData);
        return $wxOrder; // mch_id 参数格式错误
        // 失败时不会返回result_code
        if ($wxOrder['return_code'] != 'SUCCESS' || $wxOrder['result_code'] != 'SUCCESS') {
            Log::record($wxOrder, 'error');
            Log::record('获取预支付订单失败', 'error');
//            throw new Exception('获取预支付订单失败');
        }
        $this->recordPreOrder($wxOrder);
        $signature = $this->sign($wxOrder);
        return $signature;
    }

    private function recordPreOrder($wxOrder)
    {
        // 必须是update，每次用户取消支付后再次对同一订单支付，prepay_id 是不同的
        WxOrderModel::where('id', '=', $this->orderID)
            ->update(['prepay_id' => $wxOrder['prepay_id']]);
    }

    // 签名
    private function sign($wxOrder)
    {
        $jsApiPayData = new \WxPayJsApiPay();
        $jsApiPayData->SetAppid(config('wechat.WP_APP_ID'));
        $jsApiPayData->SetTimeStamp((string)time());
        $rand = md5(time() . mt_rand(0, 1000));
        $jsApiPayData->SetNonceStr($rand);
        $jsApiPayData->SetPackage('prepay_id=' . $wxOrder['prepay_id']);
        $jsApiPayData->SetSignType('md5');
        // 生成签名
        $sign = $jsApiPayData->MakeSign();
        $rawValues = $jsApiPayData->GetValues();
        $rawValues['paySign'] = $sign;
        unset($rawValues['appId']);
        return $rawValues;
    }

    // 订单的有效性
    public function checkOrderValid()
    {
        // 订单是否存在
        $order = WxOrderModel::where('id', '=', $this->orderID)->find();
        if (!$order) {
            throw new OrderException();
        }

        // 订单号和客户是否匹配
        if (!Token::isValidateOperate($order->user_id)) {
            throw new TokenException([
                'msg' => '订单与客户不匹配',
                'errorCode' => 10003
            ]);
        }

        // 订单号存有可能已经被支付过，status【1:未支付， 2：已支付，3：已发货 , 4: 已支付，但库存不足】
        if ($order->status != OrderStatusEnum::UNPAID) {
            throw new OrderException([
                'msg' => '订单以及被支付过了，不要重复支付',
                'errorCode' => 80003,
                'code' => 400,
            ]);
        }

        $this->orderNo = $order->order_no;
        return true;
    }
}