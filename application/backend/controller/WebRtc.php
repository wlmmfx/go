<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/7 14:42
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\backend\controller;


use app\common\controller\BaseBackend;

class WebRtc extends BaseBackend
{
    public function index()
    {
        return $this->fetch();
    }

    public function recordRtc()
    {
        header('Access-Control-Allow-Origin:*');
        return $this->fetch();
    }

    public function recordRtcRun()
    {
        foreach(array('video', 'audio') as $type) {
            if (isset($_FILES["${type}-blob"])) {
                echo 'uploads/';
                $fileName = $_POST["${type}-filename"];
                $uploadDirectory = ROOT_PATH . 'public' . DS .'uploads/'.$fileName;

                if (!move_uploaded_file($_FILES["${type}-blob"]["tmp_name"], $uploadDirectory)) {
                    echo(" problem moving uploaded file");
                }
                echo($fileName);
            }
        }
    }
}