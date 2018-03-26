<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/26 14:21
 * |  Mail: Overcome.wan@Gmail.com
 * |  Desc: 描述信息
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;


use app\common\controller\BaseApiController;

class OrderController extends BaseApiController
{
    // 1、用户选择商品后，向API 提交商品所包含的商品信息
    // 2、API接受到数据后，查询库存量是否满足
    // 3、有库存，把订单数据写入数据库中（下单成功），返回客户端信息，表示下单成功
    // 4、调用支付接口进行支付
    // 5、再次检测库存量
    // 6、服务器调用微信支付接口进行支付
    // 7、成功：微信返回给我们的库存量
    // 8、成功：进行库存量扣除，失败：返回一个支付失败结果，

    protected $beforeActionList = [
        ''
    ];

    /**
     * 权限：用户可以访问，管理员不可以访问
     */
    public function placeOrder(){

    }

}