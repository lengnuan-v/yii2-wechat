<?php
// +----------------------------------------------------------------------
// | Helpers
// +----------------------------------------------------------------------
// | User: Lengnuan <25314666@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020年08月20日
// +----------------------------------------------------------------------

namespace lengnuan\wechat\extension;

use yii\helpers\Json;
use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\base\InvalidConfigException;

class Helpers
{
    // 微信接口基本地址
    const WECHAT_BASE_URL = 'https://api.weixin.qq.com';

    // access token
    const WECHAT_ACCESS_TOKEN_URL = '/cgi-bin/token';

    // 微信callback IP
    const WECHAT_CALLBACK_IP_URL = '/cgi-bin/getcallbackip';

    // 二维码 ticket
    const WECHAT_CREATE_QRCODE_URL = '/cgi-bin/qrcode/create';

    // 二维码展示
    const WECHAT_SHOW_QRCODE_URL = 'https://mp.weixin.qq.com/cgi-bin/showqrcode';

    // 关注者基本信息
    const WECHAT_MEMBER_INFO_URL = '/cgi-bin/user/info';

    // 模板消息
    const WECHAT_TEMPLATE_MESSAGE_SEND_URL = '/cgi-bin/message/template/send';

    // 客服消息
    const WECHAT_CUSTOM_MESSAGE_SEND_URL = '/cgi-bin/message/custom/send';

    // 消息群发
    const WECHAT_MASS_SEND_URL = '/cgi-bin/message/mass/sendall';

    // 短连接
    const WECHAT_SHORT_URL_URL = '/cgi-bin/shorturl';

    /**
     * http Client
     * @param null $url
     * @param string $method
     * @param array $data
     * @param array $headers
     * @param string $format
     * @return mixed|null
     * @throws Exception
     * @throws InvalidConfigException
     */
    public static function httpClient($url = null, $method = 'GET', $data = [], $format = 'curl', $headers = [])
    {
        if (stripos($url, 'http://') === false && stripos($url, 'https://') === false) {
            $url = self::WECHAT_BASE_URL . $url;
        }
        static $client = null;
        $client === null && $client = new Client();
        $response = $client->createRequest()->setMethod($method)->setData($data)->setUrl($url)->setHeaders($headers)
            ->setFormat($format)->setOptions([CURLOPT_CONNECTTIMEOUT => 5, CURLOPT_TIMEOUT => 10])->send();
        $results = Json::decode($response->content);
        if (isset($results['errcode']) && $results['errcode'] !== 0) {
            throw new Exception("{$url}\n\n{$response->content}");
        }
        return $results;
    }
}