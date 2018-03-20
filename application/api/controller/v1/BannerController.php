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
use think\Controller;
use app\common\model\Banner as BannerModel;
use think\Exception;

class BannerController extends Controller
{
    public function getBanner($id)
    {
        // 数据验证 url：https://www.tinywan.com/banner/0.1?num=2
        (new IDMustBePositiveInt())->goCheck();
        // 1 异常 2 数据库数据不存在
        $banner = BannerModel::getBannerById($id);
        if (!$banner) {
            // BannerMissException 必须属于Exception 类的
            throw new  Exception("Banner 没有啊！");
        }
        return json($banner);
    }
}