<?php
// +----------------------------------------------------------------------
// | 对公众平台发送给公众账号的消息加解密
// +----------------------------------------------------------------------
// | User: Lengnuan <25314666@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020年08月20日
// +----------------------------------------------------------------------

namespace lengnuan\wechat\encryption;

class WXBizMsgCrypt
{
	private $token;
	private $encodingAesKey;
	private $appId;

	/**
	 * 构造函数
	 * @param $token string 公众平台上，开发者设置的token
	 * @param $encodingAesKey string 公众平台上，开发者设置的EncodingAESKey
	 * @param $appId string 公众平台的appId
	 */
	public function __construct($token, $encodingAesKey, $appId)
	{
		$this->token = $token;
		$this->encodingAesKey = $encodingAesKey;
		$this->appId = $appId;
	}

	/**
	 * 将公众平台回复用户的消息加密打包.
	 * @param string $replyMsg  公众平台待回复用户的消息，xml格式的字符串
	 * @param string $timeStamp  时间戳，可以自己生成，也可以用URL参数的timestamp
	 * @param string $nonce  随机串，可以自己生成，也可以用URL参数的nonce
	 * @param string &$encryptMsg  加密后的可以直接回复用户的密文，包括msg_signature, timestamp, nonce, encrypt的xml格式的字符串,当return返回0时有效
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function encryptMsg($replyMsg, $timeStamp, $nonce, &$encryptMsg)
	{
		$pc = new Prpcrypt($this->encodingAesKey);
		//加密
		$array = $pc->encrypt($replyMsg, $this->appId);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}
		if ($timeStamp == null) {
			$timeStamp = time();
		}
		$encrypt = $array[1];
		//生成安全签名
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->token, $timeStamp, $nonce, $encrypt);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}
		$signature = $array[1];
		//生成发送的xml
		$xmlparse = new XMLParse;
		$encryptMsg = $xmlparse->generate($encrypt, $signature, $timeStamp, $nonce);
		return ErrorCode::$OK;
	}

	/**
	 * 检验消息的真实性，并且获取解密后的明文.
	 * @param string $msgSignature  签名串，对应URL参数的msg_signature
	 * @param string $timestamp  时间戳 对应URL参数的timestamp
	 * @param string $nonce  随机串，对应URL参数的nonce
	 * @param string $postData  密文，对应POST请求的数据
	 * @param string &$msg 解密后的原文，当return返回0时有效
	 * @return int 成功0，失败返回对应的错误码
	 */
	public function decryptMsg($msgSignature, $timestamp = null, $nonce, $postData, &$msg)
	{
		if (strlen($this->encodingAesKey) !== 43) {
			return ErrorCode::$IllegalAesKey;
		}
		$pc = new Prpcrypt($this->encodingAesKey);
		//提取密文
		$xmlparse = new XMLParse;
		$array = $xmlparse->extract($postData);
		$ret = $array[0];
		if ($ret !== 0) {
			return $ret;
		}
		if ($timestamp == null) {
			$timestamp = time();
		}
		$encrypt = $array[1];
		$touser_name = $array[2];
		//验证安全签名
		$sha1 = new SHA1;
		$array = $sha1->getSHA1($this->token, $timestamp, $nonce, $encrypt);
		$ret = $array[0];
		if ($ret != 0) {
			return $ret;
		}
		$signature = $array[1];
		if ($signature != $msgSignature) {
			return ErrorCode::$ValidateSignatureError;
		}
		$result = $pc->decrypt($encrypt, $this->appId);
		if ($result[0] != 0) {
			return $result[0];
		}
		$msg = $result[1];
		return ErrorCode::$OK;
	}
}
?>