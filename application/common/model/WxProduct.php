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

    /**
     * 一对多
     */
    public function imgs()
    {
        return $this->hasMany('WxProductImage', 'product_id', 'id');
    }

    /**
     * 一对多关系
     * @return \think\model\relation\HasMany
     */
    public function products()
    {
        return $this->hasMany('WxProductProperty', 'product_id', 'id');
    }

    /**
     * 获取商品详情
     * 这里有二级模型的排序
     * @static
     */
    public static function getProductDetail($id)
    {
        $product = self::with([
            'imgs' => function ($query) {
                $query->with(['imgUrl'])->order('order', 'asc');
            }
        ])
        ->with('products')
        ->find($id);
        return $product;
    }
}