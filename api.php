<?php
$GLOBALS = array(
		// 投递的公司，暂时支持百度和阿里巴巴
		'type'			=>	'Baidu', // Alibaba
		// 你的短信平台账号密码，每个手机号可以免费发10条，也就足够用了:
		'smsAccount'	=>	'', 
		// 申请链接：http://www.ihuyi.com/product.php#bottom_div：
		'smsPassword'	=>	'', 
		// 接受状态更新的手机号：
		'smsMoblie'		=>	'', 
		// 1、登陆查询到查询状态的页面
		// 2、阿里的同学请将campus.alibaba.com域下的JSESSIONID、tmp0两个Cookie值填入：
		// 2、百度的同学请将http://talent.baidu.com/域下的JSESSIONID、DWRSESSIONID两个Cookie值填入：
		'loginCookie'	=>	'JSESSIONID=...; DWRSESSIONID=...',
		// 填入当前的状态 百度的为后面两个状态，阿里的只有一个状态如：新投递
		'stateNow'		=>	'发布中|新提交'
);
/**
 * 路由入口 
 */
main();
function main(){
	if(!$_GET) return;
	if(isset($_GET['stateNow'])) return stateNow();
	if(isset($_GET['stateRefresh'])) return stateRefresh();
	if(isset($_GET['smsSend'])) return smsSend();
}
/**
 * 应聘状态JSON API
 */
function stateRefresh(){
	$url = 'http://talent.baidu.com/baidu/web/templet1000/index/corpshowDeliveryRecordbaidu!listApplyPosition?urlCorpEdition=null&operational=68D9D2019E2EB7084A99779FC62F2E78FCDF992CCBA7AB75F8B9B2A44FDACBAC5DB5F4C1FBB34881873190D09DAC66DB61BDC43A7589B5F5A0D4B16AFCA9C67FF88FF34B80B0BDE425AB2756A906729374FCF7BE70ADD3D0947F4F2B7E69C3BAC76AEEB8312398F4C7EA7E0C44032E259E11CF75A7837F70';
	$cookie = $GLOBALS['loginCookie'];
	$content = getPageContent($url, $cookie);
	$state = call_user_func('getApplyState_'.$GLOBALS['type'], $content);
	echoJSON($state);
}
/**
 * 应聘状态JSON API
 */
function stateNow(){
	echoJSON(array('type'=>$GLOBALS['type'], 'now'=>$GLOBALS['stateNow']));
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
function getApplyState_Baidu($content){
	$pattern = '/[\s\S]*<tbody>\s*?<tr>([\s\S]*?)<\/tr>\s*?<\/tbody>[\s\S]*/';
	$state = trim(preg_replace($pattern, '$1', $content));
	$state = trim(preg_replace('/\s/', '', $state));
	$state = preg_replace('/[\s\S]*<td>([\s\S]*?)<\/td><td>([\s\S]*?)<\/td>/', '$1|$2', $state);
	return $state;
}
/**
 * 阿里应聘状态页面解析
 * @param string $content
 * @return string html
 */ 
function getApplyState_Alibaba($content){
	$pattern = '/[\s\S]*?[^\.]class="state\-name"[^>]+>([\s\S]*?)<\/td>[\s\S]*/';
	$state = strip_tags(trim(preg_replace($pattern, '$1', $content)));
	return $state;
}
/**
 * 输出JSON
 * @param mixed:string|array
 */
function echoJSON($data){
	header('Content-Type:application/json; charset=utf-8');
	exit(json_encode($data, JSON_UNESCAPED_UNICODE));
}
/**
 * 短信请求接口（http://www.ihuyi.com/）
 * 这个短信平台提供的是POST请求，XML格式返回
 */
function smsSend(){
	$target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
	$auth = "account={$GLOBALS['smsAccount']}&password={$GLOBALS['smsPassword']}";
	$message = $_GET['message']; // 要发送的短信内容
	$message = "您的验证码是：666666 ###\n\n".$message."\n\n###      。请不要把验证码泄露给其他人。"; // 这个模板不要改，不然平台就不让你发了！
	$post_data = $auth."&mobile={$GLOBALS['smsMoblie']}&content=".rawurlencode($message);
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