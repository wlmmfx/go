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

class AuthRule extends BaseModel
{
    protected $pk = "id";
    protected $table = "resty_auth_rule"; //完整的表名

    /**
     * 需要改进
     * @return mixed
     */
    public function getAll()
    {
        return Arr::tree(db('auth_rule')->order('id desc')->select(), 'title', $fieldPri = 'id', $fieldPid = 'pid');
    }

    /**
     * 保存数据
     * @param $data
     * @return array
     */
    public function store($data)
    {
        $result = $this->validate(false)->save($data);
        if (false === $result) {
            // 验证失败 输出错误信息
            return ['valid' => 0, 'msg' => $this->getError()];
        }
        return ['valid' => 1, 'msg' => "添加成功"];
    }

    /**
     * 编辑Rule
     */
    public function getEditRule($id)
    {
        // 1 首先查找到 $id 的子集
        $subIds = $this->getSubRule(db('auth_rule')->select(), $id);
        // 2 将当前的分类id追加进去
        $subIds[] = $id;
        // 3 找到除了自己和自己子集的所有数据,构成树结构
        $res = db('auth_rule')->whereNotIn('id', $subIds)->select();
        return Arr::tree($res, 'title', $fieldPri = 'id', $fieldPid = 'pid');

    }

    public function getSubRule($allData, $id)
    {
        static $tmp = [];
        foreach ($allData as $k => $v) {
            if ($id == $v['pid']) {
                $tmp[] = $v['id'];
                $this->getSubRule($allData, $v['id']);
            }
        }
        return $tmp;
    }

    /**
     * @param $data
     * @return array
     */
    public function edit($data)
    {
        // id 已经存在的，只能更新
        $result = $this->validate(true)->save($data, [$this->pk => $data['id']]);
        if (false === $result) {
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
        $delArr = $this->getChildrenId($id);
        //2 删除一个数据
        $res = AuthRule::destroy($delArr);
        if (false === $res) return ['valid' => 0, 'msg' => "删除失败"];
        return ['valid' => 1, 'msg' => "删除成功"];
    }

    /**
 * 获取子权限
 * @param $id
 * @return mixed
 */
    public function getChildrenId($id)
    {
        // 1 首先查找到 $id 的子集
        $subIds = $this->_getChildrenId(db('auth_rule')->select(), $id);
        // 2 将当前的分类id追加进去
        $subIds[] = $id;
        return $subIds;
    }

    public function _getChildrenId($allData, $id)
    {
        static $tmp = [];
        foreach ($allData as $k => $v) {
            if ($id == $v['pid']) {
                $tmp[] = $v['id'];
                $this->_getChildrenId($allData, $v['id']);
            }
        }
        return $tmp;
    }

    /**
     * 获取父权限
     * @param $id
     * @return mixed
     */
    public function getParentId($authRuleId)
    {
        // 1 首先查找到 $id 的子集
        $subIds = $this->_getgetParentId(db('auth_rule')->select(), $authRuleId);
        // 2 将当前的分类id追加进去
        $subIds[] = $authRuleId;
        return $subIds;
    }

    public function _getgetParentId($allData, $authRuleId)
    {
        static $tmp = [];
        foreach ($allData as $k => $v) {
            if ($authRuleId == $v['pid']) {
                $tmp[] = $v['id'];
                $this->_getChildrenId($allData, $v['pid']);
            }
        }
        return $tmp;
    }

    /**
     * 获取所有父节点id(含自身)
     * @param int $id 节点id
     * @return array
     */
    public function get_admin_parents($id = 0)
    {
        $id = $id ?: $this->get_url_id('', 1);
        if (empty($id)) return [];
        $lists = self::order('level desc,sort')->column('pid', 'id');
        $ids = [];
        while (isset($lists[$id]) && $lists[$id] != 0) {
            $ids[] = $id;
            $id = $lists[$id];
        }
        if (isset($lists[$id]) && $lists[$id] == 0) $ids[] = $id;
        return array_reverse($ids);
    }

    /**
     * 获取当前节点及父节点下菜单(仅显示状态)
     * @param int $id 节点id
     * @return array|mixed
     */
    public function get_admin_parent_menus(&$id)
    {
        $id = $this->get_url_id('', 1);
        $pid = self::where('id', $id)->value('pid');
        //取$pid下子菜单
        $menus = self::where(array('status' => 1, 'pid' => $pid))->order('sort')->select();
        return $menus;
    }

    /**
     * 获取不需要验证的节点id
     * @return array
     */
    public static function get_notcheck_ids()
    {
        $ids = self::where('notcheck', 1)->column('id');
        return $ids;
    }

}
