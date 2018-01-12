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
use app\common\model\Live;
use app\common\model\StreamName;
use live\LiveStream;
use think\Db;

class IndexController extends BaseFrontendController
{
    /**
     * 初始化
     */
    public function _initialize()
    {
        parent::_initialize();

    }

    /**
     * @return mixed
     */
    public function index()
    {
        $vods = Db::table("resty_vod")
            ->alias('v')
            ->join('resty_category c', 'c.id = v.cid', 'left')
            ->field('v.id,v.create_time,v.name,v.hls_url,v.image_url,v.content,v.download_data,c.name as cName')
            ->order('v.create_time desc')
            ->limit(6)
            ->cache('RESTY_VOD_LIVE_MODULE')
            ->select();
        $this->assign('banners', Db::table('resty_banner')->where(['publish_status' => 1, 'deleted' => 0])->order('id desc')->cache('RESTY_BANNER')->select());
        $this->assign('vods', $vods);
        return $this->fetch();
    }

    /**
     * @return mixed
     */
    public function videoJsIndex()
    {
        $id = 201710028;
        $live = Db::name('live')->alias('l')
            ->join('resty_file f', "f.live_id = l.id")
            ->where('l.id', $id)
            ->field('l.id,l.name,l.liveStartTime,l.liveEndTime,l.stream_name,l.stream_id,l.isLive,l.autoPlay,f.path')
            ->find();
        $streamInfo = StreamName::where('id', $live['stream_id'])->find();
        return $this->fetch('', [
            'streamInfo' => $streamInfo,
            'live' => $live
        ]);
    }


    /**
     * 事件列表
     */
    public function eventList()
    {
        return $this->fetch();
    }

    /**
     * 活动列表
     */
    public function liveList()
    {
        return $this->fetch();
    }

    /**
     * 活动详情
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
            'liveStatus' => $liveStatus
        ]);
    }

    /**
     * https://www.tinywan.com/live/Index/detail3/id/201710016.html
     * @param $id
     * @return mixed
     */
    public function detail3($id)
    {
        $streamName = 8001515731713;
        $totalInfo = LiveStream::getLiveStreamNameStatus($streamName)['status'];
        halt($totalInfo);
        $live = Db::name('live')->alias('l')
            ->join('resty_file f', "f.live_id = l.id")
            ->where('l.id', $id)
            ->field('l.id,l.name,l.liveStartTime,l.liveEndTime,l.stream_name,l.stream_id,l.isLive,l.autoPlay,f.path')
            ->find();
        $streamInfo = StreamName::where('id', $live['stream_id'])->cache('RESTY_STREAM_NAME:' . $live['stream_id'])->find();
        return $this->fetch('', [
            'streamInfo' => $streamInfo,
            'live' => $live
        ]);
    }

    /**
     * 点播详情
     */
    public function vodDetail()
    {
        $liveId = input('param.id');
        $live = Db::table('resty_vod')->where('id', $liveId)->cache('RESTY_VOD_DETAIL:' . $liveId)->find();
        $this->assign('vod', $live);
        return $this->fetch();
    }
}