<?php
// +----------------------------------------------------------------------
// | 二维码
// +----------------------------------------------------------------------
// | User: Lengnuan <25314666@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020年08月20日
// +----------------------------------------------------------------------

namespace lengnuan\wechat\model;

use lengnuan\wechat\extension\BaseConfig;
use yii\httpclient\Client;

class Qrcode extends BaseConfig
{
//    private $api;
//
//    public function init()
//    {
//        $accessToken = \Yii::$app->wechat->sdk->accessToken;
//        $this->api = self::WECHAT_BASE_URL . self::WECHAT_CREATE_QRCODE_URL . '?access_token=' . $accessToken;
//    }

//    // 临时二维码
//    public function sceneTicket($scene_str = null, $expire = 1800)
//    {
//        $params = [
//            'expire_seconds' => $expire, 'action_name' => 'QR_SCENE', 'action_info' => ['scene' => ['scene_id' => $scene_str]]
//        ];
//        $results = $this->httpClient( $this->api, 'POST', $params, [], Client::FORMAT_JSON);
//        var_dump($results);die;
//    }
}

