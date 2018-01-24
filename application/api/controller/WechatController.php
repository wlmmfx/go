<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/24 9:32
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\controller;


use app\common\controller\BaseController;
use think\Db;

class WechatController extends BaseController
{
    /**
     * 用户播放列表
     */
    public function userPlayLists()
    {
        $curlReq = curl_request('http://music.163.com/api/search/get/',[
            's'=>'成都',
            'limit'=>'10',
            'type'=>'1',
            'offset'=>'0',
        ]);
        $arrRes = json_decode($curlReq,true);
        $newArr = [];
        foreach ($arrRes['result']['songs'] as $key=>$val){
            $newArr[] = [
                'id'=>$val['id'],
                'name'=>$val['name'],
                'creatorId'=>$val['artists'][0]['id'],
                'creator'=>$val['artists'][0]['name'],
                'img1v1Url'=>$val['artists'][0]['img1v1Url'],
            ];
        }
        $res = [
            'code' => 200,
            'msg' => 'success',
            'playlist' =>[
                'item'=> $newArr,
                'songCount'=> $arrRes['result']['songCount'],
            ]
        ];
        return json($res);
    }

    public function usersPlayLists()
    {
        $str = "curl -d \"s=玫瑰色的你&limit=20&type=1&offset=0\" -b \"appver=1.5.2;\" http://music.163.com/api/search/get/";
        $curlReq = curl_request('http://music.163.com/api/search/get/',[
            's'=>'婚礼',
            'limit'=>'10',
            'type'=>'1',
            'offset'=>'0',
        ]);
        $arrRes = json_decode($curlReq,true);
        $newArr = [];
        foreach ($arrRes['result']['songs'] as $key=>$val){
            var_dump($val);
            $newArr[] = [
                'id'=>$val['id'],
                'name'=>$val['name'],
                'creatorId'=>$val['artists'][0]['id'],
                'creator'=>$val['artists'][0]['name'],
                'img1v1Url'=>$val['artists'][0]['img1v1Url'],
            ];
        }
        die;
        $res = [
            'code' => 200,
            'msg' => 'success',
            'playlist' =>[
                'item'=> $newArr,
                'songCount'=> $arrRes['result']['songCount'],
            ]
        ];
        return json($res);
    }
}