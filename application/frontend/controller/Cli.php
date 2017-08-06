<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/7/1
 * Time: 13:40
 */

namespace app\frontend\controller;


use FFMpeg\FFMpeg;
use think\Controller;

class Cli extends Controller
{
    public function index(){
        echo  "Cli";
    }

    //这个方法将被cli模式调用
    public function cmdCliTest()
    {
        echo date("Y-m-d H:i:s",time()).' : ThinnPHP cli Mode Run Success:';
    }
}