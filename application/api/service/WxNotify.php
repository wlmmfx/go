<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/4/2 13:39
 * |  Mail: Overcome.wan@Gmail.com
 * |  Desc: 描述信息
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\service;


use app\common\library\enum\OrderStatusEnum;
use app\common\model\WxOrder as WxOrderModel;
use app\api\service\Order as WxOrderService;
use app\common\model\WxProduct;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

Loader::import('wxpay.wxpay', EXTEND_PATH, '.Api.php');

class WxNotify extends \WxPayNotify
{
    //<xml>
    //<appid><![CDATA[wx2421b1c4370ec43b]]></appid>
    //<attach><![CDATA[支付测试]]></attach>
    //<bank_type><![CDATA[CFT]]></bank_type>
    //<fee_type><![CDATA[CNY]]></fee_type>
    //<is_subscribe><![CDATA[Y]]></is_subscribe>
    //<mch_id><![CDATA[10000100]]></mch_id>
    //<nonce_str><![CDATA[5d2b6c2a8db53831f7eda20af46e531c]]></nonce_str>
    //<openid><![CDATA[oUpF8uMEb4qRXf22hE3X68TekukE]]></openid>
    //<out_trade_no><![CDATA[1409811653]]></out_trade_no>
    //<result_code><![CDATA[SUCCESS]]></result_code>
    //<return_code><![CDATA[SUCCESS]]></return_code>
    //<sign><![CDATA[B552ED6B279343CB493C5DD0D78AB241]]></sign>
    //<sub_mch_id><![CDATA[10000100]]></sub_mch_id>
    //<time_end><![CDATA[20140903131540]]></time_end>
    //<total_fee>1</total_fee>
    //<coupon_fee><![CDATA[10]]></coupon_fee>
    //<coupon_count><![CDATA[1]]></coupon_count>
    //<coupon_type><![CDATA[CASH]]></coupon_type>
    //<coupon_id><![CDATA[10000]]></coupon_id>
    //<coupon_fee><![CDATA[100]]></coupon_fee>
    //<trade_type><![CDATA[JSAPI]]></trade_type>
    //<transaction_id><![CDATA[1004400740201409030005092168]]></transaction_id>
    //</xml>
    public function NotifyProcess($data, &$msg)
    {
        if ($data['result_code'] == "SUCCESS") {
            $orderNo = $data['out_trade_no'];
            Db::startTrans();
            try {
                $order = WxOrderModel::where('order_no', '=', $orderNo)->find();
                if ($order->status == 1) {
                    $service = new WxOrderService();
                    $stockStatus = $service->checkOrderStock($order->id); // 返回订单量状态的
                    // 通过订单状态量
                    if ($stockStatus['pass']) {
                        // 更新订单状态
                        $this->updateOrderStatus($order->id, true);
                        // 消减库存量
                        $this->reduceStock($stockStatus);
                    } else {
                        // 已支付，但是库存不足
                        $this->updateOrderStatus($order->id, false);
                    }
                    return true;
                }
                Db::commit();
            } catch (Exception $ex) {
                Db::rollback();
                Log::record($ex->getMessage(), 'error');
                return false;
            }
        } else {
            // 告诉微信，支付已经错误了，不需要继续发送了
            return true;
        }
    }

    // 消减库存量
    private function reduceStock($stockStatus)
    {
        // 查询出数量
        foreach ($stockStatus as $singlePStatus) {
            // $singlePStatus['count']
            WxProduct::where('id', '=', $singlePStatus['id'])
                ->setDec('stock', $singlePStatus['count']);
        }
    }

    // 支付成功 更新订单状态
    private function updateOrderStatus($orderID, $success)
    {
        // 订单支付状态
        $status = $success ? OrderStatusEnum::PAID : OrderStatusEnum::PAID_BUT_OUT_OF;
        WxOrderModel::where('id', '=', $orderID)->update(['status' => $status]);
    }
}