<?php

/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2017/12/23 16:13
 * |  Mail: Overcome.wan@Gmail.com
 * |  Function: QQ第三方登录认证
 * '------------------------------------------------------------------------------------------------------------------*/

namespace oauth;

class Qq
{
    const REQUEST_URL = 'https://graph.qq.com/oauth2.0/authorize';

    const TOKEN_URL = 'https://graph.qq.com/oauth2.0/token';

    private static $data;

    private $app_id = "";

    private $app_key = "";

    private $callBackUrl = "";

    private $code = "";

    private $accessToken = "";

    private $openid = "";

    /**
     * Qq constructor.
     * @param $appId
     * @param $appKey
     * @param $callBackUrl
     */
    public function __construct($appId, $appKey, $callBackUrl)
    {
        $this->app_id = $appId;
        $this->app_key = $appKey;
        $this->callBackUrl = $callBackUrl;
        //检查用户数据
        if (empty(session('QC_userData'))) {
            self::$data = [];
        } else {
            self::$data = session('QC_userData');
        }
    }

    /**
     * 获取Authorization Code
     */
    public function getAuthCode()
    {
        $param['response_type'] = "code";
        $param['client_id'] = $this->app_id;
        $param['redirect_uri'] = $this->callBackUrl;
        //生成唯一随机串防CSRF攻击
        $state = md5(uniqid(rand(), TRUE));
        session("state", $state);
        $param['state'] = $state;
        $param['scope'] = "get_user_info";
        $param = http_build_query($param, '', '&');
        $url = self::REQUEST_URL . "?" . $param;
        header("Location:" . $url);
    }

    //通过 Authorization Code 获取Access Token
    public function getAccessToken()
    {
        if (!empty($this->accessToken)) {
            return $this->accessToken;
        } else {
            $param['grant_type'] = "authorization_code";
            $param['client_id'] = $this->app_id;
            $param['client_secret'] = $this->app_key;
            $param['code'] = $_GET['code'];
            $param['redirect_uri'] = $this->callBackUrl;
            $param = http_build_query($param, '', '&');
            $url = self::TOKEN_URL . "?" . $param;
            return $this->getUrl($url);
        }
    }

    //获取openid
    public function getOpenId()
    {
        if ($this->openid) {
            return $this->openid;
        } else {
            $rzt = $this->getAccessToken();
            parse_str($rzt, $data);
            $this->accessToken = $data['access_token'];
            $url = "https://graph.qq.com/oauth2.0/me";
            $param['access_token'] = $this->accessToken;
            $param = http_build_query($param, '', '&');
            $url = $url . "?" . $param;
            $response = $this->getUrl($url);
            //--------检测错误是否发生
            if (strpos($response, "callback") !== false) {
                $lpos = strpos($response, "(");
                $rpos = strrpos($response, ")");
                $response = substr($response, $lpos + 1, $rpos - $lpos - 1);
            }
            $user = json_decode($response);

            if (isset($user->error)) {
                exit("错误代码：100007");
            }
            return $user->openid;
        }
    }

    public function setCallBackInfo()
    {
        if (input("state", "") != session("state")) {
            exit("错误代码：300001");
        }
        $this->code = input("code", "");
        $this->openid = $this->getOpenId();
        if (empty($this->openid)) {
            return false;
        }
    }

    /**
     * 获取用户信息
     * @return mixed
     */
    public function getUsrInfo()
    {
        $url = "https://graph.qq.com/user/get_user_info";
        $param['access_token'] = $this->accessToken;
        $param['oauth_consumer_key'] = $this->app_id;
        $param['openid'] = $this->openid;
        $param = http_build_query($param, '', '&');
        $url = $url . "?" . $param;
        $rzt = $this->getUrl($url);
        return $rzt;
    }

    //CURL GET
    private function getUrl($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        if (!empty($options)) {
            curl_setopt_array($ch, $options);
        }
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }

    //CURL POST
    private function postUrl($url, $post_data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
        ob_start();
        curl_exec($ch);
        $result = ob_get_contents();
        ob_end_clean();
        return $result;
    }
}