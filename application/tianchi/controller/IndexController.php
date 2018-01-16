<?php

/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/16 9:41
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\tianchi\controller;


use app\api\controller\OAuthController;
use think\Controller;

class IndexController extends Controller
{
    /**
     * 使用PHP对图片进行base64解码输出
     * @param $image_file
     * @return string
     * @static
     */
    public static function base64EncodeImage($image_file)
    {
        $image_info = getimagesize($image_file);
        $image_data = fread(fopen($image_file, 'r'), filesize($image_file));
        $base64_image = 'data:' . $image_info['mime'] . ';base64,' . chunk_split(base64_encode($image_data));
        return $base64_image;
    }

    /**
     * 根据车牌图片获取车牌号
     * @param $base64_str
     * @return mixed
     * @static
     */
    public static function getNumberPlate($base64_str)
    {
        $host = "http://jisucpsb.market.alicloudapi.com";
        $path = "/licenseplaterecognition/recognize";
        $method = "POST";
        $appcode = "e607e7ed7d78441097c6eb6fddd309b1";
        $headers = array();
        array_push($headers, "Authorization:APPCODE " . $appcode);
        //根据API的要求，定义相对应的Content-Type
        array_push($headers, "Content-Type" . ":" . "application/x-www-form-urlencoded; charset=UTF-8");
        $querys = "";
        $urlencode = urlencode($base64_str);
        $bodys = "pic=" . $urlencode;
        $url = $host . $path;

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_FAILONERROR, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if (1 == strpos("$" . $host, "https://")) {
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        }
        curl_setopt($curl, CURLOPT_POSTFIELDS, $bodys);
        $res = json_decode(curl_exec($curl), true);
        curl_close($curl);
        return $res;
    }

    /**
     *
     * @return mixed
     */
    public function index()
    {
//        if(!is_wechet()) return "请用微信登陆";
        $currentUri = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
        halt($currentUri);
        OAuthController::weChatRedirect();
        return $this->fetch();
    }

    /**
     *
     * @return mixed
     */
    public function test()
    {
        // 注意：这里的图片必须是在服务器本地，才可转换的哦，所以要使用OSS下载车辆牌照照片
        $str = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'chepaihao002.jpg';
        $base64Str = self::base64EncodeImage($str);
        $base64StrFotmat = explode(',', $base64Str)[1];
        $res = self::getNumberPlate($base64StrFotmat);
        if ($res['status'] != 0) return $res['msg'];
        $busCode = $res['result']['number'];
        echo $busCode;
    }

    public function index456()
    {
    }
}