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
use app\common\model\WxProduct;

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