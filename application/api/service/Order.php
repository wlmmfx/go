<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/27 10:42
 * |  Mail: Overcome.wan@Gmail.com
 * |  Desc: 描述信息
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\service;


use app\common\library\exception\OrderException;
use app\common\library\exception\UserException;
use app\common\model\WxOrder;
use app\common\model\WxOrderProduct;
use app\common\model\WxProduct;
use app\common\model\WxUserAddress;
use think\Exception;

class Order
{
    // 订单商品列表，也就是客户端传递过来的的proudcts参数
    protected $oProducts = '';

    //真实的商品信息，包括库存量
    protected $products = '';

    protected $uid;

    public function place($uid, $oProducts)
    {
        // $oProducts和$products 对比
        // $products 查询出来
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsById($oProducts);
        $this->uid = $uid;

        $status = $this->getOrderStatus();
        if (!$status['pass']) {
            $status['order_id'] = -1;
            return $status;
        }

        // 创建订单快照
        $orderSnap = $this->snapOrder();

        // 订单创建

    }

    // 创建订单
    private function createOrder($snap)
    {
        // 对于使用异常的尽量加上 try catch
        try {
            $orderNo = self::makeOrderNo();
            $order = new WxOrder();
            $order->order_no = $orderNo;
            $order->user_id = $this->uid;
            $order->total_price = $snap['orderPrice'];
            $order->total_count = $snap['totalCount'];
            $order->snap_img = $snap['snapImg'];
            $order->snap_name = $snap['snapName'];
            $order->snap_address = $snap['snapAddress'];
            $order->snap_items = json_encode($snap['pStatus']);

            $order->save();
            $orderId = $order->id;
            $createTime = $order->create_time;
            foreach ($this->products as $p) {
                $p['order_id'] = $orderId;
            }
            $orderProduct = new WxOrderProduct();
            $orderProduct->saveAll($this->products);
            return [
                'order_no' => $orderNo,
                'order_id' => $orderId,
                'create_time' => $createTime
            ];
        } catch (Exception $e) {
            throw $e;
        }
    }

    // 生成订单号
    public static function makeOrderNo()
    {
        $yCode = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        $orderSn =
            $yCode[intval(date('Y')) - 2017] . strtoupper(dechex(date('m'))) . date(
                'd') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        return $orderSn;
    }

    // 生成订单快照
    private function snapOrder($status)
    {
        $snap = [
            'orderPrice' => 0,
            'totalCount' => 0,
            'pStatus' => 0,
            'snapAddress' => null,
            'snapName' => 0,
            'snapImg' => 0,
        ];

        $snap['orderPrice'] = $status['orderPrice'];
        $snap['totalCount'] = $status['totalCount'];
        $snap['pStatus'] = $status['pStatusArray'];
        $snap['snapAddress'] = json_encode($this->getUserAddress()); // 地址不存在则不允许下订单
        $snap['snapName'] = $this->products[0]['name'];
        $snap['snapImg'] = $this->products[0]['name'];
        if (count($this->products) > 1) {
            $snap['snapName'] .= '等';
        }
    }

    // 获取用户地址
    private function getUserAddress()
    {
        $userAddress = WxUserAddress::where('user_id', '=', $this->uid)->find();
        if (!$userAddress) {
            throw new UserException([
                'msg' => '用户收货地址不存在，下单失败',
                'errorCode' => 60001
            ]);
        }
        return $userAddress->toArray();
    }

    /**
     * 获取订单状态
     * @return array
     */
    private function getOrderStatus()
    {
        $status = [
            'pass' => true,
            'orderPrice' => 0,  // 总价格
            'totalPrice' => 0,  // 所有总价格
            'pStatusArray' => []  //保存订单中所有商品详细信息
        ];

        // 两个数组之间的对比
        foreach ($this->oProducts as $oProduct) {
            $pStatus = $this->getProductStatus(
                $oProduct['product_id'], $oProduct['count'], $this->products
            );

            if (!$pStatus['havaStock']) {
                $status['pass'] = false;
            }
            $status['orderPrice'] = $pStatus['totalPrice'];
            $status['totalCount'] = $pStatus['count'];
            array_push($status['pStatusArray'], $pStatus);
        }
        return $status;
    }

    // 商品库存状态
    private function getProductStatus($oPID, $oCount, $products)
    {
        $pIndex = -1;
        //  $pStatus 保存每一个商品的详细信息
        $pStatus = [
            'id' => null,
            'havaStock' => null,  // 库存量
            'count' => 0,
            'name' => '',
            'totalPrice' => 0, // 一类商品的价格
        ];

        for ($i = 0; $i < count($products); $i++) {
            if ($oPID == $products[$i]['id']) {
                $pIndex = $i;
            }
        }

        if ($pIndex == -1) {
            // 客户端传递过来的product_id 可能根本就不存在
            throw new OrderException([
                'msg' => 'id为：' . $oPID . '的商品不存在，创建订单失败'
            ]);
        } else {
            $product = $products[$pIndex];
            $pStatus['id'] = $product['id'];
            $pStatus['name'] = $product['name'];
            $pStatus['count'] = $oCount;
            $pStatus['totalPrice'] = $product['price'] * $oCount;
            if ($pStatus['stock'] - $oCount >= 0) {
                $pStatus['havaStock'] = true;
            }
        }
        return $pStatus;
    }


    /**
     * 获取具体的某一条商品信息
     * @param $oProducts
     * @return mixed
     */
    private function getProductsById($oProducts)
    {
//        foreach ($oProducts as $oProduct) {
//            // 循环查询数据库
//        }
        $oIds = [];
        foreach ($oProducts as $item) {
            array_push($oIds, $item['product_id']);
        }
        $products = WxProduct::all($oIds)->visible(['id', 'price', 'stock', 'name', 'main_img_url'])->toArray();
        return $products;
    }
}