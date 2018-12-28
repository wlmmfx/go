<?php

/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/9/18 16:09
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\index\controller;

use think\Controller;
use think\Request;

class Index extends Controller
{
    protected $beforeActionList = [
        'first',
        'second' =>  ['except'=>'hello111'],
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

    public function hello()
    {
//        return '是否AJAX请求:' . var_export(request()->isSsl(), true);
        return '是否AJAX请求:' . var_export(request()->header(), true);
    }

    public function index()
    {
        halt(111111111111111);
        return $this->fetch();
    }

}