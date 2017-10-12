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
    const AUTH_PRIVATEKEY = 'Tinywan123';
    protected $db;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Live();
    }

    /**
     * 获取推流地址auth_key
     * @param string $sourceName
     * @param string $domainName
     * @param string $appName
     * @param $streamName
     * @param $startTime
     * @param $expireTime
     * @param string $cdn
     * @return mixed
     */
    public static function getPushUrlAuthKey($sourceName='live.tinywan.com', $domainName='live.tinywan.com', $appName='live', $streamName, $startTime, $expireTime, $cdn='live.tinywan.com')
    {
        $auth_key = self::AUTH_PRIVATEKEY;
        $timestatmp = strtotime(date('Y-m-d H:i:s', strtotime($startTime . "+" . $expireTime . " minute ")));
        $rtmp_auth_md5 = md5("/" . $appName . "/" . $streamName . "-" . $timestatmp . "-0-0-" . $auth_key);
        $hls_auth_md5 = md5("/" . $appName . "/" . $streamName . ".m3u8-" . $timestatmp . "-0-0-" . $auth_key);

        $authUrl['push_flow_address'] = "rtmp://$sourceName/$appName/$streamName?vhost=$domainName&auth_key=" . $timestatmp . "-0-0-" . $rtmp_auth_md5;
        $authUrl['play_rtmp_address'] = "rtmp://$domainName/$appName/$streamName?auth_key=" . $timestatmp . "-0-0-" . $rtmp_auth_md5;
        $authUrl['play_m3u8_address'] = "http://$cdn/$appName/$streamName.m3u8?auth_key=" . $timestatmp . "-0-0-" . $hls_auth_md5;
        $authUrl['hash_value'] = 'startTime = ' . $startTime . ' expireTime = ' . $expireTime . ' timestatmp = ' . $timestatmp . "uri = /" . $appName . "/" . $streamName . ".m3u8-" . $timestatmp . "-0-0-" . $auth_key;
        return $authUrl;
    }

    /**
     * @return mixed
     */
    public function index()
    {
        return $this->fetch();
    }

    /**
     * 活动管理
     * @return mixed
     */
    public function liveIndex()
    {
        $lives = Db::table('resty_live')->select();
        $this->assign('categorys', $lives);
        return $this->fetch();
    }

    /**
     * 保存
     */
    public function liveStore()
    {
        if (request()->isPost()) {
            $streamName = '400' . time();
            $startTime = date('Y-m-d H:i:s', time());
            $res = $this->db->store(input('post.'));
            if ($res["valid"]) {
                $this->success($res["msg"], "backend/live/liveIndex");
                exit;
            } else {
                $this->error($res["msg"]);
                exit;
            }
        }
    }

    /**
     * 活动详细信息
     */
    public function liveDetail()
    {
        $liveId = input('param.id');
        $videos = Db::table('resty_stream_video')->where('streamName',$liveId)->order('createTime desc')->paginate(12);
        $this->assign('videos', $videos);
        return $this->fetch();
    }

    /**
     * 预览观看
     */
    public function liveView()
    {
        $id = input('param.id');
        $this->assign('live', Db::table('resty_live')->where('id', $id)->find());
        return $this->fetch();
    }

    /**
     * 录像列表
     * @return mixed
     */
    public function indexRecord()
    {
        if (request()->isPost()) {
            $liveStartTime = input('post.liveStartTime');
            halt($liveStartTime);
        }
        $liveId = input('param.id');
        $videos = Db::table('resty_stream_video')->where('streamName',$liveId)->order('createTime desc')->paginate(12);
        $this->assign('videos', $videos);
        return $this->fetch();
    }

    /**
     * 录像操作
     */
    public function recordHandle()
    {
        if ($this->request->isAjax()) {
            $data = input('post.');
            $res = $this->db->recordHandle($data);
            if ($res['valid']) {
                return json(['code' => 200, 'msg' => $res["msg"]]);
            }
            return json(['code' => 500, 'msg' => $res["msg"]]);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }
}