<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/6 9:30
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller;


use aliyun\api\Live;
use Curl\Curl;
use think\Controller;
use think\Db;
use think\Log;
use think\Request;

class Stream extends Controller
{

    /**
     * @return Request
     * @static
     */
    protected static function request()
    {
        return Request::instance();
    }

    /**
     * @return Curl
     * @static
     */
    protected static function curl()
    {
        $curl = new Curl();
        return $curl;
    }


    /**
     * 获取一个签名
     * @param $allParam
     * @return string
     * @static
     */
    public static function getApiSign($allParam)
    {
        $appSecret = 'f48d03070f4572069dfafab41027a913a50ea06e';
        foreach ($allParam as $key => $value) {
            $sortParam[$key] = $key;
        }
        sort($sortParam);
        $str = "";
        foreach ($sortParam as $k => $v) {
            $str = $str . $sortParam[$k] . $allParam[$v];
        }
        $finalStr = $str . $appSecret;
        $sign = strtoupper(sha1($finalStr));
        return $sign;
    }

    /**
     * 直播回调URL
     * {
     * "action":"publish",
     * "ip":"113.215.160.43",
     * "id":"8001513150121",
     * "app":"lives.tinywan.com",
     * "appname":"live",
     * "time":"1513228146",
     * "usrargs":"vhost=lives.tinywan.com&alilive_streamidv2=p011135161073.eu13_31375_108971977_1513228145425",
     * "node":"eu13"
     * }
     */
    public function pushCallbackUrl()
    {
        $request = self::request();
        $action = $request->get('action');
        $clientIP = $request->get('ip');
        $domainName = $request->get('app');
        $appName = $request->get('appname');
        $streamName = $request->get('id');
        $time = $request->get('time');
        $usrargs = $request->get('usrargs');
        $node = $request->get('node');
        if (empty($action) || empty($streamName)) {
            $this->redirect("https://www.tinywan.com/", 302);
        }
        $curl = self::curl();
        $redis = messageRedis();
        //推流限制
        $recordServiceIP = $redis->hGet('GLOBAL_STREAM_DATA:' . $streamName, 'recordServiceIP');
        $notifyUrl = $redis->hGet('GLOBAL_STREAM_DATA:' . $streamName, 'notify_url');
        $streamId = $redis->hGet('GLOBAL_STREAM_DATA:' . $streamName, 'streamId');
        if ($action == 'publish') {
            //推流有效性检测
            $StreamNameValidity = $redis->sIsMember('GLOBAL_STREAM_WHITE_LIST', $streamName);
            if ($StreamNameValidity == false || $StreamNameValidity == '') {
                $redis->zAdd('GLOBAL_STREAM_BLACK_LIST', time(), $streamName);
                try {
                    $forbid = Live::setAliForbidLiveStream($domainName, $appName, $streamName, prcToUtc('2036-12-03  09:15:00'));
                    Log::debug('[' . getCurrentDate() . ']:' . $streamName . '禁止直播流成功,Response：' . json_encode($forbid));
                } catch (\Exception $e) {
                    Log::error('[' . getCurrentDate() . ']:' . $streamName . '禁止直播流失败,Error：' . json_encode($e->getMessage()));
                }
                return json([0]);
            }
            $tabRes = self::addPushFlowRecord($streamName, $clientIP, $action, $domainName, $appName, $time, $usrargs, $node, $action_str = '321312');
            //是否需要拉流
            //notify_url地址回调，添加到消息队列中去
            if ($notifyUrl != false){
                $msgRes = addCallbackTaskQueue($action,$notifyUrl,$streamId,$streamName);
                Log::error('------添加到消息队列中去-----'.json_encode($msgRes));
            }
            Log::debug('[' . getCurrentDate() . ']:' . '[1] 数据库表记录插入Id = ' . $tabRes);
        } elseif ($action == 'publish_done') {
            //结束推流事件
            if ($notifyUrl != false){
                $msgRes = addCallbackTaskQueue($action,$notifyUrl,$streamId,$streamName);
                Log::error('------添加到消息队列中去-----'.json_encode($msgRes));
            }
            //更新推流记录表
            $tabRes = self::addPushFlowRecord($streamName, $clientIP, $action, $domainName, $appName, $time, $usrargs, $node, $action_str = "dssssssss");
            Log::debug('[' . getCurrentDate() . ']:' . '[2] 数据库表记录更新 结果 = ' . json_encode($tabRes));
            //-------------------------------------notify_url地址回调---------------------------------------------------
            $notifyUrl = $redis->hGet('GLOBAL_STREAM_DATA:' . $streamName, 'notify_url');
        } else {
            Log::error('[' . getCurrentDate() . ']:' . $streamName . ':未知推流消息事件,Error：\n');
        }
        return json([0]);
    }

    /**
     * 添加推流记录到MySQL数据
     * @param $streamName
     * @param $clientIP
     * @param $action
     * @param $domainName
     * @param $appName
     * @param $usrargs
     * @param $node
     * @param $action_str
     * @return \think\response\Json
     */
    public static function addPushFlowRecord($streamName, $clientIP, $action, $domainName, $appName, $time, $usrargs, $node, $action_str)
    {
        $redis = messageRedis();
        if ($action == 'publish') {
            $data = [
                'client_ip' => $clientIP,
                'action' => $action,
                'domain_name' => $domainName,
                'app_name' => $appName,
                'stream_name' => $streamName,
                'start_time' => $time,
                'usrargs' => $usrargs,
                'node' => $node,
                'curl' => $action_str,
                'sign' => 1,
                'create_time' => getCurrentDate()
            ];
            try {
                $insertId = Db::table('resty_push_flow_record')->insertGetId($data);
                Log::info('[' . getCurrentDate() . ']:' . $streamName . ' insert success insertId==' . $insertId);
                $redis->hMset('GLOBAL_STREAM_DATA:' . $streamName, [
                    'currentLocation' => 'startStream',
                    'startStream' => 1,
                    'startStreamTime' => getCurrentDate(),
                    'recordId' => $insertId,
                    'stopStream' => 0,
                    'stopStreamTime' => null,
                    'startRecord' => 0,
                    'startRecordTime' => null,
                    'stopRecord' => 0,
                    'stopRecordTime' => null,
                    'autoStopRecord' => 0,
                ]);
            } catch (\Exception $e) {
                Log::error('[' . getCurrentDate() . ']:' . ' push_flow_record 表数据异常，错误信息:' . $e->getMessage());
                return json_encode($e->getMessage());
            }
            return $insertId;
        } elseif ($action == 'publish_done') {
            try {
                $recordId = $redis->hGet('GLOBAL_STREAM_DATA:' . $streamName, 'recordId');
                $updateData = [
                    'stop_time' => $time,
                    'sign' => 0,
                    'action' => $action
                ];
                // $updateId = 1
                $updateId = Db::table('resty_push_flow_record')->where('id', $recordId)->update($updateData);
                $redis->hMset('GLOBAL_STREAM_DATA:' . $streamName, [
                    'currentLocation' => 'stopStream',
                    'startStream' => 0,
                    'stopStream' => 1,
                    'stopStreamTime' => $time,
                ]);
                Log::info('[' . getCurrentDate() . ']:' . $streamName . ' insert success insertId==' . $recordId);
            } catch (\Exception $e) {
                Log::error('[' . getCurrentDate() . ']:' . ' push_flow_record 表发修改数据异常，错误信息:' . $e->getMessage());
                return json_encode($e->getMessage());
            }
            return $updateId;
        }
        return json("未知的ID");
    }

    /**
     * 验证检查签名
     * @param $appId
     * @param $allParam
     * @return array|false|\PDOStatement|string|\think\Model
     * @static
     */
    protected static function checkApiSign($appId, $allParam)
    {
        // appSecret = sha1('eb9a365a9d37a1354e13ddd7973d5e02409ef451'.$userModel->mobile.time());
        //根据appId查询否存在该用户
        $userInfo = Db::table("resty_open_user")->where('app_id', ':app_id')->bind(['app_id' => $appId])->find();
        if (false == $userInfo) return false;
        $appSecret = $userInfo['app_secret'];  //$appSecret = sha1('http://sewise.www.com/');
        //去除最后的签名
        unset($allParam['_url']);
        unset($allParam['Sign']);
        // 1. 对加密数组进行字典排序
        foreach ($allParam as $key => $value) {
            $sortParam[$key] = $key;
        }
        // 2. 字典排序的作用就是防止因为参数顺序不一致而导致下面拼接加密不同
        sort($sortParam);
        // 3. 将Key和Value拼接
        $str = "";
        foreach ($sortParam as $k => $v) {
            $str = $str . $sortParam[$k] . $allParam[$v];
        }
        //3.将appSecret作为拼接字符串的后缀,形成最后的字符串
        $finalStr = $str . $appSecret;
        //4. 通过sha1加密,转化为大写大写获得签名
        $sign = strtoupper(sha1($finalStr));
        return $sign;
    }

    /**
     * 通过APPId获取Uid
     * @static
     */
    public static function getUidByAppId($appId)
    {
        $userInfo = Db::table("resty_open_user")->where('app_id', ':app_id')->bind(['app_id' => $appId])->find();
        return $userInfo['id'];
    }

    /**
     * 创建推流地址
     * @return \think\response\Json
     */
    public function createPushAddress()
    {
        //限制频繁的创建攻击
        $redis = messageRedis();
        $clientIP = $_SERVER['REMOTE_ADDR'];
        $clientKey = "REQUEST_RATE_LIMIT:{$clientIP}";
        $listClientIpLen = $redis->llen($clientKey);
        if ($listClientIpLen > 30) {
            $clientIPexpireTime = $redis->lIndex($clientKey, -1); //获取最后一个索引文件
            if (time() - $clientIPexpireTime < 60) {
                $result = [
                    'status_code' => 40301,
                    'msg' => 'Request exceeded limit,Please try again in 60 seconds',
                    'data' => null
                ];
                return json($result);
            } else {
                $redis->lPush($clientKey, time());
                $redis->lTrim($clientKey, 0, 3);
            }
        }
        $redis->lPush($clientKey, time());
        $appId = input('get.AppId/d');
        $sign = input('get.Sign/s');
        $domainName = input('get.DomainName/s');
        $appName = input('get.AppName/s');
        $expireTime = 900000;
        $authKeyStatus = 0;
        $autoStartRecord = 0;
        if (empty($domainName) || empty($appId) || empty($appName)) {
            $result = [
                'status_code' => 40601,
                'msg' => 'The input parameter not supplied',
                'data' => null
            ];
            return json($result);
        }
        //接口签名验证
        $checkSign = self::checkApiSign($appId, $this->request->get());
        if ($checkSign != $sign) {
            $result = [
                'status_code' => 40302,
                'msg' => 'Invalid signature or signature error',
                'data' => [
//                    'server' => $checkSign,
//                    'sign' => $sign,
                ]
            ];
            return json($result);
        }

        $streamINfo = Live::createPushFlowAddress($domainName, $appName, $expireTime, $authKeyStatus);
        $insertData = [
            'user_id' => self::getUidByAppId($appId),
            'domain_name' => $streamINfo['domainName'],
            'app_name' => $streamINfo['appName'],
            'stream_name' => $streamINfo['streamName'],
            'push_address' => $streamINfo['push_address'],
            'rtmp_address' => $streamINfo['rtmp_address'],
            'flv_address' => $streamINfo['flv_address'],
            'm3u8_address' => $streamINfo['m3u8_address'],
            'expire_time' => $streamINfo['expireTime'],
            'create_time' => $streamINfo['createTime'],
        ];
        $insertId = Db::table("resty_stream_name")->insertGetId($insertData);
        if (!$insertId) {
            $result = [
                'status_code' => 500,
                'msg' => '数据库发生异常，请稍后访问'
            ];
            return json($result);
        }
        $streamINfo['streamId'] = $insertId;

        #全局流数据
        $recordServiceIP = '10.117.19.148';  //内网使用拉流
        $recordServiceOuterIP = '78.43.78.78';  //外网使用查看是否有流信息
        $redis->del('GLOBAL_STREAM_DATA:' . $streamINfo['streamName']);//初始化
        $redis->hMset('GLOBAL_STREAM_DATA:' . $streamINfo['streamName'], [
            'domainName' => $domainName,
            'appName' => $appName,
            'streamId' => $insertId,
            'streamName' => $streamINfo['streamName'],
            'push_address' => $streamINfo['push_address'],
            'rtmp_address' => $streamINfo['rtmp_address'],
            'currentLocation' => 'createStream',
            'createStream' => true,
            'createStreamTime' => getCurrentDate(),
            'startStream' => 0,
            'stopStream' => 0,
            'startRecord' => $autoStartRecord,
            'authKeyStatus' => $authKeyStatus,
            'stopRecord' => 0,
            'autoStartRecord' => $autoStartRecord,
            'autoStopRecord' => 0,
            'recordServiceIP' => $recordServiceIP,
            'recordServiceOuterIP' => $recordServiceOuterIP, //查看当前流状态
            'outerIP' => $streamINfo['domainName'],
            'innerIP' => $streamINfo['domainName'],
            'recordStatus' => 'STOPPING',
            'node_ip' => $streamINfo['domainName'] . "|" . $recordServiceIP,
            'notify_url' => ""
        ]);
        // 全局流白名单
        $redis->sAdd('GLOBAL_STREAM_WHITE_LIST', $streamINfo['streamName']);
        $result = [
            'status_code' => 200,
            'msg' => 'success',
            'data' => $streamINfo
        ];
        return json($result);
    }

    /**
     * 查询推流历史
     * @return \think\response\Json
     */
    public function getLiveStreamsPublishListAction()
    {
        $DomainName = "lives.tinywan.com";    //'tinywan.amai8.com'
        $AppName = "live";   //'live'
        $StartTime = "2017-12-13 11:39:39";   //2017-12-13 11:39:39;
        $EndTime = "2017-12-13 19:54:16";   //2017-12-13 19:54:16;
        if (empty($DomainName) || empty($AppName) || empty($StartTime) || empty($EndTime)) {
            $result = [
                'status_code' => 500,
                'msg' => 'The input parameter that is mandatory for processing this request is not supplied ',
                'data' => null
            ];
            return json($result);
        }

        $StreamName = null;
        $result = Live::DescribeLiveStreamsPublishList($DomainName, $AppName, $StreamName, prcToUtc($StartTime), prcToUtc($EndTime));
        $result = [
            'status_code' => 200,
            'msg' => 'success',
            'data' => $result
        ];
        return json($result);
    }

    /**
     * 简单的PHP函数
     */
    public function createRange($number)
    {
        $data = [];
        for ($i = 0; $i < $number; $i++) {
            $data[] = time();
        }
        return $data;
    }

    /**
     *  打印结果：
     *  1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     * 1513174700
     */
    public function simpleFunction()
    {
        $result = $this->createRange(10); // 这里调用上面我们创建的函数
        foreach ($result as $value) {
            sleep(1); //这里停顿1秒，我们后续有用
            echo $value . '<br />';
        }
    }

    /**
     * 创建生成器
     * @param $number
     * @return \Generator
     */
    function yieldCreateRange($number)
    {
        for ($i = 0; $i < $number; $i++) {
            yield time();
        }
    }

    /**
     * 使用简单的生成器
     *  打印结果：
     *  1513174966
     * 1513174967
     * 1513174968
     * 1513174969
     * 1513174970
     * 1513174971
     * 1513174972
     * 1513174973
     * 1513174974
     * 1513174975
     */
    public function yieldSimpleFunction()
    {
        $result = $this->yieldCreateRange(10); // 这里调用上面我们创建的函数
        foreach ($result as $value) {
            sleep(1); //这里停顿1秒，我们后续有用
            echo $value . '<br />';
        }
    }

    /**
     * 实际开发应用
     */
    public function readTextFunction()
    {
        header("content-type:text/html;charset=utf-8");
        $handle = fopen(ROOT_PATH . 'public' . DS . 'uploads/test.txt', 'rb');
        while (feof($handle) === false) {
            # code...
            yield fgets($handle);
        }
        fclose($handle);
    }

    public function echoTextFunction()
    {
        foreach ($this->readTextFunction() as $key => $value) {
            echo $value . '<br />';
        }
        echo 11111111111;
    }


    /**
     * 手动创建一个签名
     */
    public function createSigin()
    {
        $userInfo = Db::table("resty_open_user")->where('id', 10)->find();
        echo $appId = base_convert(uniqid(), 16, 10);
        $appSecret = sha1('eb9a365a9d37a1354e13ddd7973d5e02409ef451' . $userInfo['mobile'] . time());
        halt($appSecret);
    }


    /**
     * 通过一个接口去创建一个流
     * @return \think\response\Json
     */
    public function createTestAddress()
    {
        //请求参数
        $appId = 1586740578218850;
        $domainName = 'lives.tinywan.com';
        $appName = 'live123';
        //签名密钥
        $appSecret = '35a41ca4b15fbdd68f9b35dc19709bc83561ebd7';
        //拼接字符串，注意这里的字符为首字符大小写，采用驼峰命名
        $str = "AppId" . $appId . "AppName" . $appName . "DomainName" . $domainName . $appSecret;
        //签名串，由签名算法sha1生成
        $sign = strtoupper(sha1($str));
        //请求资源访问路径以及请求参数，参数名必须为大写
        $url = "https://www.tinywan.com/api/stream/createPushAddress?AppId=" . $appId . "&AppName=" . $appName . "&DomainName=" . $domainName . "&Sign=" . $sign;
        //CURL方式请求
        $ch = curl_init() or die (curl_error());
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 360);
        $response = curl_exec($ch);
        curl_close($ch);
        //返回数据为JSON格式，进行转换为数组打印输出
        return json(json_decode($response, true));
    }

    /**
     * 回调测试
     * @return \think\response\Json
     */
    public function testNotifyUrl()
    {
        $redis = messageRedis();
        $redis->set("testNotifyUrl",json_encode($_GET));
        $redis->incr("testNotifyUrlNums");
        return json(['code'=>200]);
    }


    /**
     * 测试第三方回调地址
     */
    public function yieldSimpleWhile()
    {
        $curl = self::curl();
        $callback_url = "https://www.tinywan.com/api/stream/testNotifyUrl";
        $res = $callbackRes = $curl->get($callback_url, [
            'on_publish_time' => date('Y-m-d H:i:s'),
        ]);
        halt($res->code);
    }

    public function tinywanPackage()
    {
        $live = new \live\Live();
        halt($live);
    }


}