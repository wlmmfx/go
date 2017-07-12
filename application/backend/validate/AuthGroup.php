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

class AuthGroup extends Validate
{
    protected $rule = [
        'title'=>'require',
    ];

    protected $message = [
        'title.require'=>"分类名称不能21312为空",
    ];
}