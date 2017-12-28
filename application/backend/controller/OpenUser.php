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


use app\common\controller\BaseBackend;
use think\Db;

class OpenUser extends BaseBackend
{
    private $_db;

    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->_db = new \app\common\model\OpenUser();
    }

    /**
     *
     * @return mixed
     */
    public function index()
    {
        if ($this->request->isPost()) {
            $keyword = input('post.keyword');
            $condition = [
                'account|address|nickname|type' => ['like', '%' . $keyword . '%'],
            ];
            $user = Db::table('resty_open_user')->where($condition)->paginate(10, false, [
                'var_page' => 'page',
                'query' => request()->param(),
            ]);
        } else {
            $user = Db::table('resty_open_user')->order('create_time desc')->paginate(10);
        }
        return $this->fetch('', [
            'users' => $user
        ]);
    }
}