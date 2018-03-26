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
    protected function isPositiveInteger($value, $rule = '', $data = '', $field = '')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return false;
    }

    protected function isNotEmpty($value, $rule = '', $data = '', $field = '')
    {
        if (empty($value)) return false;
        return true;
    }

    //没有使用TP的正则验证，集中在一处方便以后修改
    //不推荐使用正则，因为复用性太差
    //手机号的验证规则
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 根据规则来获取数据
     * @param array $arrays 通常传入request.post变量数组
     * @return array 按照规则key过滤后的变量数组
     * @throws ParameterException
     */
    public function getDataByRule($arrays)
    {
        if (array_key_exists('user_id', $arrays) | array_key_exists('uid', $arrays)) {
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名 user_id 或者 uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value) {
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }

}