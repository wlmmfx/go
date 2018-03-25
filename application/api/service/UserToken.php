<?php
/**.-------------------------------------------------------------------------------------------------------------------
 * |  Github: https://github.com/Tinywan
 * |  Blog: http://www.cnblogs.com/Tinywan
 * |--------------------------------------------------------------------------------------------------------------------
 * |  Author: Tinywan(ShaoBo Wan)
 * |  DateTime: 2018/3/25 17:08
 * |  Mail: Overcome.wan@Gmail.com
 * '------------------------------------------------------------------------------------------------------------------*/

namespace app\api\service;


use app\common\library\exception\TokenException;
use app\common\library\exception\WeChatException;
use think\Exception;
use app\common\model\WxUser as WxUserModel;

class UserToken extends Token
{
    protected $code;
    protected $wpAppID;
    protected $wpAppSecret;
    protected $wpLoginUrl;

    public function __construct($code)
    {
        $this->code = $code;
        $this->wpAppID = config('wechat.WP_APP_ID');
        $this->wpAppSecret = config('wechat.WP_APP_SECRET');
        $this->wpLoginUrl = sprintf(config('wechat.WP_LOGIN_URL'), $this->wpAppID, $this->wpAppSecret, $this->code);
    }

    public function get()
    {
        $res = curl_request($this->wpLoginUrl);
        $wxResult = json_decode($res, true);
        if (empty($wxResult)) throw new Exception('获取session_key以及openId异常，微信内部错误');

        // 错误时返回JSON数据包(示例为Code无效)
        $loginFail = array_key_exists('errcode', $wxResult); // boole
        if ($loginFail) {
            return $this->processLoginFail($wxResult);
        } else {
            return $this->grantToken($wxResult);
        }
    }

    /**
     * 授权令牌
     * @param $wxResult
     * @return string
     */
    public function grantToken($wxResult)
    {
        // 拿到openId，查询数据库检查openId是否存在
        // 如果存在则不处理。不存在则在数据库新增一条记录
        // 生成令牌，准备缓存数据，写入缓存
        // 返回令牌到客户端
        // 缓存 key：令牌
        // 缓存 value：wxResult,udi,scope
        $openId = $wxResult['openid'];
        $userInfo = WxUserModel::getByOpenId($openId);
        if ($userInfo) {
            $uid = $userInfo->id;
        } else {
            $uid = $this->newUser($openId);
        }
        $cacheValue = $this->prepareCacheValue($wxResult, $uid);
        $key = $this->saveCache($cacheValue);
        return $key;
    }

    // $cacheValue
    private function saveCache($cacheValue)
    {
        $key = self::generateToken();
        $value = json_encode($cacheValue);
        // 令牌过期时间也就是缓存的过期时间
        $expire_in = config('setting.token_expire_in');
        $request = cache($key, $value, $expire_in);
        if (!$request) {
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 1005 // 通用错误
            ]);
        }
        return $key;
    }

    /**
     * 准备缓存数据
     * @param $wxResult
     * @param $uid
     * @return mixed
     */
    private function prepareCacheValue($wxResult, $uid)
    {
        $cacheValue = $wxResult;
        $cacheValue['uid'] = $uid;
        $cacheValue['scope'] = 16;

        return $cacheValue;
    }

    /**
     * 创建新记录
     * @param $openid
     * @return mixed
     */
    private function newUser($openid)
    {
        $res = WxUserModel::create([
            'openid' => $openid
        ]);
        return $res->id;
    }

    /**
     * 微信接口登录异常
     * @param $wxResult
     * @throws WeChatException
     */
    private function processLoginFail($wxResult)
    {
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}