<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/7/9
 * Time: 11:05
 */

namespace app\common\validate;

use think\Validate;

class Tag extends Validate
{
    protected $rule = [
        'name'=>'require'
    ];

    protected $message = [
        'name.require'=>"标签名称不能为空",
    ];
}