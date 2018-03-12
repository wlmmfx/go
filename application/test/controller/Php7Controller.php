<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/19 8:55
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\test\controller;


use baidu\sdk\AipOcr;
use think\Controller;

class Php7Controller extends Controller
{

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

    /**
     * 输出文本内容
     */
    public function echoTextFunction()
    {
        foreach ($this->readTextFunction() as $key => $value) {
            echo $value . '<br />';
        }
        echo 11111111111;
    }

    /**
     * @return false|object
     */
    public function sendAliSms()
    {
        $code = rand(100000, 999999);
        $sendRes = send_dayu_sms('18993807053', "register", ['code' => $code]);
        if (isset($sendRes->result->success) && ($sendRes->result->success == true)) {
            $res = [
                "code" => 200,
                "msg" => "验证码发送成功"
            ];
        } else {
            $res = [
                "code" => 500,
                "msg" => "验证码发送失败"
            ];
        }
        return json($res);
    }

    /**
     * ============================================================百度 OCr=============================================
     * 百度 OCr 通用文字识别
     * @return string
     */
    public function baiDuOcr01()
    {
        $APP_ID = '10715277';
        $API_KEY = 'Dd99XzzyS3W12Uqlu60iuPaR';
        $SECRET_KEY = '71puOI1fLlNfYVgYAjK0NGyPuHlK5XDE';
        $client = new AipOcr($APP_ID, $API_KEY, $SECRET_KEY);
        $image = file_get_contents(ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'ocr0004.png');

        // 如果有可选参数
        $options = array();
        $options["language_type"] = "CHN_ENG";
        $options["detect_direction"] = "true";
        $options["detect_language"] = "true";
        $options["probability"] = "true";

        // 带参数调用通用文字识别, 图片参数为本地图片
        $res = $client->basicGeneral($image,$options);
        $str = "";
        foreach ($res['words_result'] as $key => $val) {
            $str .= $val['words'] . "</br>";
        }
        return $str;
    }

    /**
     * 【通用文字识别（高精度版）】
     * 用户向服务请求识别某张图中的所有文字，相对于通用文字识别该产品精度更高，但是识别耗时会稍长。
     * 注意：图片不能够太大，否则会提示找不到文件，所以针对于长文章，在这里要做的就是限制图片大小
     * @return string
     */
    public function baiDuOcr02()
    {
        $APP_ID = '10715277';
        $API_KEY = 'Dd99XzzyS3W12Uqlu60iuPaR';
        $SECRET_KEY = '71puOI1fLlNfYVgYAjK0NGyPuHlK5XDE';
        $client = new AipOcr($APP_ID, $API_KEY, $SECRET_KEY);
        $image = file_get_contents(ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'ocr0007.png');

        // 调用通用文字识别（高精度版）
        //halt($client->basicAccurate($image));

        // 如果有可选参数
        $options = array();
        $options["detect_direction"] = "true";
        $options["probability"] = "true";

        // 带参数调用通用文字识别（高精度版）
        $res = $client->basicAccurate($image,$options);
        $str = "";
        foreach ($res['words_result'] as $key => $val) {
            $str .= $val['words'] . "</br>";
        }
        return $str;
    }

    /**
     * 【远程url图片】
     * 这里使用OSS存储图片格式
     * @return string
     */
    public function baiDuOcr03()
    {
        $APP_ID = '10715277';
        $API_KEY = 'Dd99XzzyS3W12Uqlu60iuPaR';
        $SECRET_KEY = '71puOI1fLlNfYVgYAjK0NGyPuHlK5XDE';
        $client = new AipOcr($APP_ID, $API_KEY, $SECRET_KEY);
        $url = "http://oss.tinywan.com/uploads/article/ocr0002.png";

        // 调用通用文字识别（含位置信息版）, 图片参数为远程url图片
        $res = $client->generalUrl($url);
        $str = "";
        foreach ($res['words_result'] as $key => $val) {
            $str .= $val['words'] . "</br>";
        }
        return $str;
    }

    /**
     * 【车牌识别】
     * 识别机动车车牌，并返回签发地和号牌
     * @return string
     */
    public function baiDuOcr04()
    {
        $APP_ID = '10715277';
        $API_KEY = 'Dd99XzzyS3W12Uqlu60iuPaR';
        $SECRET_KEY = '71puOI1fLlNfYVgYAjK0NGyPuHlK5XDE';
        $client = new AipOcr($APP_ID, $API_KEY, $SECRET_KEY);

        $image = file_get_contents(ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'chepaihao003.jpg');
        // 调用车牌识别
        $res = $client->licensePlate($image);
        $resArr = [
            '颜色'=>$res['words_result']['color'],
            '车牌号'=>$res['words_result']['number'],
        ];
        return json($resArr);
    }

    public function test(){
        //请求参数
        $appId = 75715888;
        $domainName = '10.51.10.172';
        $appName = 'live';
        //签名密钥
        $appSecret = 'f48d03070f4572069dfafab41027a913a50ea06e';
        //拼接字符串，注意这里的字符为首字符大小写，采用驼峰命名
        $str = "AppId" . $appId . "AppName" . $appName . "DomainName" . $domainName . $appSecret;
        //签名串，由签名算法sha1生成
        $sign = strtoupper(sha1($str));
        //请求资源访问路径以及请求参数，参数名必须为大写
        $url = "http://ssconsole.amaitech.com/openapi/createPushFlowAddress?AppId=" . $appId . "&AppName=" . $appName . "&DomainName=" . $domainName . "&Sign=" . $sign;
        //CURL方式请求
        $ch = curl_init() or die (curl_error());
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 360);
        $response = curl_exec($ch);
        curl_close($ch);
        //返回数据为JSON格式，进行转换为数组打印输出
        var_dump(json_decode($response, true));
    }
}














