<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/22 21:29
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;


use app\api\validate\Count;
use app\api\validate\IDMustBePositiveInt;
use app\common\library\exception\ProductException;
use app\common\model\WxProduct as WxProductModel;

class ProductController
{
    /**
     * @url api/v1/product/recent?count=12
     * @param int $count
     * @return \think\response\Json
     * @throws ProductException
     */
    public function getRecent($count = 15)
    {
        (new Count())->goCheck();
        $products = WxProductModel::getMostRecent($count);
        // 临时隐藏字段，保持模型的干净性
        $products = $products->hidden(['summary']);
        if($products->isEmpty()){
            throw new ProductException();
        }
        return json($products);
    }

    /**
     * @url api/v1/product/by_category?id=4
     * @param $id
     * @return \think\response\Json
     * @throws ProductException
     */
    public function getAllInCategory($id){
        (new IDMustBePositiveInt())->goCheck();
        $products = WxProductModel::getProductsByCategoryID($id);
        // 单独转换为数据集
        $products = collection($products);
        if($products->isEmpty()){
            throw new ProductException();
        }
        $products = $products->hidden(['summary']);
        return json($products);
    }
}