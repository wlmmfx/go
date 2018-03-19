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
use app\api\validate\TestValidate;
use think\Controller;
use think\Validate;

class BannerController extends Controller
{
    /**
     * 获取Banner信息
     * @url     /banner/:id
     * @http    get (https://www.tinywan.com/banner/12312312)
     * @param   int $id banner id
     * @return  array of banner item , code 200
     * @throws  MissException
     */
    public function getBanner($id)
    {
        // 需要验证的数据
        $data = [
            'id'  => $id
        ];

        // 使用验证器验证
        $validate = new IDMustBePositiveInt();
        $validate->batch()->check($data);
        var_dump($validate->getError());
        return $id;
    }


    // 使用验证器验证
    public function getBannerValidate($id)
    {
        // 需要验证的数据
        $data = [
            'name'  => 'thinkphp',
            'email' => 'thinkphpqq.com'
        ];

        // 使用验证器验证
        $validate = new TestValidate();
        $validate->batch()->check($data);
        var_dump($validate->getError());
        return $id;
    }

    // 单个验证规则
    public function getBannerTest($id)
    {
        // 需要验证的数据
        $data = [
            'name'  => 'thinkphp',
            'email' => 'thinkphpqq.com'
        ];
        // 任何时候，都可以使用Validate类进行独立的验证操作
        // 定义验证规则
        $validate = new Validate([
            'name'  => 'require|max:5',
            'email' => 'email'
        ]);

        // 单个验证规则   执行验证
        //$result = $validate->check($data);

        // 批量
        $result = $validate->batch()->check($data);

        if (!$validate->check($data)) {
            dump($validate->getError());
        }
        return $id;
    }
}