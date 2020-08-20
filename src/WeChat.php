<?php
// +----------------------------------------------------------------------
// | WeChat
// +----------------------------------------------------------------------
// | User: Lengnuan <25314666@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020年08月20日
// +----------------------------------------------------------------------

namespace lengnuan\wechat;

use Yii;
use yii\base\Component;
use yii\httpclient\Exception;
use yii\base\InvalidConfigException;
use lengnuan\wechat\extension\Helpers;

class WeChat extends Component
{
    // 缓存
    protected $cache;

    // 公众号开发信息
    protected $config;

    // Access Token
    public $accessToken;

    // ticket
    public $ticket;

    public function init()
    {
        $this->cache = Yii::$app->cache;
        $this->config = Yii::$app->params['weChat']['config'];
        $this->accessToken = $this->getAccessToken();
    }

    /**
     * Access Token
     * @return mixed
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getAccessToken()
    {
        $cacheKey = md5("{$this->config['appid']}@access_token");
        $accessToken = $this->cache->get($cacheKey);
        if (!$accessToken) {
            $results = Helpers::httpClient(Helpers::WECHAT_ACCESS_TOKEN_URL, 'GET', [
                'grant_type' => 'client_credential', 'appid' => $this->config['appid'], 'secret' => $this->config['secret']
            ]);
            $this->cache->set($cacheKey, $results['access_token'], $results['expires_in'] - 600);
            $accessToken = $results['access_token'];
        }
        return $accessToken;
    }

    /**
     * 获取微信callback IP地址
     * @return mixed|null
     * @throws InvalidConfigException
     * @throws Exception
     */
    public function getCallbackip()
    {
        return Helpers::httpClient(Helpers::WECHAT_CALLBACK_IP_URL, 'GET', ['access_token' => $this->accessToken]);
    }

    /**
     * 创建临时整型 ticket
     * @param int $scene_str
     * @param int $expire
     * @return $this
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getIntTicket($scene_str = 0, $expire = 1800)
    {
        $api = Helpers::WECHAT_BASE_URL . Helpers::WECHAT_CREATE_QRCODE_URL . '?access_token=' . $this->accessToken;
        $params = ['expire_seconds' => $expire, 'action_name' => 'QR_SCENE', 'action_info' => ['scene' => ['scene_id' => $scene_str]]];
        $results = Helpers::httpClient( $api, 'POST', $params, [], 'json');
        $this->ticket = $results['ticket'];
        return $this;
    }

    /**
     * 创建临时字符串 ticket
     * @param null $scene_str
     * @param int $expire
     * @return $this
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getStrTicket($scene_str = null, $expire = 1800)
    {
        $api = Helpers::WECHAT_BASE_URL . Helpers::WECHAT_CREATE_QRCODE_URL . '?access_token=' . $this->accessToken;
        $params = ['expire_seconds' => $expire, 'action_name' => 'QR_STR_SCENE', 'action_info' => ['scene' => ['scene_str' => $scene_str]]];
        $results = Helpers::httpClient( $api, 'POST', $params, [], 'json');
        $this->ticket = $results['ticket'];
        return $this;
    }

    /**
     * 创建永久整型 ticket
     * @param int $scene_str
     * @return $this
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getLimitIntTicket($scene_str = 0)
    {
        $api = Helpers::WECHAT_BASE_URL . Helpers::WECHAT_CREATE_QRCODE_URL . '?access_token=' . $this->accessToken;
        $params = ['action_name' => 'QR_LIMIT_SCENE', 'action_info' => ['scene' => ['scene_id' => $scene_str]]];
        $results = Helpers::httpClient( $api, 'POST', $params, [], 'json');
        $this->ticket = $results['ticket'];
        return $this;
    }

    /**
     *  创建永久字符串 ticket
     * @param null $scene_str
     * @return $this
     * @throws Exception
     * @throws InvalidConfigException
     */
    public function getLimitStrTicket($scene_str = null)
    {
        $api = Helpers::WECHAT_BASE_URL . Helpers::WECHAT_CREATE_QRCODE_URL . '?access_token=' . $this->accessToken;
        $params = ['action_name' => 'QR_LIMIT_STR_SCENE', 'action_info' => ['scene' => ['scene_str' => $scene_str]]];
        $results = Helpers::httpClient( $api, 'POST', $params, [], 'json');
        $this->ticket = $results['ticket'];
        return $this;
    }
    /**
     * 获取二维码图片
     * @return string
     */
    public function getQrcodeUrl()
    {
        return Helpers::WECHAT_SHOW_QRCODE_URL . '?ticket=' . urlencode($this->ticket);
    }
}