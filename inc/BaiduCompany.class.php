<?php 
class BaiduCompany extends Company{

	public function __construct(){
		parent::__construct();
		$this->name = 'Baidu';
	}
	
	/**
	 * 应聘状态页面解析
	 * @param string $content
	 * @return string html
	 */
	public function getApplyState($content){
		$pattern = '/[\s\S]*<tbody>\s*?<tr>([\s\S]*?)<\/tr>\s*?<\/tbody>[\s\S]*/';
		$state = trim(preg_replace($pattern, '$1', $content));
		$state = trim(preg_replace('/\s/', '', $state));
		$state = preg_replace('/[\s\S]*<td>([\s\S]*?)<\/td><td>([\s\S]*?)<\/td>/', '$1|$2', $state);
		return $state;
	}
}