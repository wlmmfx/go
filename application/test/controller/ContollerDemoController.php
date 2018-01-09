<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/2 9:43
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\test\controller;


use think\Controller;

class ContollerDemoController extends Controller
{

    /**
     * beforeActionList属性可以指定某个方法为其他方法的前置操作，
     * 数组键名为需要调用的前置方法名，
     * 无值的话为当前控制器下所有方法的前置方法。
     * @var array
     */
    protected $beforeActionList = [
        'first',
        'second' =>  ['except'=>'hello'],
        'three'  =>  ['only'=>'hello,data'],
    ];

    protected function first()
    {
        echo 'first<br/>';
    }

    protected function second()
    {
        echo 'second<br/>';
    }

    protected function three()
    {
        echo 'three<br/>';
    }

    /**
     * 参数传入
     * https://www.tinywan.com/test/ContollerDemo/hello/name/thinkphp
     * @param string $name
     * @return string
     */
    public function hello($name = 'World')
    {
        return 'Hello,' . $name . '!';
    }

    /**
     * 多个参数
     * https://www.tinywan.com/test/ContollerDemo/hello2/name/thinkphp2/city/shanghai
     * @param string $name
     * @param string $city
     * @return string
     */
    public function hello2($name = 'World', $city = '')
    {
        return 'Hello,' . $name . '! You come from ' . $city . '.';
    }
}