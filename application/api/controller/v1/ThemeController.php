<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/22 15:16
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;


use app\api\validate\IDCollection;
use app\api\validate\IDMustBePositiveInt;
use app\common\library\exception\ThemeException;
use app\common\model\WxTheme as WxThemeModel;
use think\Controller;

class ThemeController extends Controller
{
    /**
     * @url     /theme?ids=:id1,id2,id3...
     * @return  array of theme
     * @throws  ThemeException
     * @note 实体查询分单一和列表查询，可以只设计一个接收列表接口，
     *       单一查询也需要传入一个元素的数组
     *       对于传递多个数组的id可以选用post传递、
     *       多个id+分隔符或者将多个id序列化成json并在query中传递
     */
    public function getSimpleList($ids = '')
    {
        (new IDCollection())->goCheck();
        $ids = explode(',', $ids);
        $res = WxThemeModel::with('topicImg,headImg')->select($ids);

        // 数据库没有查询到，异常类处理
        if ($res->isEmpty()) {
            throw new ThemeException();
        }
        return json($res);
    }

    /**
     * @url api/v1/theme/121
     * @param $id
     * @return string
     */
    public function getComplexOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $theme = WxThemeModel::getThemeWithProducts($id);
        return json($theme);
    }
}