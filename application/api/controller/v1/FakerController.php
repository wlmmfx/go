<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/11/28 12:14
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller\v1;

use Faker\Factory;
use redis\BaseRedis;
use think\Controller;
use think\Db;

class FakerController extends Controller
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
     * API 路由地址：https://www.tinywan.com/v1/faker/hehuiyun
     * @return \think\response\Json
     */
    public function read($sign,$limit=10)
    {
        //如果需要设置允许所有域名发起的跨域请求，可以使用通配符 *
        header('Access-Control-Allow-Origin:*');
//        $sign = input('param.sign');
//        $page = input('param.page',10);
        if (empty($sign) || $sign!='hehuiyun') {
            $resJson = [
                'code' => 500,
                'msg' => 'param sign is null',
                'data' => null
            ];
        } else {
            $faker = Factory::create($locale = 'zh_CN');
            $fakerName = [];
            for ($i=0; $i < $limit; $i++) {
                $fakerName[] = [
                    'userName'=>$faker->name,
                    'City'=>$faker->city,
                    'Email'=>$faker->email,
                    'Company'=>$faker->company,
                    'Address'=>$faker->address,
                    'Color'=>$faker->hexColor,
                    'Mobile'=>$faker->phoneNumber,
                    'SafeColor'=>$faker->safeColorName,
                    'CreditCardNumber'=>$faker->creditCardNumber,
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

    public function user2()
    {
        //如果需要设置允许所有域名发起的跨域请求，可以使用通配符 *
        header('Access-Control-Allow-Origin:*');
        $sign = input('param.sign');
        $limit = input('param.limit',10,'int');
        if (empty($sign) || $sign!='hehuiyun' || empty($limit)) {
            $resJson = [
                'code' => 500,
                'msg' => 'param sign is error',
                'data' => null
            ];
        } else {
            $faker = Factory::create($locale = 'zh_CN');
            $fakerName = [];
            for ($i=0; $i < $limit; $i++) {
                $fakerName[] = [
                    'userName'=>$faker->name,
                    'City'=>$faker->city,
                    'Email'=>$faker->email,
                    'Company'=>$faker->company,
                    'Address'=>$faker->address,
                    'Color'=>$faker->hexColor,
                    'Mobile'=>$faker->phoneNumber,
                    'SafeColor'=>$faker->safeColorName,
                    'CreditCardNumber'=>$faker->creditCardNumber,
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