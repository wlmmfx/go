<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/7/9
 * Time: 11:05
 */

namespace app\backend\validate;

use think\Validate;

class Article extends Validate
{
    protected $rule = [
        'title'=>'require',
    ];

    protected $message = [
        'title.require'=>"文章名称不能为空",
    ];
}