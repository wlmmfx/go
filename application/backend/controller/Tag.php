<?php

namespace app\backend\controller;

use app\common\controller\BaseBackend;

class Tag extends BaseBackend
{
    protected $db;

    public function _initialize()
    {
        parent::_initialize();
        $this->db = new \app\common\model\Tag();
    }

    public function index()
    {
        $this->assign('tags', db('tag')->select());
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
}
