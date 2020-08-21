<?php
// +----------------------------------------------------------------------
// | ErrorCode error code 说明
// +----------------------------------------------------------------------
// | User: Lengnuan <25314666@qq.com>
// +----------------------------------------------------------------------
// | Date: 2020年08月20日
// +----------------------------------------------------------------------

namespace lengnuan\wechat\encryption;

class ErrorCode
{
    // 成功
	public static $OK = 0;

	// -40001: 签名验证错误
	public static $ValidateSignatureError = -40001;

	// -40002: xml解析失败
	public static $ParseXmlError = -40002;

	// -40003: sha加密生成签名失败
	public static $ComputeSignatureError = -40003;

	// -40004: encodingAesKey 非法
	public static $IllegalAesKey = -40004;

	// -40005: appid 校验错误
	public static $ValidateAppidError = -40005;

	// -40006: aes 加密失败
	public static $EncryptAESError = -40006;

	// -40007: aes 解密失败
	public static $DecryptAESError = -40007;

	// -40008: 解密后得到的buffer非法
	public static $IllegalBuffer = -40008;

	// -40009: base64加密失败
	public static $EncodeBase64Error = -40009;

	// -40010: base64解密失败
	public static $DecodeBase64Error = -40010;

	// -40011: 生成xml失败
	public static $GenReturnXmlError = -40011;
}

?>