<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/1/23 18:03
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: 微信小程序接口
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
            's'=>'欢乐颂2',
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

    /**
     * 微信小程序---搜索歌曲
     * API：https://www.tinywan.com/api/wechat/songsSearch?name=去哪儿&offset=0&type=1&limit=10
     * @return \think\response\Json
     */
    public function songsSearch()
    {
        $name = input('param.name');
        $offset = input('param.offset');
        $limit = input('param.limit');
        $type = input('param.type');
//        return json([
//           'name'=>$name,
//           'offset'=>$offset,
//           'limit'=>$limit,
//           'type'=>$type
//        ]);
        $curlReq = curl_request('http://music.163.com/api/search/get/',[
            's'=>$name,
            'limit'=>$limit,
            'type'=>$type,
            'offset'=>$offset,
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

    /**
     * 测试环境
     * @return \think\response\Json
     */
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