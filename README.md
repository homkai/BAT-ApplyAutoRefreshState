# AlibabaApplyAutoRefreshState
Automatically Refresh Your Job Apply State on Alibaba Campus/Intern, and it will send a message (SMS) to inform you!
# 阿里巴巴校园招聘（应届/实习），简历状态自动刷新，短信通知程序
该程序会自动刷新你的简历状态，并在简历状态更新后，及时通知你。这样你就不用重复登录网页查状态了！
## Overview
### api.php
服务器端解析“个人中心”页面，并整理出最新状态
提供发短信接口
### auto.html 
前端轮询，并及时发短信通知
## Get started
1. api.php中配置：$loginCookie（登陆后，将campus.alibaba.com域下的JSESSIONID、tmp两个Cookie值填入）
2. api.php中配置：$smsAccount、$smsPassword（你的短信平台账号密码，每个手机号可以免费发10条，也就足够用了！）、$smsMoblie（接受状态更新的手机号）短信平台申请链接：http://www.ihuyi.com/product.php#bottom_div
3. auto.html中配置当前简历的状态defaultState
4. 将这两个文件传至一个PHP服务器（本地亦可），然后浏览器访问auto.html
5. 推荐上传至你的服务器，这样你的电脑就可以正常关机等等。在服务器的浏览器打开auto.html，从此你就可以尽情地等短信了。
