<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/28 13:21
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\backend\controller;


use app\common\controller\BaseBackendController;
use app\common\model\CarCustomer;
use app\common\model\OpenUser;
use think\Db;

class OpenUserController extends BaseBackendController
{
    /**
     * 使用模型查询数据
     * @return mixed
     */
    public function index()
    {
        if ($this->request->isPost()) {
            $keyword = input('post.keyword');
            $condition = [
                'account|address|nickname|type' => ['like', '%' . $keyword . '%'],
            ];
            $user = OpenUser::where($condition)
                ->field('id,open_id,account,realname,nickname,avatar,ip,company,address,create_time,type,score')
                ->order('create_time desc')
                ->paginate(10, false, [
                    'var_page' => 'page',
                    'query' => request()->param(),
                ]);
        } else {
            // 查询分页数据 ,注意这里返回的是对象模型
            $user = OpenUser::where(1)
                ->field('id,open_id,account,realname,nickname,avatar,ip,company,address,create_time,type,score')
                ->order('create_time desc')
                ->paginate(10);
        }
        // 创建分页显示
        $this->assign('page', $user);
        // 模板渲染输出
        return $this->fetch();
    }

    /**
     * 软删除
     * 注意：参数绑定，Post 数据也是可以接受的
     * @param $id
     * @return \think\response\Json
     */
    public function softDelete($id)
    {
        if (request()->isAjax()) {
            $userInfo = OpenUser::destroy($id);
            if ($userInfo) {
                return json(['code' => 200, 'msg' => "成功"]);
            }
            return json(['code' => 500, 'msg' => '失败']);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }

    /**
     * 真实删除
     * 注意：参数绑定，Post 数据也是可以接受的
     * @param $id
     * @return \think\response\Json
     */
    public function hardDelete($id)
    {
        if (request()->isAjax()) {
            $userInfo = OpenUser::destroy($id, true);
            if ($userInfo) {
                return json(['code' => 200, 'msg' => "成功"]);
            }
            return json(['code' => 500, 'msg' => '失败']);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }

    /**
     * 查询回收站数据
     * 注意：参数绑定，Post 数据也是可以接受的
     * @return \think\response\Json
     */
    public function recycleData()
    {
        $user = OpenUser::onlyTrashed()
            ->field('id,open_id,account,realname,nickname,avatar,ip,company,address,create_time,type,score')
            ->order('create_time desc')
            ->paginate(10);
        $this->assign('empty', '<h2><span class="empty">回收站暂时没有数据</span></h2>');
        $this->assign('page', $user);
        return $this->fetch();
    }

    /**
     * 回收站恢复[恢复被软删除的记录]
     * 注意：参数绑定，Post 数据也是可以接受的
     * @return \think\response\Json
     */
    public function recycleRestore($id)
    {
        if (request()->isAjax()) {
            $user = new  OpenUser();
            $res = $user->recycleRestore($id);
            if ($res) {
                return json(['code' => 200, 'msg' => "成功"]);
            }
            return json(['code' => 500, 'msg' => '失败']);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }

    /**
     * 车辆客户信息表
     */
    public function customerList()
    {
        $lists = Db::name('car_customer')->select();
        $this->assign('lists', $lists);
        return $this->fetch();
    }

    /**
     * 根据OpenId 获取推流信息
     * @param $serviceProvider
     * @param $authKeyStatus
     * @return mixed
     * @static
     */
    protected static function apiCreateAddress($serviceProvider = 2, $authKeyStatus = 0)
    {
        $appId = 'rawdsb9ldp855h7r';
        $domainName = 'lives.tinywan.com';
        $appName = 'live';
        $appSecret = 'pvxyij6wdalr694u7dq3zqlvhlf55ytldoa49ij2';
        if ($serviceProvider == 1) {
            $domainName = 'live.tinywan.com';
        }
        $str = "AppId" . $appId . "AppName" . $appName . "AuthKeyStatus" . $authKeyStatus . "DomainName" . $domainName . "ServiceProvider" . $serviceProvider . $appSecret;
        $sign = strtoupper(sha1($str));
        $url = "https://www.tinywan.com/api/stream/createPushAddress?AppId=" . $appId . "&AppName=" . $appName . "&AuthKeyStatus=" . $authKeyStatus . "&DomainName=" . $domainName . "&ServiceProvider=" . $serviceProvider . "&Sign=" . $sign;
        $ch = curl_init() or die (curl_error());
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 360);
        $response = curl_exec($ch);
        curl_close($ch);
        return json_decode($response, true);
    }

    /**
     * 添加客户信息
     */
    public function addCustomer()
    {
        if (request()->isPost()) {
            $res = input('post.');
            // 【3】生成推流地址
            $stream = self::apiCreateAddress();
            if ($stream == false) {
                return $this->error("获取推流信息错误");
            }
            $user = CarCustomer::create([
                'c_name' => $res['c_name'],
                'c_tel' => $res['c_tel'],
                'num_plate' => $res['num_plate'],
                'unit' => $res['unit'],
                'stream_id' => $stream['data']['streamId'],
                'stream_name' => $stream['data']['streamName'],
            ]);
            if ($user) {
                return $this->success("成功");
            } else {
                return $this->error("失败");
            }
        }
    }
}