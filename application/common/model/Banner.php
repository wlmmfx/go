<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/9/10 16:43
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm
 * '-------------------------------------------------------------------*/
namespace app\common\model;

use think\Log;

class Banner extends BaseModel
{
    protected $pk = "id";
    protected $table = "resty_banner"; //完整的表名

    /**
     * 添加轮播图
     * @param $data
     * @return array
     */
    public function store($data)
    {
        $result = $this->save($data);
        if (false === $result) {
            // 验证失败 输出错误信息
            return ['valid' => 0, 'msg' => $this->getError()];
        }
        return ['valid' => 1, 'msg' => "添加成功"];
    }

    /**
     * 更新轮播图
     * @param $data
     * @return array
     */
    public function edit($data)
    {
        // id 已经存在的，只能更新
        $result = $this->save($data, [$this->pk => $data['id']]);
        if (false === $result) {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
        return ['valid' => 1, 'msg' => "更新成功"];
    }

    /**
     * @param $id
     * @return array
     */
    public function del($id)
    {
        $res = $this->where('id', $id)->update(['deleted' => 1]);
        if (false === $res) return ['valid' => 0, 'msg' => "删除失败"];
        return ['valid' => 1, 'msg' => "删除成功"];
    }

    /**
     * 发布操作
     * @param $id
     * @return array
     */
    public function publish($id,$publishStatus)
    {
        if($publishStatus == 1){
            $status = 0;
        }else{
            $status = 1;
        }
        Log::error('--------------------$publishStatus----------------'.$publishStatus);
        Log::error('--------------------$status----------------'.$status);
        $res = $this->where('id', $id)->update(['publish_status' => $status]);
        if (false === $res) return ['valid' => 0, 'msg' => "发布失败"];
        return ['valid' => 1, 'msg' => "发布成功"];
    }
}
