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

use app\common\controller\BaseFrontend;
use think\Db;

class Index extends BaseFrontend
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
            ->join('resty_vod_tag vt', 'v.id = vt.vod_id')
            ->join('resty_category c', 'c.id = v.cid')
            ->join('resty_tag t', 't.id = vt.tag_id')
            ->field('v.id,v.create_time,v.name,v.hls_url,v.image_url,v.content,v.download_data,c.name as cName,t.name as tName')
            ->order('v.create_time desc')
            ->limit(6)
            ->select();
        $this->assign('banners', db('banner')->where(['publish_status'=>1,'deleted'=>0])->order('id desc')->select());
        $this->assign('vods', $vods);
        return $this->fetch();
    }

    /**
     * 事件列表
     */
    public function eventList(){
        return $this->fetch();
    }

    /**
     * 活动列表
     */
    public function liveList(){
        return $this->fetch();
    }

    /**
     * 活动详情
     */
    public function detail(){
        $liveId = input('param.id');
        $live = Db::table('resty_live')->where('id',$liveId)->find();
        $this->assign('live',$live);
        return $this->fetch();
    }

    /**
     * 点播详情
     */
    public function vodDetail(){
        $liveId = input('param.id');
        $live = Db::table('resty_vod')->where('id',$liveId)->find();
        $this->assign('vod',$live);
        return $this->fetch();
    }

}