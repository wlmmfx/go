<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/21 17:15
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\common\model;


use think\Db;

class WxBanner extends BaseModel
{
    // 根据bannerId 获取banner 信息
    public static function getBannerById($id)
    {
//        $res = Db::query('select * from resty_wx_banner_item WHERE banner_id=?',[$id]);
        //$res = Db::table('resty_wx_banner_item')->where('banner_id', '=', $id)// 返回的是一个query对象
        //->find(); //query对象 执行SQL语句执行查询结果集

        // 闭包查询发
        $res = Db::table("resty_wx_banner_item")->where(function ($query) use ($id) {
            $query->where('banner_id', '=', $id);
        })->find();
        return $res;
    }
}