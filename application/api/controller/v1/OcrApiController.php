<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/4/14 22:42
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;

use app\common\controller\BaseApiController;
use baidu\sdk\BaiDuOcr;

class OcrApiController extends BaseApiController
{
    /**
     * url https://www.tinywan.com/api/v1/ocr/baiDuBasicAccurate
     * @return string
     */
    public static function baiDuBasicAccurate($imgPath)
    {
        if (empty($imgPath)) {
            return '图片不经不能为空';
        }
        // ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . 'ocr0007.png'
        $APP_ID = config('baidu')['api']['APP_ID'];
        $API_KEY = config('baidu')['api']['API_KEY'];
        $SECRET_KEY = config('baidu')['api']['SECRET_KEY'];
        $client = new BaiDuOcr($APP_ID, $API_KEY, $SECRET_KEY);
        $image = file_get_contents($imgPath);

        // 如果有可选参数
        $options["detect_direction"] = "true";
        $options["probability"] = "true";

        // 带参数调用通用文字识别（高精度版）
        $res = $client->basicAccurate($image, $options);
        $str = "";
        foreach ($res['words_result'] as $key => $val) {
            $str .= $val['words'] . "</br>";
        }
        return $str;
    }
}