<?php 
abstract class Company{

	protected $name;
	protected $loginCookie;
	protected $pageURL;
	
	public function __construct(){
		$this->loginCookie = C('loginCookie');
		$this->pageURL = C('pageURL');
	}
	
	public function getName(){
		return $this->name;
	}

	public function getLoginCookie(){
		return $this->loginCookie;
	}
	
	public function getPageURL(){
		return $this->pageURL;
	}
	
	abstract public function getApplyState($content);
}