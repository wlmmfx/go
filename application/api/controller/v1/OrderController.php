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


use app\api\service\Token as TokenSerice;
use app\api\validate\OrderPlace;
use app\common\controller\BaseApiController;
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
        'checkPrimaryScope' => ['only' => 'placeOrder']
    ];

    /**
     * 【下单接口】
     * 权限：用户可以访问，管理员不可以访问
     */
    public function placeOrder()
    {
        // 数组类型验证器
        (new OrderPlace())->goCheck();
        $products = input('post.products/');
        $uid = TokenSerice::getCurrentUid();

    }

    public function test(){
        Log::error('11111111111111111111');
        die;
        $data['name'] = '万少波';
        $data['address'] = 'tc/wechat';
        halt(send_dayu_sms('13669361192','car_notice',$data));
    }

}