<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/10/21 22:50
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/

namespace app\backend\controller;


use aliyun\oss\Oss;
use app\common\controller\BaseBackend;
use OSS\Core\OssException;
use think\Image;
use think\Log;

class Banner extends BaseBackend
{    protected $db;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Banner();
    }

    /**
     * 列表
     * @return mixed
     */
    public function index()
    {
        $categorys = db('category')->where('pid',129)->order('id desc')->select();
        $this->assign('categorys', $categorys);
        $this->assign('banners', db('banner')->where('deleted',0)->order('id desc')->paginate(6));
        return $this->fetch();
    }

    /**
     * 保存
     * @return \think\response\Json
     */
    public function store()
    {
        if (request()->isPost()) {
            //图片上传
            if ($_FILES['thumb']['tmp_name']) {
                $data = input('post.');
                $file = request()->file("thumb");
                //开始一个缩略图，直接获取当前请求中的文件上传对象
                $image = Image::open($file);
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->rule("uniqid")->move(ROOT_PATH . 'public' . DS . 'uploads/banner');
                if ($info) {
                    // oss upload
                    $oss = Oss::Instance();
                    $bucket = config('aliyun_oss.BUCKET');
                    $thumbName = 'thumb_' . $info->getFilename();
                    //获取文件名
                    $data['thumb'] = $thumbName;

                    /**
                     * 注意：这里定义的$ossbObject 目录路径为OSS 上存储的路径信息,可以自定义的，最好在配置文件中配置最好了
                     */
                    $ossbObject = 'uploads/banner';
                    //  定义缩略图保存路径
                    $thumbObjectPath = ROOT_PATH . 'public' . DS . 'uploads/banner' . DS . $thumbName;
                    // 按照原图的比例生成一个最大为150*150的缩略图并保存为thumb.png
                    $image->thumb(240, 150)->save($thumbObjectPath);
                    $localDirectory = $ossbObject . DS;
                    try {
                        $oss->uploadDir($bucket, $ossbObject, $localDirectory);
                        $data['oss_upload_status'] = 1;
                        $data['image_thumb'] = '/'.$ossbObject.DS.$thumbName;
                        $data['image_origin'] = '/'.$ossbObject.DS.$info->getFilename();
                        // 遍历删除原图和缩略图
                        $this->rmdirs(ROOT_PATH . 'public' . DS . 'uploads/banner');
                    } catch (OssException $e) {
                        $data['oss_upload_status'] = json_encode($e->getMessage());
                    }
                }
                $res = $this->db->store($data);
                if ($res["valid"]) {
                    $this->success($res["msg"], "backend/banner/index");
                    exit;
                } else {
                    return json(['code' => 500, 'msg' => $res["msg"]]);
                }
            }
            return json(['code' => 500, 'msg' => "Not Forbidden"]);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }

    /**
     * 显示编辑资源表单页
     */
    public function edit($id){
        $banner =  db('banner')->where('id',$id)->find();
        $categorys = db('category')->where('pid',129)->order('id desc')->select();
        $this->assign('categorys', $categorys);
        $this->assign('banner',$banner);
        return $this->fetch();
    }

    /**
     * 保存更新的资源
     */
    public function update(){
        // 获取提交过来的所有数据，包括文件 : $this->request->param(true);
        if (request()->isPost()) {
            $data = input('post.');
            if ($_FILES['thumb']['tmp_name']) {
                $file = request()->file("thumb");
                // 移动到框架应用根目录/public/uploads/ 目录下
                $info = $file->rule("uniqid")->move(ROOT_PATH . 'public' . DS . 'uploads/banner');
                if ($info) {
                    // oss upload
                    $oss = Oss::Instance();
                    $bucket = config('aliyun_oss.BUCKET');
                    $thumbName = 'thumb_' . $info->getFilename();
                    //获取文件名
                    $data['thumb'] = $thumbName;

                    /**
                     * 注意：这里定义的$ossbObject 目录路径为OSS 上存储的路径信息,可以自定义的，最好在配置文件中配置最好了
                     */
                    $ossbObject = 'uploads/banner';
                    //  定义缩略图保存路径
                    $localDirectory = $ossbObject . DS;
                    /**
                     * 缩略图上传、原始图上传
                     */
                    try {
                        $oss->uploadDir($bucket, $ossbObject, $localDirectory);
                        $data['oss_upload_status'] = 1;
                        $data['image_thumb'] = '/'.$ossbObject.DS.$thumbName;
                        $data['image_origin'] = '/'.$ossbObject.DS.$info->getFilename();
                        // 遍历删除原图和缩略图
                        $this->rmdirs(ROOT_PATH . 'public' . DS . 'uploads/banner');
                    } catch (OssException $e) {
                        $data['oss_upload_status'] = json_encode($e->getMessage());
                    }
                }
                $res = $this->db->edit($data);
                if ($res["valid"]) {
                    $this->success($res["msg"], "backend/banner/index");
                    exit;
                } else {
                    return json(['code' => 500, 'msg' => $res["msg"]]);
                }
            }else{
                $res = $this->db->edit($data);
                if ($res["valid"]) {
                    $this->success($res["msg"], "backend/banner/index");
                    exit;
                } else {
                    return json(['code' => 500, 'msg' => $res["msg"]]);
                }
            }
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }

    /**
     * 删除操作
     * @return \think\response\Json
     */
    public function del()
    {
        if ($this->request->isAjax()) {
            $id = input('post.id');
            $res = $this->db->del($id);
            if ($res['valid']) {
                return json(['code' => 200, 'msg' => $res["msg"]]);
            }
            return json(['code' => 500, 'msg' => $res["msg"]]);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }

    /**
     * 发布操作
     * @return \think\response\Json
     */
    public function publish()
    {
        if ($this->request->isAjax()) {
            $id = input('post.id');
            $publishStatus = input('post.publish_status');
            $res = $this->db->publish($id,$publishStatus);
            if ($res['valid']) {
                return json(['code' => 200, 'msg' => $res["msg"]]);
            }
            return json(['code' => 500, 'msg' => $res["msg"]]);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }
}