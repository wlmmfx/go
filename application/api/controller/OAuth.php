<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  Date: 2017/1/20
 * |  Time: 16:25
 * |  Mail: Overcome.wan@Gmail.com
 * |  Created by PhpStorm.
 * '-------------------------------------------------------------------*/

namespace app\api\controller;

use app\common\controller\BaseFrontend;
use app\common\model\OpenUser;
use League\OAuth2\Client\Provider\Github;
use oauth\Qq;
use think\Db;

class OAuth extends BaseFrontend
{
    const GITHUB_URL = 'https://github.com/login/oauth/authorize';

    protected $_open_db;

    public function _initialize()
    {
        parent::_initialize(); // TODO: Change the autogenerated stub
        $this->_open_db = new OpenUser();
    }

    public function indexConfig()
    {
        halt(config('oauth.github')['client_id']);
    }

    // Github 登录
    public function gitHub()
    {
        $url = self::GITHUB_URL . "?client_id=" . config('oauth.github')['client_id'] . "&redirect_uri=" . config('oauth.github')['redirect_uri'];
        header('location:' . $url);
    }

    // Github 回调地址
    public function gitHubRedirectUri()
    {
        $code = $this->request->get('code');
        //第一步:取全局access_token
        $postRes = curl_request(config('oauth.github')['access_token_uri'], [
            "client_id" => config('oauth.github')['client_id'],
            "client_secret" => config('oauth.github')['client_secret'],
            "code" => $code,
        ]);
        //第三步:根据全局access_token和openid查询用户信息
        $jsonRes = json_decode($postRes, true);
        $access_token = $jsonRes["access_token"];
        $userUrl = "https://api.github.com/user?access_token=" . $access_token;
        $userInfo = curl_request($userUrl);
        $userJsonRes = json_decode($userInfo, true);
        //第五步，检查用户是否已经注册过
        $checkUserInfo = $this->_open_db->userIsExistByOpenId($userJsonRes['id']);
        if ($checkUserInfo['valid']) {
            // 记录session信息
            session('open_user_id', $userJsonRes['id']);
            session('open_user_username', $userJsonRes['login']);
            return $this->redirect(session('OAUTH_REFFERER_URL'));
        } else {
            // 第六步，添加用户信息到数据库
            $insertData['account'] = $userJsonRes['login'];
            $insertData['open_id'] = $userJsonRes['id'];
            $insertData['password'] = md5('123456');
            $insertData['realname'] = $userJsonRes['name'];
            $insertData['nickname'] = $userJsonRes['login'];
            $insertData['avatar'] = $userJsonRes['avatar_url'];
            $insertData['email'] = $userJsonRes['email'];
            $insertData['company'] = $userJsonRes['company'];
            $insertData['address'] = $userJsonRes['location'];
            $insertData['site'] = $userJsonRes['blog'];
            $insertData['ip'] = request()->ip();
            $insertData['type'] = 'GitHub';
            $insertData['score'] = 10;
            $insertData['blog'] = $userJsonRes['blog'];
            $insertData['github'] = $userJsonRes['html_url'];
            $insertData['create_time'] = time();
            $userId = $this->_open_db->store($insertData);
            if ($userId) {
                // 记录session信息
                session('open_user_id', $userId);
                session('open_user_username', $userJsonRes['login']);
                return $this->redirect("/");
            } else {
                return $this->redirect("/");
            }
        }
    }

    // QQ 登录
    public function qq()
    {
        $qq = new Qq();
        return $qq->getAuthCode();
    }

    // QQ 回调地址
    public function qqRedirectUri()
    {
        $qqInstance = new Qq();
        $qqInstance->setCallBackInfo();
        $openId = $qqInstance->getOpenId();
        $userInfo = $qqInstance->getUsrInfo();
        $userJsonRes = json_decode($userInfo, true);
        $condition['open_id'] = $openId;
        $checkUserInfo = Db::table('resty_open_user')->where($condition)->find();
        if ($checkUserInfo) {
            session('open_user_id', $checkUserInfo['id']);
            session('open_user_username', $checkUserInfo['account']);
            return $this->redirect("/");
        } else {
            $insertData['account'] = $userJsonRes['nickname'];
            $insertData['open_id'] = $openId;
            $insertData['password'] = md5('123456');
            $insertData['realname'] = $userJsonRes['nickname'];
            $insertData['nickname'] = $userJsonRes['nickname'];
            $insertData['avatar'] = $userJsonRes['figureurl_2'];
            $insertData['company'] = $userJsonRes['province'];
            $insertData['address'] = $userJsonRes['city'];
            $insertData['type'] = 'QQ';
            $insertData['score'] = 10;
            $insertData['ip'] = request()->ip();
            $insertData['create_time'] = time();;
            $userId = $this->_open_db->store($insertData);
            if ($userId) {
                session('open_user_id', $userId);
                session('open_user_username', $userJsonRes['nickname']);
                return $this->redirect("/");
            } else {
                return $this->redirect("/");
            }
        }
    }

    /**
     * Wechat 登录 https://open.weixin.qq.com/connect/qrconnect?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
     */
    public function wechat()
    {
        //$scope = "snsapi_base";
        $scope = "snsapi_userinfo";
        $appid = 'wx94c43716d8a91f3f';
//        $appid = 'wx8cdfa8abbc7433fa';
        /*基本授权 方法跳转地址*/
        $redirect_uri = urlencode('http://www.tinywan.com/frontend/open_auth/wechatRedirectUri');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . $redirect_uri . "&response_type=code&scope=${scope}&state=1234#wechat_redirect";
        header('location:' . $url);
    }

    /**
     * Wechat回调地址
     */
    public function wechatRedirectUri()
    {
//        $appid = 'wx8cdfa8abbc7433fa';
        $appid = 'wx94c43716d8a91f3f';
        $appsecret = 'd4624c36b6795d1d99dcf0547af5443d';
//        $appsecret = 'bcbefa89681e5f2d9e62ca22e3f5e6e4';
        /*回调的时候自带的这个参数*/
        $code = $this->request->get('code');
        //第一步:取全局access_token
        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
        $token = getJson($url);
        //第二步:取得openid
        $oauth2Url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$appsecret&code=$code&grant_type=authorization_code";
        $oauth2 = getJson($oauth2Url);
        //第三步:根据全局access_token和openid查询用户信息
        $access_token = $token["access_token"];
        if (empty($oauth2['openid'])) return $this->success("授权失败", '/');
        $openid = $oauth2['openid'];
        $get_user_info_url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
        $userJsonRes = getJson($get_user_info_url);
        //打印用户信息
        //第五步，检查用户是否已经注册过
        $condition['open_id'] = $userJsonRes['openid'];
        $checkUserInfo = Db::table('resty_open_user')->where($condition)->find();
        if ($checkUserInfo) {
            // 记录session信息
            session('open_user_id', $checkUserInfo['id']);
            session('open_user_username', $checkUserInfo['account']);
            $this->success("登录成功", '/');
        } else {
            // 第六步，添加用户信息到数据库
            $insertData['account'] = $userJsonRes['nickname'];
            $insertData['open_id'] = $userJsonRes['openid'];
            $insertData['password'] = md5('123456');
            $insertData['realname'] = $userJsonRes['nickname'];
            $insertData['nickname'] = $userJsonRes['nickname'];
            $insertData['avatar'] = $userJsonRes['headimgurl'];
            $insertData['company'] = $userJsonRes['city'];
            $insertData['address'] = $userJsonRes['city'];
            $insertData['type'] = "QQ";
            $insertData['ip'] = get_client_ip();
            $insertData['create_time'] = date("Y-m-d H:i:s");;
            $userId = Db::table('resty_open_user')->insertGetId($insertData);
            if ($userId) {
                // 记录session信息
                session('open_user_id', $userId);
                session('open_user_username', $userJsonRes['nickname']);
                $this->success("授权登录成功", '/');
            } else {
                $this->error("授权登录失败", "frontend/member/signin");
            }
        }
    }

    /**
     * 退出登录
     */
    public function logOut()
    {
        session(null);
        return $this->redirect("/");
    }

    public function index()
    {
        $provider = new Github([
            'clientId' => '5e70ee2d904f655b0c31',
            'clientSecret' => 'd190c915d36b5feff7ceeb017ce35ab92e7cb38c',
            'redirectUri' => 'http://www.tinywan.xyz:8086/frontend/index/redirect_uri',
        ]);

        if (!isset($_GET["code"])) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $_SESSION['oauth2state'] = $provider->getState();
            header('Location: ' . $authUrl);
            exit;
            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET["state"] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        } else {
            // Try to get an access token (using the authorization code grant)
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $_GET["code"]
            ]);
            // Optional: Now you have a token you can look up a users profile data
            try {
                // We got an access token, let's now get the user's details
                $user = $provider->getResourceOwner($token);
                // Use these details to create a new profile
                printf('Hello %s!', $user->getNickname());
            } catch (\Exception $e) {
                // Failed to get user details
                exit('Oh dear...');
            }
            // Use this to interact with an API on the users behalf
            echo $token->getToken();
        }
    }

}