<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  DateTime: 2017/11/2 17:07
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;


use aliyun\oss\Oss;
use app\common\controller\BaseBackend;
use OSS\Core\OssException;
use think\Log;

class Common extends BaseBackend
{
    /**
     * 百度编辑器
     */
    public function ueditorUpload()
    {
        $ueditor_config = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents(ROOT_PATH . 'public' . DS . "common/plugins/ueditor/php/config.json")), true);
        $action = $_GET['action'];
        switch ($action) {
            case 'config':
                $result = json_encode($ueditor_config);
                break;
            /* 上传图片 */
            case 'uploadimage':
                /* 上传涂鸦 */
            case 'uploadscrawl':
                /* 上传视频 */
            case 'uploadvideo':
                /* 上传文件 */
            case 'uploadfile':
                $file = request()->file("upfile");
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads/common');
                if ($info) {
                    //获取文件名
                    $data['thumb'] = "/uploads/common/" . $info->getSaveName();
                    // oss upload
                    $bucket = config('aliyun_oss.BUCKET');
                    $object = 'uploads/common/' . $info->getSaveName();
                    $file = ROOT_PATH . 'public' . DS  . $object;  //这个才是文件在本地的真实路径，也是就是你要上传的文件信息
                    $oss = Oss::Instance();
                    try {
                        $res = $oss->uploadFile($bucket, $object, $file);
                        if ($res['info']['http_code'] == 200) {
                            // 返回数据
                            $url = config('aliyun_oss.DOMAIN'). $object;
                            Log::info("url == " . $url);
                            $result = json_encode(array(
                                'url' => $url,
                                'title' => htmlspecialchars("也是就是你要上传的文件信息", ENT_QUOTES),
                                'original' => $url,
                                'state' => 'SUCCESS'
                            ));
                            if (!is_dir($object)) unlink($object);
                        }
                    } catch (OssException $e) {
                        $url = "http://" . $_SERVER["HTTP_HOST"] . "/uploads/" . $info->getSaveName();
                        Log::info("url == " . $url);
                        $result = json_encode(array(
                            'url' => $url,
                            'title' => htmlspecialchars("OSS 上传文件OssException", ENT_QUOTES),
                            'original' => $url,
                            'state' => 'FAIL'
                        ));
                    }

                } else {
                    $result = json_encode(array(
                        'state' => $file->getError(),
                    ));
                }
                break;
            default:
                $result = json_encode(array(
                    'state' => '请求地址出错'
                ));
                break;
        }
        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state' => 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
}