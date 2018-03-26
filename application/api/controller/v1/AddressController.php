<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/26 10:29
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;


use app\api\validate\NewAddress;
use app\api\service\Token as TokenService;
use app\common\controller\BaseApiController;
use app\common\library\exception\SuccessMessage;
use app\common\library\exception\UserException;
use app\common\model\WxUser as WxUserModel;

class AddressController extends BaseApiController
{
//    protected $beforeActionList = [
//        'first' => ['only' => 'second,third']
//    ];
//
//    public function first()
//    {
//        echo "first";
//    }
//
//    public function second()
//    {
//        echo "second";
//    }
//
//    public function third()
//    {
//        echo 'third';
//    }

    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress'] // 执行createOrUpdateAddress 会自动调用 checkPrimaryScope方法
    ];

    /**
     * api/v1/address
     * @return \think\response\Json
     * @throws UserException
     */
    public function createOrUpdateAddress()
    {
        $validate = new NewAddress();
        $validate->goCheck();
        // 权限验证
        $uid = TokenService::getCurrentUid();
        $userInfo = WxUserModel::get($uid);
        if (!$userInfo) {
            throw new UserException([
                'code' => 404,
                'msg' => '用户收获地址不存在',
                'errorCode' => 60001
            ]);
        }

        $userAddress = $userInfo->address;
        // 根据规则取字段是很有必要的，防止恶意更新非客户端字段
        $dataArr = $validate->getDataByRule(input('post.'));
        if (!$userAddress) {
            // 关联属性不存在，则新建
            $userInfo->address()->save($dataArr);
        } else {
            // 存在则更新
            // fromArrayToModel($user->address, $data);
            // 新增的save方法和更新的save方法并不一样
            // 新增的save来自于关联关系
            // 更新的save来自于模型
            $userInfo->address->save($dataArr); // 这里要注意了，不明白什么意思？
        }
        return json(new SuccessMessage());
    }
}