<?php
/**
 * Created by PhpStorm.
 * User: tinywan
 * Date: 2017/7/1
 * Time: 13:40
 */

namespace app\frontend\controller;


use FFMpeg\FFMpeg;
use Flc\Alidayu\App;
use Flc\Alidayu\Client;
use Flc\Alidayu\Requests\AlibabaAliqinFcSmsNumSend;

class Video
{
    public function index()
    {
        $ffmpeg = FFMpeg::create();
        var_dump($ffmpeg);
    }

    /**
     * 成功案例
     */
    public function alidayu(){
        // 配置信息
        $config = [
            'app_key'    => '23651008',
            'app_secret' => 'f613acf952e3a5a6ed974da76c2fb3ab',
            // 'sandbox'    => true,  // 是否为沙箱环境，默认false
        ];
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;

        $req->setRecNum('13669361192')
            ->setSmsParam([
                'number' => rand(100000, 999999)
            ])
            ->setSmsFreeSignName('弍萬测试')
            ->setSmsTemplateCode('SMS_50285067');

        $resp = $client->execute($req);
        var_dump($resp);
    }

    /**
     * 发送大于短信
     * @param $tel
     * @param $type
     * @param $data
     * @return mixed
     */
    protected function sendDaYuSms($tel,$type,$data) {
        $dayu_template = 'dayu_template_'.$type;
        $signname = C($dayu_template.".signname");
        $templatecode = C($dayu_template.".templatecode");
        // require LIB_PATH . 'ORG/Taobao-sdk-php/TopSdk.php';
        include_once LIB_PATH . 'ORG/Taobao-sdk-php/TopSdk.php';
        $c = new TopClient;
        $c->appkey = C('dayu_appkey');
        $c->secretKey = C('dayu_secretKey');
        $req = new AlibabaAliqinFcSmsNumSendRequest;
        $req->setSmsType("normal");
        $req->setSmsFreeSignName("{$signname}");
        switch($type) {
            case 'sold':
                $req->setSmsParam('{"name":"'. $data['name'] .'"}');
                break;
            case 'buysuccess':
                $req->setSmsParam('{"name":"'. $data['name'] .'","product":"'. $data['product'] .'"}');
                break;
            case 'newagent':
                $req->setSmsParam('{"name":"'. $data['name'] .'"}');
                break;
            default:
                $req->setSmsParam('{"code":"'. $data['code'] .'","product":"'. $data['product'] .'"}');
        }
        $req->setRecNum("{$tel}");
        $req->setSmsTemplateCode("{$templatecode}");
        $resp = $c->execute($req);
        return $resp;
    }

    protected function sendDaYuSms123($tel,$type,$data) {
        $dayu_template = 'template_'.$type; //template_register
        $signname = config("sms")['dayu'][$dayu_template]["sign_name"];
        $templatecode = config("sms")['dayu'][$dayu_template]["code"];
        $config = [
            'app_key'    => config("sms")['dayu']['app_key'],
            'app_secret' => config("sms")['dayu']['app_secret']
        ];
        $client = new Client(new App($config));
        $req    = new AlibabaAliqinFcSmsNumSend;
        $req->setRecNum("{$tel}");
        switch($type) {
            case 'register':
                $req->setSmsParam('{"name":"'. $data['name'] .'"}');
                break;
            case 'live':
                $req->setSmsParam('{"number":"'. $data['number'] .'","code":"'. $data['code'] .'"}');
                break;
            case 'identity':
                $req->setSmsParam('{"name":"'. $data['name'] .'"}');
                break;
            default:
                $req->setSmsParam('{"code":"'. $data['code'] .'","product":"'. $data['product'] .'"}');
        }
        $req->setSmsFreeSignName("{$signname}");
        $req->setSmsTemplateCode("{$templatecode}");
        $resp = $client->execute($req);
        return $resp;
    }

    public function alidayu123(){
         array (
            // 阿里大鱼短信配置
            'dayu_appkey'=>'xxxxxx',
            'dayu_secretKey'=>'xxxxxxxxxxxxxxxxxxxxx',
            'dayu_template_register' => array('signname'=>'注册验证','templatecode'=>'SMS_9655457'),
            'dayu_template_alteration' => array('signname'=>'变更验证','templatecode'=>'SMS_9655454'),
            'dayu_template_identity' => array('signname'=>'身份验证','templatecode'=>'SMS_9655461'),
            'dayu_template_sold'=> array('signname'=>'点多多','templatecode'=>'SMS_12800188'),
            'dayu_template_buysuccess'=> array('signname'=>'点多多','templatecode'=>'SMS_12775103'),
            'dayu_template_newagent'=> array('signname'=>'点多多','templatecode'=>'SMS_12815193'),
        );
    }

}