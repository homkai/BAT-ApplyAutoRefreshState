<?php
// 登陆后，将campus.alibaba.com域下的JSESSIONID、tmp两个Cookie值填入
$loginCookie = 'JSESSIONID=...; tmp0=...';
// 你的短信平台账号密码，每个手机号可以免费发10条，也就足够用了！申请链接：http://www.ihuyi.com/product.php#bottom_div
$smsAccount = 'cf_leasur';
$smsPassword = '123456';
$smsMoblie = ''; // 接受状态更新的手机号

/**
 * 路由入口 
 */
main();
function main(){
	if(!$_GET) return stateApi();
	if($_GET['sms']) return smsAliApply();
}

/**
 * 阿里应聘状态JSON API
 */
function stateApi(){
	$url = 'http://campus.alibaba.com/myJobApply.htm';
	$cookie = $loginCookie;
	$content = getPageContent($url, $cookie);
	$state = getApplyState($content);
	header('Content-Type:application/json; charset=utf-8');
	exit(json_encode(array('text'=>strip_tags($state), 'html'=>$state), JSON_UNESCAPED_UNICODE));
}
/**
 * CURL抓取页面内容
 * @param string $url
 * @param string $cookie
 * @param string html
 */ 
function getPageContent($url, $cookie = ''){
	$ch = curl_init($url);
	if($cookie) curl_setopt($ch, CURLOPT_COOKIE, $cookie);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$output = curl_exec($ch) ;
	curl_close($ch);
	return $output;
}
/**
 * 阿里应聘状态页面解析
 * @param string $content
 * @return string html
 */ 
function getApplyState($content){
	$pattern = '/[\s\S]*[^\.]class="state\-name"[^>]+>([\s\S]*?)<\/td>[\s\S]*/';
	$state = trim(preg_replace($pattern, '$1', $content));
	return $state;
}
/**
 * 短信请求接口（http://www.ihuyi.com/）
 * 这个短信平台提供的是POST请求，XML格式返回
 */
function smsAliApply(){
	$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
	$auth = "account={$smsAccount}&password={$smsPassword}";
	$message = $_GET['message']; // 要发送的短信内容
	$message = "您的验证码是：666666 ###\n\n".$message."\n\n###      。请不要把验证码泄露给其他人。"; // 这个模板不要改，不然平台就不让你发了！
	$post_data = $auth."&mobile={$smsMobile}&content=".rawurlencode($message);
 	$gets =  xml_to_array(post($post_data, $target));
	header('Content-Type:application/json; charset=utf-8');
	exit($_GET['callback'].'('.json_encode($gets).')'); // 支持JSONP跨域请求
}
/**
 * 短信平台POST请求
 * @param string $curlPost
 * @param string $url
 * @return string xml
 */
function post($curlPost,$url){
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_NOBODY, true);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $curlPost);
	$return_str = curl_exec($curl);
	curl_close($curl);
	return $return_str;
}
/**
 * XML 转 Array
 * @param string $xml
 * @return array
 */
function xml_to_array($xml){
	$reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\\/\\1>/";
	if(preg_match_all($reg, $xml, $matches)){
		$count = count($matches[0]);
		$arr = [];
		for($i = 0; $i < $count; $i++){
			$subxml= $matches[2][$i];
			$key = $matches[1][$i];
			if(preg_match( $reg, $subxml )){
				$arr[$key] = xml_to_array( $subxml );
			}else{
				$arr[$key] = $subxml;
			}
		}
	}
	return $arr;
}
