<?php

namespace app\backend\controller;

use houdunwang\arr\Arr;
use think\Controller;

class Article extends Controller
{
    protected  $db;

    public function _initialize()
    {
        $this->db = new \app\common\model\Article();
    }

    /**
     * 一个标签可以使多个标签
     * @return mixed
     */
    public function index()
    {
        $articles = $this->db->getAll();
        $categorys = Arr::tree(db('category')->order('id desc')->select(), 'name', $fieldPri = 'id', $fieldPid = 'pid');
        $this->assign('articles',$articles);
        $this->assign('categorys',$categorys);
        $this->assign('tags',db('tag')->select());
        return $this->fetch();
    }

    public function store()
    {
        if (request()->isPost()) {
            $res = $this->db->store(input('post.'));
            if ($res["valid"]) {
                $this->success($res["msg"], "backend/article/index");
                exit;
            } else {
                $this->error($res["msg"]);
                exit;
            }
        }
    }
}
