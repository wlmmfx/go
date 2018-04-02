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


use app\api\service\Token as TokenService;
use app\api\service\Order as OrderService;
use app\api\service\Token;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\common\controller\BaseApiController;
use app\common\library\exception\OrderException;
use app\common\model\WxOrder as WxOrderModel;
use think\Log;

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
        'checkExclusiveScope' => ['only' => 'placeOrder'],
        'checkPrimaryScope' => ['only' => 'getDetail,getSummaryByUser'],
    ];

    /**
     * 【下单接口】
     * 权限：用户可以访问，管理员不可以访问
     */
    public function placeOrder()
    {
        // 数组类型验证器
        (new OrderPlace())->goCheck();
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();

        // 订单状态
        $order = new OrderService();
        $status = $order->place($uid, $products);
        return json($status);
    }

    // 订单列表
    public function getSummaryByUser($page = 1, $size = 15)
    {
        (new PagingParameter())->goCheck();
        $uID = Token::getCurrentUid();
        $pagingOrders = WxOrderModel::getSummaryByUser($uID, $page, $size);
        if ($pagingOrders->isEmpty()) {
            $res = [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
            return json($res);
        }
        $data = $pagingOrders->hidden(['prepay_id', 'snap_items', 'snap_address'])->toArray();
        $res = [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ];
        return json($res);
    }

    /**
     * 订单详情
     * https://www.tinywan.com/api/v1/order/557
     */
    public function getDetail($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $orderDetail = WxOrderModel::get($id);
        if (!$orderDetail) {
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }

    public function test()
    {
        Log::error('----------------------------------');
        return 1111;
        die;
        $data['name'] = '万少波';
        $data['address'] = 'tc/wechat';
        halt(send_dayu_sms('13669361192', 'car_notice', $data));
    }

}