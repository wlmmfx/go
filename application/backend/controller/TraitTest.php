<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/11/7 10:08
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;


class TraitTest
{
    use \traits\controller\Jump;

    /**
     * 现在我们可以直接使用\traits\controller\Jump中定义的方法
     * 包括success/error/result/redirect
     * @return string
     */
    public function hello()
    {
        return 'hello';
    }

}