<?php 
return array(
	// 投递的公司，暂时支持百度和阿里巴巴：
	'company'	=>	'Alibaba', // Alibaba,Baidu
	// 1、登陆查询到查询状态的页面
	// 2、阿里的同学请将campus.alibaba.com域下的JSESSIONID、tmp0两个Cookie值填入：
	// 2、百度的同学请将http://talent.baidu.com/域下的JSESSIONID、DWRSESSIONID两个Cookie值填入，如：'JSESSIONID=EF6YPP1W...; tmp0=eNo9yM...;'：
	'loginCookie'=>	'JSESSIONID=EF6YPP1W...; tmp0=eNo9yM...;',
	// 填入当前的状态 百度的为后面两个状态，如：；阿里的只有一个状态如：新投递：
	'currentState'=>'新投递',
	// 轮询间隔时间（秒）：
	'interval'	=>	80,
	// 百度的要填入查状态的页面URL，阿里的无需理会：
	'pageURL'	=>	'http://talent.baidu.com/baidu/web/templet1000/index/corpshowDeliveryRecordbaidu!listApplyPosition?urlCorpEdition=null&operational=68D9D2019E2EB7084A99779FC62F2E78FCDF992CCBA7AB75F8B9B2A44FDACBAC5DB5F4C1FBB34881873190D09DAC66DB61BDC43A7589B5F5A0D4B16AFCA9C67FF88FF34B80B0BDE425AB2756A906729374FCF7BE70ADD3D0947F4F2B7E69C3BAC76AEEB8312398F4C7EA7E0C44032E259E11CF75A7837F70',
	// 短信通知配置
	'SMS'		=>array(
		// 你的短信平台账号密码，每个手机号可以免费发10条，也就足够用了:
		'account'	=>	'cf_z1..',
		// 申请链接：http://www.ihuyi.com/product.php#bottom_div：
		'password'	=>	'z1...',
		// 接受状态更新的手机号：
		'mobile'	=>	'158...',
	),
);