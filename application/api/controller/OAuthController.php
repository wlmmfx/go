<?php
/** .-------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |-------------------------------------------------------------------
 * |  Author: Tinywan(SHaoBo Wan)
 * |  Date: 2017/1/20
 * |  Time: 16:25
 * |  Mail: Overcome.wan@Gmail.com
 * |  描述：全部使用模型对象去处理数据
 * '-------------------------------------------------------------------*/

namespace app\api\controller;

use app\common\controller\BaseApiController;
use app\common\model\OpenUser;
use League\OAuth2\Client\Provider\Github;
use oauth\Qq;
use think\Db;

class OAuthController extends BaseApiController
{
    const GITHUB_OAUTH_URL = 'https://github.com/login/oauth/authorize';
    const GITHUB_OAUTH_ACCESS_TOKEN_URL = 'https://github.com/login/oauth/access_token';
    const GITHUB_USER_ACCESS_TOKEN_URL = 'https://api.github.com/user?access_token=';

    // GitHub
    public function gitHub()
    {
        $url = self::GITHUB_OAUTH_URL . "?client_id=" . config('oauth.github')['client_id'] . "&redirect_uri=" . config('oauth.github')['redirect_uri'];
        header('location:' . $url);
    }

    // GitHub 回调
    public function gitHubRedirectUri()
    {
        //第一步:取全局access_token
        $postRes = curl_request(self::GITHUB_OAUTH_ACCESS_TOKEN_URL, [
            "client_id" => config('oauth.github')['client_id'],
            "client_secret" => config('oauth.github')['client_secret'],
            "code" => input('get.code'),
        ]);
        //第三步:根据全局access_token和openid查询用户信息
        $jsonRes = json_decode($postRes, true);
        if (empty($jsonRes['access_token'])) {
            return $this->error('接口获取数据异常，请重新获取');
        }
        $userInfo = curl_request(self::GITHUB_USER_ACCESS_TOKEN_URL . $jsonRes["access_token"]);
        $userJsonRes = json_decode($userInfo, true);
        //第五步，检查用户是否已经注册过
        $condition['open_id'] = $userJsonRes['id'];
        $checkUserInfo = OpenUser::where($condition)->find();
        if ($checkUserInfo) {
            // 记录session信息
            session('open_user_id', $checkUserInfo['id']);
            session('open_user_username', $checkUserInfo['account']);
            return $this->redirect("/");
        } else {
            // 第六步，添加用户信息到数据库,使用模型静态方法创建数据
            $user = OpenUser::create([
                'account' => $userJsonRes['login'],
                'open_id' => $userJsonRes['id'],
                'password' => md5('123456'),
                'realname' => $userJsonRes['name'],
                'nickname' => $userJsonRes['login'],
                'avatar' => $userJsonRes['avatar_url'],
                'email' => $userJsonRes['email'],
                'company' => $userJsonRes['company'],
                'address' => $userJsonRes['location'],
                'site' => $userJsonRes['blog'],
                'type' => 'GitHub',
                'blog' => $userJsonRes['blog'],
                'github' => $userJsonRes['html_url'],
                'app_id' => get_rand_string(),
                'app_secret' => get_rand_string(40),
            ]);
            if ($user) {
                // 记录session信息
                session('open_user_id', $user->id);
                session('open_user_username', $userJsonRes['login']);
                return $this->redirect("/");
            } else {
                return $this->redirect("/");
            }
        }
    }

    // QQ
    public function qq()
    {
        $qq = new Qq();
        return $qq->getAuthCode();
    }

    // QQ 回调
    public function qqRedirectUri()
    {
        $qqInstance = new Qq();
        $qqInstance->setCallBackInfo();
        $openId = $qqInstance->getOpenId();
        $userInfo = $qqInstance->getUsrInfo();
        $userJsonRes = json_decode($userInfo, true);
        $condition['open_id'] = $openId;
        $checkUserInfo = OpenUser::where($condition)->find();
        if ($checkUserInfo) {
            session('open_user_id', $checkUserInfo['id']);
            session('open_user_username', $checkUserInfo['account']);
            return $this->redirect("/");
        } else {
            $user = OpenUser::create([
                'account' => $userJsonRes['nickname'],
                'open_id' => $openId,
                'password' => md5('123456'),
                'realname' => $userJsonRes['nickname'],
                'nickname' => $userJsonRes['nickname'],
                'avatar' => $userJsonRes['figureurl_2'],
                'company' => $userJsonRes['province'],
                'type' => 'QQ',
                'app_id' => get_rand_string(),
                'app_secret' => get_rand_string(40),
            ]);
            if ($user) {
                session('open_user_id', $user->id);
                session('open_user_username', $userJsonRes['nickname']);
                return $this->redirect("/");
            } else {
                return $this->redirect("/");
            }
        }
    }

    // 微博登录
    public function weiBo()
    {
        $obj = new \SaeTOAuthV2(config('oauth.weibo')['app_key'], config('oauth.weibo')['app_secret']);
        $codeUrl = $obj->getAuthorizeURL(config('oauth.weibo')['call_back_url']);
        header('location:' . $codeUrl);
    }

    // 微博回调
    public function weiBoRedirectUri()
    {
        $code = input('get.code');
        $obj = new \SaeTOAuthV2(config('oauth.weibo')['app_key'], config('oauth.weibo')['app_secret']);
        $keys = [
            'code' => $code,
            'redirect_uri' => config('oauth.weibo')['call_back_url'],
        ];
        $token = $obj->getAccessToken('code', $keys);
        session('WEIBO_ACCESS_TOKEN', $token);
        setcookie('weibojs_' . $obj->client_id, http_build_query($token));
        $client = new \SaeTClientV2(config('oauth.weibo')['app_key'], config('oauth.weibo')['app_secret'], $token['access_token']);
        $getUid = $client->get_uid();
        // 微博sdk方法获取用户的信息
        $userInfo = $client->show_user_by_id($getUid['uid']);
        // 入库了
        $condition['open_id'] = $userInfo['id'];
        $checkUserInfo = OpenUser::where($condition)->find();
        if ($checkUserInfo) {
            session('open_user_id', $checkUserInfo['id']);
            session('open_user_username', $checkUserInfo['account']);
            return $this->redirect("/");
        } else {
            $user = OpenUser::create([
                'account' => $userInfo['name'],
                'open_id' => $userInfo['id'],
                'password' => md5('123456'),
                'realname' => $userInfo['screen_name'],
                'nickname' => $userInfo['screen_name'],
                'avatar' => $userInfo['avatar_large'],
                'company' => $userInfo['province'],
                'address' => $userInfo['location'],
                'site' => $userInfo['profile_url'],
                'type' => '微博',
                'app_id' => get_rand_string(),
                'app_secret' => get_rand_string(40),
            ]);
            if ($user) {
                session('open_user_id', $user->id);
                session('open_user_username', $userInfo['name']);
                return $this->redirect("/");
            } else {
                return $this->redirect("/");
            }
        }
    }

    /**
     * Wechat
     * https://open.weixin.qq.com/connect/qrconnect?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
     */
    public function wechat()
    {
        //$scope = "snsapi_base";
        $scope = "snsapi_userinfo";
        $appid = 'wx94c43716d8a91f3f';
        /*基本授权 方法跳转地址*/
        $redirect_uri = urlencode('http://www.tinywan.com/frontend/open_auth/wechatRedirectUri');
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $appid . "&redirect_uri=" . $redirect_uri . "&response_type=code&scope=${scope}&state=1234#wechat_redirect";
        header('location:' . $url);
    }

    /**
     * WeChat 回调
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
