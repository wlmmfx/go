<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/22 15:17
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;

use app\common\model\WxProduct as WxProductModel;

class WxProduct extends BaseModel
{
    protected $hidden = [
        'from',
        'create_time',
        'update_time',
        'delete_time',
        'pivot',
    ];

    // 设置后，模型所有的数据集查询返回结果的类型都是think\model\Collection对象实例。
    protected $resultSetType = 'collection';

    protected function getMainImgUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value, $data);
    }

    public static function getMostRecent($count)
    {
        $products = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $products;
    }

    public static function getProductsByCategoryID($categoryID)
    {
        $products = self::where('category_id', '=', $categoryID)->select();
        return $products;
    }
}