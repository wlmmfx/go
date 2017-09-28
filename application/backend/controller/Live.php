<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/9/28 15:44
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;


use app\common\controller\BaseBackend;
use think\Db;

class Live extends BaseBackend
{
    public function index()
    {
        return $this->fetch();
    }

    public function indexRecord()
    {
        $videos = Db::table('resty_stream_video')->select();
        $this->assign('videos',$videos);
        return $this->fetch();
    }
}