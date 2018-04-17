<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/4/16 16:28
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\share\controller;


use app\api\controller\v1\OcrApiController;
use think\Controller;

class OcrController extends Controller
{
    public function imgToTxt()
    {
        return $this->fetch();
    }

    /**
     * 识别图片上传
     * @return \think\response\Json
     */
    public function imageChangeTextContentPost()
    {
        if (request()->isPost()) {
            $file = request()->file("img_file");
            if ($file) {
                $savePath = ROOT_PATH . 'public' . DS . 'tmp';
                $info = $file->validate(['size' => config('upload_config')['web']['size'] * 1024, 'ext' => config('upload_config')['web']['ext']])->rule("uniqid")->move($savePath);
                if ($info) {
                    $originImg = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'tmp' . DIRECTORY_SEPARATOR . $info->getSaveName();
                    $strContent = OcrApiController::baiDuBasicAccurate($originImg);
                    unlink($originImg);
                    $res = [
                        'code' => 200,
                        'msg' => "OK",
                        'data' => [
                            'content' => $strContent,
                            'image_path' => 'thumb_' . $info->getSaveName()
                        ]
                    ];
                } else {
                    $res = [
                        'code' => 500,
                        'msg' => $file->getError()
                    ];
                }
                return json($res);
            }
            return json(['code' => 500, 'msg' => "upload file name error"]);
        }
    }
}