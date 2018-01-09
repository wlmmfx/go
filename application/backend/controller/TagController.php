<?php

namespace app\backend\controller;

use app\common\controller\BaseBackendController;

class TagController extends BaseBackendController
{
    protected $db;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Tag();
    }

    public function index()
    {
        $this->assign('tags', db('tag')->where('deleted',0)->select());
        $this->assign('categorys', db('category')->where('pid',0)->order('id desc')->select());
        return $this->fetch();
    }

    public function store()
    {
        if (request()->isPost()) {
            $res = $this->db->store(input('post.'));
            if ($res["valid"]) {
                $this->success($res["msg"], "backend/tag/index");
                exit;
            } else {
                $this->error($res["msg"]);
                exit;
            }
        }
    }

    /**
     * 删除操作
     * @return \think\response\Json
     */
    public function del()
    {
        if ($this->request->isAjax())
        {
            $id = input('post.id');
            $res = $this->db->del($id);
            if ($res['valid']) {
                return json(['code' => 200, 'msg' => $res["msg"]]);
            }
            return json(['code' => 500, 'msg' => $res["msg"]]);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);
    }
}
