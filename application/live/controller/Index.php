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