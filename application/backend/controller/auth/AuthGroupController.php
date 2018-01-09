<?php

/**
 * Created by PhpStorm.
 * User: Tinywan
 * Date: 2017/8/31
 * Time: 16:13
 * Mail: Overcome.wan@Gmail.com
 */

namespace app\backend\controller\auth;

use app\common\controller\BaseBackendController;
use app\common\model\AuthGroup;
use app\common\model\AuthRule;
use houdunwang\arr\Arr;

class AuthGroupController extends BaseBackendController
{
    protected $db;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->db = new \app\common\model\AuthGroup();  //对象存储在一个db属性中
    }

    /**
     * 显示用户组列表
     * @return \think\Response
     */
    public function groupList()
    {
//        $groupInfo = AuthGroup::where('id', '>', 1)->cache('RESTY_AUTH_GROUP')->select();
        $groupInfo = AuthGroup::where('id', '>', 1)->select();
        return $this->fetch('', [
            'lists' => $groupInfo
        ]);
    }

    /**
     * 保存新建的资源
     */
    public function store()
    {
        if (request()->isPost()) {
            $res = input('post.');
            $user = AuthGroup::create([
                'title' => $res['title'],
                'rules' => $res['rules'],
            ]);
            if ($user) {
                $this->success("成功", "backend/auth.auth_group/groupList");
                exit;
            } else {
                $this->error("失败");
                exit;
            }
        }
    }

    /**
     * 编辑数据.
     * @param  int $id
     * @return \think\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * 删除指定资源
     */
    public function delete()
    {
        if (request()->isPost()) {
            $delGroup = AuthGroup::destroy(input("param.id"));
            if ($delGroup) {
                return json(['status' => 200, 'msg' => 'success']);
            } else {
                return json(['status' => 500, 'msg' => 'fail']);
            }
        }
        return json(['status' => 403, 'msg' => 'forbidden']);
    }

    /**
     * 用户组添加规则
     */
    public function addRules()
    {
        if (request()->isPost()) {
            $data = input('post.');
            if ($data["rules"]) {
                $data["rules"] = implode(',', $data["rules"]);
                $res = AuthGroup::where('id', $data["groupId"])->setField('rules', $data["rules"]);
                // 注意：当使用“===”判断时，由于判断了变量的类型，0 和 false就不相等了，问题解决
                if ($res !== false) {
                    add_operation_log('用户组添加规则成功');
                    $this->success('用户组添加规则成功', "backend/auth.auth_group/groupList");
                    exit;
                } else {
                    add_operation_log('用户组添加规则失败');
                    $this->success('用户组添加规则失败');
                    exit;
                }
            }
        }
        $id = input('param.id');
        $groups = AuthGroup::where('id', $id)->find();
        $allRules = Arr::tree(AuthRule::where('id', '>', 1)->select(), 'title', $fieldPri = 'id', $fieldPid = 'pid');
        $checkedRules = [];
        foreach ($allRules as $value) {
            if (in_array($value['id'], explode(',', $groups['rules']))) {
                $value['access'] = '1';
            } else {
                $value['access'] = '0';
            }
            $checkedRules[] = $value;
        }

        return $this->fetch('addRules', [
            'groupTitle' => $groups['title'],
            'groupId' => $id,
            'allRules' => $checkedRules
        ]);
    }

    /**
     * 用户组删除规则
     */
    public function delRules()
    {
        if (request()->isPost()) {
            halt($_POST);
        }
        $id = input('param.id');
        $groupTitle = db('auth_group')->where('id', $id)->find()['title'];
        $allRules = Arr::tree(db('auth_rule')->select(), 'title', $fieldPri = 'id', $fieldPid = 'pid');
        $this->assign('groupTitle', $groupTitle);
        $this->assign('allRules', $allRules);
        return $this->fetch();
    }
}