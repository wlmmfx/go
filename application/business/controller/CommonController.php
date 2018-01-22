<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/22 13:41
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\business\controller;


use app\common\controller\BaseController;
use think\Cookie;

class CommonController extends BaseController
{
    /**
     * 图片上传方法
     */
    public function upload()
    {
        if ($this->request->file('file')) {
            $file = $this->request->file('file');
        } else {
            $res = [
                "code" => 1,
                "msg" => '没有上传文件'
            ];
            return json($res);
        }
        $module = $this->request->has('module') ? $this->request->param('module') : 'common';//模块
        $savePath = ROOT_PATH . 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR;
        $info = $file->rule('date')->move($savePath);
        if ($info) {
            //写入到附件表
            $insertData = [
                'pid' => 0,
                'path' => $info->getSaveName(),
                'min_path' => $info->getSaveName()
            ];
            $data = [];
            $data['module'] = $module;
            $data['filename'] = $info->getFilename();//文件名
            $data['filepath'] = DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . $info->getSaveName();//文件路径
            $data['fileext'] = $info->getExtension();//文件后缀
            $data['filesize'] = $info->getSize();//文件大小
            $data['create_time'] = time();//时间
            $data['uploadip'] = $this->request->ip();//IP
            $data['user_id'] = Cookie::has($module) ? Cookie::get($module) : 0;
            $data['use'] = $this->request->has('use') ? $this->request->param('use') : 'common';//用处
            $res['id'] = Db::name('attachment')->insertGetId($data);
            $res['src'] = DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR  . $info->getSaveName();
            $res['code'] = 2;
            return json($res);
        } else {
            // 上传失败获取错误信息
            return $this->error('上传失败：' . $file->getError());
        }
    }
}