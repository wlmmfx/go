<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/11/28 12:14
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller;


use Faker\Factory;
use redis\BaseRedis;
use think\Controller;
use think\Db;

class Faker extends Controller
{
    public function index(){
        $sign = input('param.sign');
        if (empty($sign)) {
            $resJson = [
                'code' => 500,
                'msg' => 'fail',
                'data' => null
            ];
        } else {
            $findRes = Db::table('resty_stream_video_edit')->where('task_id', $sign)->find();
            if(empty($findRes) || ($findRes == false)){
                $resJson = ['code' => 500, 'msg' => 'success', 'data' => null];
                return json($resJson);
            }
            $resJson = [
                'code' => 200,
                'msg' => 'success',
                'data' => json_decode($findRes['edit_config'])
            ];
        }
        return json($resJson);
    }

    /**
     * 测试数据
     * @return \think\response\Json
     */
    public function user1(){
        $sign = input('param.sign');
        if (empty($sign)) {
            $resJson = [
                'code' => 500,
                'msg' => 'param sign is null',
                'data' => null
            ];
        } else {
            $faker = Factory::create($locale = 'zh_CN');
            $fakerName = [];
            for ($i=0; $i < 10; $i++) {
                $fakerName[] = [
                    'userName'=>$faker->name,
                    'Email'=>$faker->email,
                    'Company'=>$faker->company,
                    'Address'=>$faker->address,
                    'Image'=>$faker->imageUrl($width = 640, $height = 480,'cats', true, 'Faker', true),
                    'DateTime'=>$faker->dateTimeThisCentury($max = 'now', $timezone = date_default_timezone_get()),
                ];
            }
            $resJson = [
                'code' => 200,
                'msg' => 'success',
                'data' =>$fakerName
            ];
        }
        return json($resJson);
    }

    public function book(){
        $redis = BaseRedis::location();
        halt($redis->lRange('L80001CommentsLate',0,10));
        $redis->set("USERNAME",'Tinywan');
        halt($redis);
    }
}