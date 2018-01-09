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
use app\common\model\OpenUser;

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
}