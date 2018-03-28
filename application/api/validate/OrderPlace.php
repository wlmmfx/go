<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/27 10:25
 * |  Mail: Overcome.wan@Gmail.com
 * |  Desc: 描述信息
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\validate;


use app\common\library\exception\ParameterException;

class OrderPlace extends BaseValidate
{
//    protected $oProducts = [
//        [
//            'product_id' => 1,
//            'count' => 3
//        ],
//        [
//            'product_id' => 2,
//            'count' => 33
//        ],
//        [
//            'product_id' => 3,
//            'count' => 2
//        ],
//        [
//            'product_id' => 4,
//            'count' => 2
//        ],
//    ];
//
//    protected $products = [
//        [
//            'product_id' => 1,
//            'count' => 3
//        ],
//        [
//            'product_id' => 2,
//            'count' => 33
//        ],
//        [
//            'product_id' => 3,
//            'count' => 2
//        ],
//        [
//            'product_id' => 4,
//            'count' => 2
//        ],
//    ];

    protected $rule = [
        'products' => 'checkProducts'
    ];

    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger',
    ];

    //自定义验证器
    protected function checkProducts($values)
    {
        if(empty($values)){
            throw new ParameterException([
                'msg' => '商品列表不能为空'
            ]);
        }
        foreach ($values as $value)
        {
            $this->checkProduct($value);
        }
        return true;
    }

    // 单个商品验证
    private function checkProduct($value)
    {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误',
            ]);
        }
    }
}