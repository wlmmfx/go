<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/19 17:17
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: 基类验证器
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\validate;


use app\common\library\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseValidate extends Validate
{

    // 全局输入参数验证
    public function goCheck()
    {
        // 获取http传入 参数
        $request = Request::instance();
        // 所有http传入参数
        $allParam = $request->param();

        // new Validate 由于是在内的内部，所以直接使用$this batch批量验证
        $result = $this->batch()->check($allParam);
        if (!$result) {
            // 使用自定义异常
            $e = new  ParameterException([
                'msg' => $this->getError()
            ]);
            throw  $e;

            // 抛出一个tp5默认异常
            //$errors = $this->getError();
            //throw  new Exception($errors);
        } else {
            return true;
        }
    }

    //自定义验证规则
    protected function isPositiveInteger($value, $rule='', $data='', $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return false;
    }
}