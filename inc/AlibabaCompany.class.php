<?php 
class AlibabaCompany extends Company{

	public function __construct(){
		parent::__construct();
		$this->name = 'Alibaba';
		$this->pageURL = 'https://campus.alibaba.com/myJobApply.htm';
	}
	
	/**
	 * 应聘状态页面解析
	 * @param string $content
	 * @return string html
	 */
	public function getApplyState($content){
		$pattern = '/[\s\S]*?[^\.]class="state\-name"[^>]+>([\s\S]*?)<\/td>[\s\S]*/';
		$state = strip_tags(trim(preg_replace($pattern, '$1', $content)));
		return $state;
	}
}