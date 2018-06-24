<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/6/14 20:59
 * |  Mail: Overcome.wan@Gmail.com
 * |  Desc: 描述信息
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\frontend\controller;


use think\Controller;
use think\Db;
use think\Exception;
use think\Request;
use Yansongda\Pay\Pay;
use app\common\model\WxOrder as WxOrderModel;

class PayController extends Controller
{
    /**
     * 商品显示列表
     */
    public function shopCart()
    {
        $this->view->engine->layout(false);
        $res = db('alipay')->where('id', input('get.id','3'))->find();
        $this->assign('info',$res);
        return $this->fetch();
    }

    /**
     * 订单异步提交数据
     */
    public function payTo(Request $request)
    {
        $snap = [
          'orderPrice'=>10,
          'totalCount'=>199,
          'snapImg'=>'https://www.tinywan.com/product-vg@1.png',
          'snapName'=>'test-shop'.rand(1111,9999),
          'snapAddress'=>'2018',
          'pStatus'=>1
        ];
        // 创建订单
        $orderInfo = $this->createOrderByTrans($snap);
        // 订单创建成功
        if($orderInfo){
            // 支付订单信息
            $order = [
              'out_trade_no' => $orderInfo['order_no'],
              'total_amount' => $snap['orderPrice'],
              'subject' => $snap['snapName']
            ];
            $alipay = Pay::alipay(config('alipay'))->web($order);
            // 发起支付
            return $alipay->send();
        }else{
            return json(['msg'=>"订单创建异常"]);
        }
    }

    public function test001()
    {
        $res = Db::name('wx_order')->where(['id' => 637])->update(['status'=>2]);
        halt($res);
    }

    public function index()
    {
        // 快照
        $snap = [
          'orderPrice'=>10,
          'totalCount'=>199,
          'snapImg'=>'https://www.tinywan.com/product-vg@1.png',
          'snapName'=>'test-shop'.rand(1111,9999),
          'snapAddress'=>'2018',
          'pStatus'=>1
        ];
        // 创建订单
        $orderInfo = $this->createOrderByTrans($snap);
        // 订单创建成功
        if($orderInfo){
            // 支付订单信息
            $order = [
              'out_trade_no' => $orderInfo['order_no'],
              'total_amount' => $snap['orderPrice'],
              'subject' => $snap['snapName']
            ];
            $alipay = Pay::alipay(config('alipay'))->web($order);
            // 发起支付
            return $alipay->send();
        }else{
            return "订单创建异常";
        }
    }

    // 创建订单时没有预扣除库存量，简化处理
    // 如果预扣除了库存量需要队列支持，且需要使用锁机制
    private function createOrderByTrans($snap)
    {
        // 启动事务
        Db::startTrans();
        try {
            $orderNo = make_order_no();
            $order = new WxOrderModel();
            $order->user_id = 12001;
            $order->order_no = $orderNo;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);
            $order->save();

            $orderID = $order->id;
            $create_time = $order->create_time;

            Db::commit();
            return [
              'order_no' => $orderNo,
              'order_id' => $orderID,
              'create_time' => $create_time
            ];
        } catch (Exception $ex) {
            Db::rollback();
            throw $ex;
        }
    }
}