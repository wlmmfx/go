<?php
/**
 * Created by PhpStorm.
 * User: Tinywan
 * Date: 2017/7/12
 * Time: 12:24
 * Mail: Overcome.wan@Gmail.com
 */

namespace app\backend\validate;

use think\Validate;

class AuthRule extends Validate
{
    protected $rule = [
        'name'=>'require',
        'title'=>'require',
    ];

    protected $message = [
        'name.require'=>"规则URL不能21312为空",
        'title.require'=>"标题不能21312为空",
    ];
}