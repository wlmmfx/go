<?php

/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/8/28 14:42
 * |  Mail: Overcome.wan@Gmail.com
 * '-------------------------------------------------------------------*/

namespace app\common\controller;

use app\common\library\Auth;
use app\common\model\AuthRule;
use think\Hook;
use think\Request;
use think\Url;

class BaseBackendController extends BaseController
{
    // 用户ID
    protected $uid = 0;

    protected $not_check_id = [1];//不检测权限的管理员id
    protected $not_check_url = ['admin/Index/index', 'admin/Sys/clear', 'admin/Index/lang'];//不检测权限的url

    // 权限实例
    protected $auth;

    // 钩子获取角色
    protected $role;

    //构造方法
    public function __construct(Request $request = null)
    {
        // 添加钩子
        $result = Hook::listen('controller_init', $this, $request, true);
        if ($result) {
            // 当前角色名
            $this->role = $result;
        }
        parent::__construct($request);
    }

    /**
     * 初始化操作
     * 初始化方法里面的return操作是无效的，也不能使用redirect助手函数进行重定向
     * 如果你是要进行重定向操作（例如权限检查后的跳转）请使用$this->redirect()方法
     */
    protected function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->auth = new Auth();
        $this->uid = session('admin.admin_id');
        // 登陆检查
        if (empty($this->uid)) {
             // 记录当前请求页面
             session('REFFERER_URL',$_SERVER['REQUEST_URI']);
             $this->error("您还没有登录，请登录后访问！",Url::build('backend/login/login'));
        }
        // 权限检查
        if($this->checkAccess() === false){
             $this->error("您没有访问权限！");
        }
//        $auth_ids_list = $this->auth->getAuthList($this->uid, 1);
//        halt($auth_ids_list);
    }

    /**
     * 检测权限
     * @return bool
     */
    private function checkAccess()
    {
        $controllerName = strtolower($this->request->controller());
        $actionName = strtolower($this->request->action());
        $checkAuth = $controllerName . '/' . $actionName;
        $checkResult = $this->auth->check($checkAuth, $this->uid);
        // public auth
        $openAuth = config('auth_config')['open_auth'];
        if (is_array($openAuth) AND in_array($checkAuth, $openAuth)) return true;
        if (!$checkResult) return false;
        return true;
    }

    /**
     * 获取权限菜单
     * @return array
     */
    public function getAdminMenus()
    {
        $uid = session('admin.admin_id');
        $where['status'] = 1;
        if (!in_array($uid, $this->not_check_id)) {
            if (empty($auth_ids_list)) {
                $auth = new Auth();
                $auth_ids_list = $auth->getAuthList($uid, 1);
            }
            if (empty($auth_ids_list)) return [];
            $where['id'] = array('in', $auth_ids_list);
        }
        $data = AuthRule::where($where)->order('sort')->select();
//        $tree = new \Tree();
//        $tree->init($data, ['child' => '_child', 'parentid' => 'pid']);
//        $menus = $tree->get_arraylist($data);
//        cache('menus_admin_' . $uid, $menus);
//        return $menus;
        return $data;
    }

}