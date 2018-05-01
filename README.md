# ReqForward
用于解决微信回调域名限制的请求转发工具

- 注：代码结构有些混乱，未支持全部的请求转发，后续不定时更新

## 更新说明
```
同一个类别下的接口url有部分相同之处，可以分别设置对象进行代码的结构优化

20180501 添加模型消息接口下的转发实现
```

## 请求流程说明 

由于微信服务号后台限制只能有1个回调域名，当业务需求需要多个域名使用同一个服务号相关请求时，就必须要将相关业务部署到同一个域名下。为了解决这类问题，可指定一个回调域名用于转发微信相关的请求。例如，指定回调域名为 proxy.qq.com ，则需要向微信请求时，请求流程如下：

        
a.qq.com  ---请求--->  proxy.qq.com下的接口 ---请求---> 微信API接口

b.qq.com  ---请求--->  proxy.qq.com下的接口 ---请求---> 微信API接口

proxy.qq.com在接收到请求后，再次请求微信API接口，并将微信API接口的返回内容直输出返回。

## 安全问题


#### 身份验证

请求转发必须加上对应的身份验证校验（例如JWT验证），必须保证是自己的业务域名进行相关调用，否则存在被他人恶意调用或攻击的风险。


#### 参数安全

请求转发可以有效保证服务号的appid和appsecret不暴露于其他业务中，并且提高了项目部署的灵活性（可以在多台机器下部署不同代码的不同项目，减少运维所需的工作）

## 代码说明

#### 配置文件 `config.php`

- 需要填写appid和appsecret的参数
- ACTION_REGION 参数指定了可接受的转发请求，与`forward.php`中的 swtich case 语句一一对应

#### 转发请求处理 `forward.php`

- 使用加密的`sign参数`进行身份校验
- 使用加密的`action参数`进行转发请求的操作指示



## 支持的请求列表

- 获取用户code 
> 微信请求的`redirect_uri`参数,填写为 `urlEncode(域名/目录/proxyG/code.php)`, 而真正需要重定向的地址于配置文件中进行设置，用户最终将会跳转回配置文件指定的重定向地址，并且带有GET参数code，例如`www.qq.com/lib/?code=ssss`


**（以下请求的地址格式均为`域名/目录/proxyG/forward.php?sign=SIGN&action=ACTION`）**

**[微信网页授权接口](https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140842)**
- code换取网页授权 access_token 包
- 刷新 access_token
- 拉取用户信息

**[获取 access_token](https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421140183)**
- 获取全局 access_token

**[模板消息类接口](https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1433751277)**
- 发送模板消息
- 设置所属行业
- 获取设置的行业信息
- 添加模板并获取模板id
- 获取模板列表
- 删除模板消息

**[微信JSSDK](https://mp.weixin.qq.com/wiki?t=resource/res_main&id=mp1421141115)**
- 获取JSSDK所需的 jsapi_ticket (注：需要将相关域名添加到服务号后台js安全域名中)

