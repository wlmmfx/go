<?php
/** .-----------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/11/28 15:17
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\backend\controller;


use app\common\controller\BaseBackend;
use think\Db;

class Wedding extends BaseBackend
{
    protected $_db;
    public function _initialize()
    {
        $this->_db = Db::connect([
            // 数据库类型
            'type'        => 'mysql',
            // 数据库连接DSN配置
            'dsn'         => '',
            // 服务器地址
            'hostname'    => 'rdshcnvt1o401vaib374.mysql.rds.aliyuncs.com',
            // 数据库名
            'database'    => 'tinywan_wedding',
            // 数据库用户名
            'username'    => 'tinywan_test',
            // 数据库密码
            'password'    => 'Tinywan_test',
            // 数据库连接端口
            'hostport'    => '3306',
            // 数据库连接参数
            'params'      => [],
            // 数据库编码默认采用utf8
            'charset'     => 'utf8',
        ]);
    }

    /**
     * 数据库连接测试
     */
    public function connection(){

        $content = $this->_db->table('wx_accounts')->select();
        halt($content);
    }

    /**
     * 显示祝福内容
     * @return mixed
     */
    public function wechatContent(){
        $content = $this->_db->table('feeds')
            ->alias('f')
            ->join('wx_accounts w','f.accountID = w.accountID')
            ->order('f.id desc')
            ->paginate(12);
        $this->assign('contents',$content);
        return $this->fetch();
    }

    /**
     * 删除操作
     * @return \think\response\Json
     */
    public function delContent(){

        if ($this->request->isAjax())
        {
            $id = input('post.id');
            $visible = input('post.visible');
            $res = $this->_db->table('feeds')->where('id', $id)->update(['visible' => $visible]);
            if ($res) {
                return json(['code' => 200, 'msg' => '删除成功']);
            }
            return json(['code' => 500, 'msg' => '删除失败']);
        }
        return json(['code' => 401, 'msg' => "Not Forbidden"]);

    }
}