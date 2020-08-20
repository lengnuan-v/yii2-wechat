lengnuan/yii2-wechat
====================
微信扩展

安装
------------

安装此扩展的首选方法是通过 [composer](http://getcomposer.org/download/).

运行

```
composer require --prefer-dist lengnuan/yii2-wechat "dev-master"
```

或添加

```
"lengnuan/yii2-wechat": "*"
```

到 composer.json 文件的 require 部分


-----

安装扩展程序后，main.php 文件中 components 中添加：

```php
'wechat' => [
    'class' => 'lengnuan\wechat\WeChat',
],
```

params.php 添加：
```php
// 微信基本配置
'weChat' => [
    'config' => [
        'appid'     => '',  // 开发者ID
        'secret'    => '',  // 开发者密码
        'token'     => '',  // Token
        'aeskey'    => '',  // 消息加解密密钥
    ]
],
```


用法 https://developers.weixin.qq.com/doc/
-----
```php
// 获取 access token
Yii::$app->wechat->accessToken;

// 获取微信callback IP地址
Yii::$app->wechat->callbackip;

// 创建临时整型 ticket
Yii::$app->wechat->getIntTicket(整型)->ticket;

// 创建临时字符串 ticket
Yii::$app->wechat->getStrTicket(字符串)->ticket;

// 创建永久整型 ticket
Yii::$app->wechat->getLimitIntTicket(整型)->ticket;

// 创建永久字符串 ticket
Yii::$app->wechat->getLimitStrTicket(字符串)->ticket;

// 获取二维码图片
Yii::$app->wechat->getIntTicket(整型)->qrcodeUrl;

// 关注者基本信息
Yii::$app->wechat->getUserInfo(openid);

// 模板消息
Yii::$app->wechat->sendTemplateMessage(数组);

// 客服消息
Yii::$app->wechat->sendCustomMessage(数组);

// 消息群发
Yii::$app->wechat->sendMassMessage(数组);

// 长链接转成短链接
Yii::$app->wechat->shortUrl(url);

// js api ticket
Yii::$app->wechat->jsapi->jsTicket;

// js api signature
Yii::$app->wechat->jsapi->jsapiSignature(url);

// s api code 获取 openid
Yii::$app->wechat->getJsapiCodeOpenId(code);
```