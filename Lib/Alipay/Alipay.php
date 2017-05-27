<?php

namespace Moon\Lib\Alipay;

class Alipay{

	public static $appid = '2016101002078763';
//	public static $appid = '2016101202117496';

	public static $pid = '2088521051605201'; //合作者身份ID

	public static $seller_id = '2088521051605201'; //卖家支付宝账号

	public static $gateway_url = "https://openapi.alipay.com/gateway.do";

	public static $rsyc_gateway_url = "http://113.247.222.61:7000/notify_url.aspx";

	public static $private_key_path = '../Lib/Alipay/rsa_private_key.pem';

	public static $public_key_path = '../Lib/Alipay/rsa_public_key.pem';

	public static $alipay_public_key_path = '../Lib/Alipay/alipay_public_key.pem';

	/**
     * 获取返回时的签名验证结果
     * @param array $para_temp 通知返回来的参数数组
     * @return string 签名验证结果
     */
	public static function genSign($para_temp) {
		//除去待签名参数数组中的空值和签名参数
		$para_filter = self::paraFilter($para_temp);

		//对待签名参数数组排序
		$para_sort = self::argSort($para_filter);

		//把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
		$prestr = self::createLinkstring($para_sort);

		$sign = self::rsaSign( $prestr, self::$private_key_path);

		return $sign;
	}

	/**
	 * 除去数组中的空值和签名参数
	 * @param array $para 签名参数组
	 * @return array 去掉空值与签名参数后的新签名参数组
	 */
	public static function paraFilter($para) {
		$para_filter = array();
		while (list ($key, $val) = each ($para)) {
			if($key == "sign" || $val == "")continue;
			else	$para_filter[$key] = $para[$key];
		}
		return $para_filter;
	}

	/**
	 * 对数组排序
	 * @param array $para 排序前的数组
	 * @return array 排序后的数组
	 */
	public static function argSort($para) {
		ksort($para);
		reset($para);
		return $para;
	}

	/**
	 * 把数组所有元素，按照“参数=参数值”的模式用“&”字符拼接成字符串
	 * @param array $para 需要拼接的数组
	 * @return string 拼接完成以后的字符串
	 */
	public static function createLinkstring($para) {
		$arg  = "";
		while (list ($key, $val) = each ($para)) {
			$arg.=$key."=".$val."&";
		}
		//去掉最后一个&字符
		$arg = substr($arg,0,count($arg)-2);

		//如果存在转义字符，那么去掉转义
		if(get_magic_quotes_gpc()){$arg = stripslashes($arg);}

		return $arg;
	}


	/**
	 * RSA签名
	 * @param array $data 待签名数据
	 * @param string $private_key_path 商户私钥文件路径
	 * @return string 签名结果
	 */
	public static function rsaSign( $data, $private_key_path ) {
		$priKey = file_get_contents( $private_key_path );
	    $res = openssl_get_privatekey($priKey);
	    openssl_sign($data, $sign, $res);
	    openssl_free_key($res);
		//base64编码
	    $sign = base64_encode($sign);
	    return $sign;
	}

	/**
	 * RSA验签
	 * @param string $data 待签名数据
	 * @param string $ali_public_key_path 支付宝的公钥文件路径
	 * @param string $sign 要校对的的签名结果
	 * @return bool 验证结果
	 */
	public static function rsaVerify($data, $ali_public_key_path, $sign)  {
		$pubKey = file_get_contents( $ali_public_key_path );
	    $res = openssl_get_publickey($pubKey);
	    $result = (bool)openssl_verify( $data, base64_decode($sign), $res);
	    openssl_free_key($res);
	    return $result;
	}

}