<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/10/20 22:06
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace app\live\controller;

use app\common\controller\BaseFrontendController;
use app\common\model\StreamName;
use live\LiveStream;
use think\Db;

class LiveController extends BaseFrontendController
{
    /**
     * 初始化
     */
    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     * 活动详情活动
     * @param $id 直播id
     * @return mixed
     */
    public function detail($id)
    {
        $live = Db::name('live')->alias('l')
            ->join('resty_file f', "f.live_id = l.id")
            ->where('l.id', $id)
            ->field('l.id,l.name,l.liveStartTime,l.liveEndTime,l.stream_name,l.stream_id,l.isLive,l.autoPlay,f.path')
            ->find();
        $streamInfo = StreamName::where('id', $live['stream_id'])->find();
        $liveStatus = LiveStream::getLiveStreamNameStatus($streamInfo['stream_name'])['status'];
        return $this->fetch('', [
            'streamInfo' => $streamInfo,
            'live' => $live,
            'title' => $live['name'],
            'liveStatus' => $liveStatus
        ]);
    }

}