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

use houdunwang\arr\Arr;
use think\Db;
use think\Log;

class Vod extends BaseModel
{
    protected $pk = "id";
    protected $table = "resty_vod"; //完整的表名

    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = true;

    //如果数据库没有创建update_time和更新时间update_time,则按照以下的做一个映射既可以
    protected $createTime  = "create_time";
    protected $updateTime  = "update_time";
    //软删除的映射
    protected $deleteTime = "delete_time";

    //插入的时候自动完成
    protected $insert = [
        "create_time"
    ];

    //更新自动完成
    protected $update = [
        "update_time"
    ];

    //初始化处理
    protected static function init()
    {

    }

    /**
     * 获取所有
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function getAll(){
        return Db::table("resty_article")
            ->alias('a')
            ->join('__CATEGORY__ c','a.cate_id = c.id')
            ->field("a.title,a.create_time,a.update_time,a.id,c.name as c_name")
            ->order("a.create_time desc,a.id desc")
            ->select();
    }

    /**
     * 添加
     * @param $data
     * @return array
     */
    public function store($data)
    {
        // tags 验证
        if(!isset($data['tag'])){
            return ['valid' => 0, 'msg' => "必须选择一个标签"];
        }
        /**
         * 1、调用当前模型对应的Vod验证器类进行数据验证
         * 2、过滤post数组中的非数据表字段数据 allowField(true)
         */
        $result = $this->allowField(true)->save($data);
        foreach ($data['tag'] as $v){
            $relData [] = [
                'vod_id'=>$this->id,
                'tag_id'=>$v
            ];
        }
        $resultTag = (new VodTag())->saveAll($relData);
        if (false === $result || $resultTag == false) {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
        return ['valid' => 1, 'msg' => "添加成功"];
    }

    /**
     * 编辑
     * @param $data
     * @return array
     */
    public function edit($data)
    {
        // tags 验证
        if(!isset($data['tag'])){
            return ['valid' => 0, 'msg' => "必须选择一个标签"];
        }
        $result = $this->allowField(true)->save($data, [$this->pk => $data['id']]);
        //先删除后添加，负责这里会出现Bug,那就是重复
        $vodTag = new VodTag();
        $vodTag->where('vod_id',$data['id'])->delete();
        foreach ($data['tag'] as $v){
            $relData [] = [
                'vod_id'=>$this->id,
                'tag_id'=>$v
            ];
        }
        $resultTag = $vodTag->saveAll($relData);
        // id 已经存在的，只能更新
        if (false === $result  || $resultTag == false) {
            return ['valid' => 0, 'msg' => $this->getError()];
        }
        return ['valid' => 1, 'msg' => "编辑成功"];
    }

    /**
     * 删除
     * @param $id
     * @return array
     */
    public function del($id)
    {
        //1 获取当前删除数据id 的pid的值
        $pid = $this->where('id', $id)->value('pid');
        //2 将当前要删除的id的子集数据的pid 修改为删除数据自己的pid ,这样子就做到了往上提一级的概念
        $this->where('pid', $id)->update(['pid' => $pid]);
        //3 执行当前数据的删除
        $res = Category::destroy($id);
        if (false === $res) return ['valid' => 0, 'msg' => "删除失败"];
        return ['valid' => 1, 'msg' => "删除成功"];
    }
}
