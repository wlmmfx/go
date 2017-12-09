<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/7 10:53
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\backend\controller;

use app\common\controller\BaseBackend;

class Ocr extends BaseBackend
{
    public function index(){
        echo "00000000000000";
    }

    public function text(){
        $path = ROOT_PATH . 'public' . DS . 'text.png';
        echo (new \TesseractOCR($path))->run();
    }

    public function text2(){
        $path = ROOT_PATH . 'public' . DS . 'aiai.png';
        $lan_type = "中文";
        $lan_type = sprintf("%s %s",$lan_type,"chi_sim");
        echo (new \TesseractOCR($path))->lang($lan_type)->run();
    }
}