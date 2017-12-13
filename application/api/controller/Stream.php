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
use think\Controller;
use think\Db;
use think\Log;

class Stream extends Controller
{
    /**
     * 直播回调URL
     */
    public function pushCallbackUrl()
    {
        Log::error("pushCallbackUrl---" . json_encode($_GET));
    }

    /**
     * 通过一个接口去创建一个流
     * @return \think\response\Json
     */
    public function createStreamAddress()
    {
        //请求参数
        $appId = 757158805;
        $domainName = 'live.tinywan.com';
        $appName = 'live';
        //签名密钥
        $appSecret = 'e2c18ce6683bd185f7565ea606bda0314e7c5a49';
        //拼接字符串，注意这里的字符为首字符大小写，采用驼峰命名
        $str = "AppId" . $appId . "AppName" . $appName . "DomainName" . $domainName . $appSecret;
        //签名串，由签名算法sha1生成
        $sign = strtoupper(sha1($str));
        //请求资源访问路径以及请求参数，参数名必须为大写
        $url = "http://live.tinywan.com/openapi/createPushFlowAddress?AppId=" . $appId . "&AppName=" . $appName . "&DomainName=" . $domainName . "&Sign=" . $sign;
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
     * 创建推流地址
     * @return \think\response\Json
     */
    public function createPushAddress()
    {
        $appId = 13669361192;
        $expireTime = 900000;
        $authKeyStatus = 0;
        $autoStartRecord = 0;
        $domainName = 'lives.tinywan.com';
        // stream.tinywan.com CNAME 到 video-center.alivecdn.com
        $sourceName = "stream.tinywan.com";
        $appName = "live";
        if (empty($domainName) || empty($appId) || empty($appName)) {
            $result = [
                'status_code' => 40601,
                'msg' => 'The input parameter not supplied',
                'data' => null
            ];
            return json($result);
        }

        $streamINfo = Live::createPushFlowAddress($domainName, $appName, $expireTime, $authKeyStatus);
        $insertData = [
            'user_id' => time(),
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
        $res = Db::table("resty_stream_name")->insert($insertData);

        $result = [
            'status_code' => 200,
            'msg' => 'success',
            'data' => $streamINfo
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
        1513174700
        1513174700
        1513174700
        1513174700
        1513174700
        1513174700
        1513174700
        1513174700
        1513174700
     */
    public function simpleFunction()
    {
        $result = $this->createRange(10); // 这里调用上面我们创建的函数
        foreach($result as $value){
            sleep(1); //这里停顿1秒，我们后续有用
            echo $value.'<br />';
        }
    }

    /**
     * 创建生成器
     * @param $number
     * @return \Generator
     */
    function yieldCreateRange($number){
        for($i=0;$i<$number;$i++){
            yield time();
        }
    }

    /**
     * 使用简单的生成器
     *  打印结果：
     *  1513174966
        1513174967
        1513174968
        1513174969
        1513174970
        1513174971
        1513174972
        1513174973
        1513174974
        1513174975
     */
    public function yieldSimpleFunction()
    {
        $result = $this->yieldCreateRange(10); // 这里调用上面我们创建的函数
        foreach($result as $value){
            sleep(1); //这里停顿1秒，我们后续有用
            echo $value.'<br />';
        }
    }

    /**
     * 实际开发应用
     */
    public function readTextFunction()
    {
        header("content-type:text/html;charset=utf-8");
        $handle = fopen(ROOT_PATH . 'public' . DS . 'uploads/test.txt', 'rb');
        while (feof($handle)===false) {
            # code...
            yield fgets($handle);
        }
        fclose($handle);
    }

    public function echoTextFunction()
    {
        foreach ($this->readTextFunction() as $key => $value) {
            echo $value.'<br />';
        }
        echo 11111111111;
    }

}