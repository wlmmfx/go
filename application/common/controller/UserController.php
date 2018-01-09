<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/6/23
 * Time: 21:16
 */

namespace app\common\controller;


class UserController
{
    public function showName($name)
    {
        return "this is common user {$name}";
    }
}