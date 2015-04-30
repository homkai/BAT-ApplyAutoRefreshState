# 百度、阿里 校招（应届/实习）自动刷新投递状态，短信通知
该程序会自动刷新你的简历投递状态，并在状态更新后，及时发短信通知你。这样你就不用重复登录网页查状态了！

## Overview
### api.php
服务器端解析“个人中心”页面，并整理出最新状态
提供发短信接口
### auto.html 
轮询获取当前状态
当状态改变时发短信通知

## Get started
1.api.php中参考如下配置：

$GLOBALS = array(

        // 投递的公司，暂时支持百度和阿里巴巴
        
        'type'          =>  'Baidu', // Alibaba
        
        // 你的短信平台账号密码，每个手机号可以免费发10条，也就足够用了:
        
        'smsAccount'    =>  '', 
        
        // 申请链接：http://www.ihuyi.com/product.php#bottom_div：
        
        'smsPassword'   =>  '', 
        
        // 接受状态更新的手机号：
        
        'smsMoblie'     =>  '', 
        
        // 1、登陆查询到查询状态的页面
        
        // 2、阿里的同学请将campus.alibaba.com域下的JSESSIONID、tmp0两个Cookie值填入：
        
        // 2、百度的同学请将http://talent.baidu.com/域下的JSESSIONID、DWRSESSIONID两个Cookie值填入：
        
        'loginCookie'   =>  'JSESSIONID=...; DWRSESSIONID=...',
        
        // 填入当前的状态 百度的为后面两个状态，阿里的只有一个状态如：新投递
        
        'stateNow'      =>  '发布中|新提交'
        
);

2. 将这两个文件传至一个PHP服务器（本地亦可），然后浏览器访问auto.html

3. 推荐上传至你的服务器，这样你的电脑就可以正常关机等等。在服务器的浏览器打开auto.html，从此你就可以尽情地等短信了。
