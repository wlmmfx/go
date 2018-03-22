<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/22 22:48
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;


use app\common\library\exception\CategoryException;
use app\common\model\WxCategory as WxCategoryModel;

class CategoryController
{
    public function getAllCategories(){
        // [] 表示查询全部， 第二个参数表示关联一个方法
        $res = WxCategoryModel::all([],'img');
        if($res->isEmpty()){
            throw  new  CategoryException();
        }
        return json($res);
    }
}