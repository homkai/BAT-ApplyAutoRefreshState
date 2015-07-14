<?php
/**
 * 读取配置
 * @param string $arg
 */
function C($arg){
	return isset($GLOBALS['__CONFIG__'][$arg]) ? $GLOBALS['__CONFIG__'][$arg] : null;
}

/**
 * 过滤XSS
 * @param string $val
 * @return string
 */
function clean_xss($val){
	$val = preg_replace('/([\x00-\x08]|[\x0b-\x0c]|[\x0e-\x19])/', '', $val);
	$val = str_replace(array('(',')','u003c','u003e'),array('(',')','',''), $val);
	return htmlspecialchars($val);
}

/**
 * CURL抓取页面内容
 * @param string $url
 * @param string $cookie
 * @param string html
 * @return string
 */
function curl_request($req){
	$ch = curl_init($req['url']);
	$options = array(
			CURLOPT_RETURNTRANSFER	=> true,
			CURLOPT_HEADER 			=> false,
			CURLOPT_COOKIE 			=> isset($req['cookie']) ? $req['cookie'] : '',
			CURLOPT_CONNECTTIMEOUT	=> 10,
			CURLOPT_TIMEOUT			=> 10,
	);	
	if(strpos($req['url'], 'https') !== false){
		$options += array(
				CURLOPT_SSL_VERIFYHOST	=> 0,
				CURLOPT_SSL_VERIFYPEER	=> false,
		);
	}
	if(isset($req['postData'])){
		$options += array(
				CURLOPT_NOBODY 		=> true,
				CURLOPT_POST		=> true,
				CURLOPT_POSTFIELDS 	=> $req['postData'],
		);
	}
	curl_setopt_array($ch, $options);
	$ret = curl_exec($ch);
	$ret = $ret ?: curl_error($ch);
	curl_close($ch);
	return $ret;
}

/**
 * 输出JSON
 * @param mixed:string|array $data
 * @param boolean $jsonp
 */
function show_json($data, $jsonp = false){
	header('Content-Type:application/json; charset=utf-8');
	if($jsonp) exit($_GET['callback'].'('.json_encode($data).')');
	exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}

/**
 * 404
 */
function show_404(){
	header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found");
	echo '<h1>404</h1><p>Page Not Found!</p>';
}

/**
 * 短信请求接口（http://www.ihuyi.com/）
 * @param $smsAccount 账号
 * @param $smsPassword 密码
 * @param $message 要发送的内容
 * @return JSON
 */
function send_sms($account, $password, $mobile, $message){
	$url = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
	$auth = "account={$account}&password={$password}";
	$message = "您的验证码是：666666 ###\n\n".$message."\n\n###      。请不要把验证码泄露给其他人。"; // 这个模板不要改，不然平台就不让你发了！
	$postData = $auth."&mobile={$mobile}&content=".rawurlencode($message);
	$data =  xml2Array(curl_request(compact('url', 'postData')));
	show_json($data, true);
}

/**
 * XML 转 Array
 * @param string $xml
 * @return array
 */
function xml2Array($xml){
	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
	if(preg_match_all($reg, $xml, $matches)){
		$count = count($matches[0]);
		$arr = [];
		for($i = 0; $i < $count; $i++){
			$sub= $matches[2][$i];
			$key = $matches[1][$i];
			if(preg_match($reg, $sub)){
				$arr[$key] = xml2Array($sub);
			}else{
				$arr[$key] = $sub;
			}
		}
	}
	return $arr;
}