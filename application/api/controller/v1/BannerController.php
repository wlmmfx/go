<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/19 15:27
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;

use app\api\validate\IDMustBePositiveInt;
use app\common\library\exception\BannerMissException;
use think\Controller;
use app\common\model\WxBanner as WxBannerModel;

class BannerController extends Controller
{
    public function getBanner($id)
    {
        // 数据验证 url：https://www.tinywan.com/banner/0.1?num=2
        (new IDMustBePositiveInt())->goCheck();
        // 1 异常 2 数据库数据不存在
//         $banner = WxBannerModel::getBannerById($id);
        $banner = WxBannerModel::get($id);
        if (!$banner) {
            // BannerMissException 必须继承 Exception 类的，这里使用自定义异常
            throw new  BannerMissException();
        }
        return json($banner);
    }
}