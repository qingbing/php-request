# php-request
## 描述
request，请求的相关获取和使用，可单独使用。

## 注意事项
- request 的参数配置参考 qingbing/php-config 组件
- 提供 CliRequest 和 HttpRequest 两种请求方式
- 对于 console 请求，强烈建议使用 \Request::cliRequest(); 
- 对于 http 请求，强烈建议使用 \Request::httpRequest(); 

## 使用方法
### 1. Request 一级使用
```php
// 判断是否是 http 请求
$isHttpRequest = Request::isHttpRequest();
var_dump($isHttpRequest);

// 获取当前请求
$request = Request::getRequest();
var_dump($request);

// 获取当前 http 请求，当确定是只在 http 模式中使用时建议使用该方法获取 request 请求
$httpRequest = Request::httpRequest();
var_dump($httpRequest);

// 获取当前 console 请求，当确定是只在 console 模式中使用时建议使用该方法获取 request 请求
$cliRequest = Request::cliRequest();
var_dump($cliRequest);
```

### 2. CliRequest 使用
```php
/**
 * test eg :
 *     php console.php --c=TestCliRequest --a=action --params="id=1&name=qingbing"
 * 上述请求除--params被认为是 $_POST, 其余被认为是 $_GET
 */
// 获取组件
$request = Request::cliRequest();
// 获取所有参数
var_dump($request->getParams());
// 获取 GET
var_dump($_GET);
// 获取 POST
var_dump($_POST);
// 获取某一参数
var_dump($request->getParams());
// 获取某一 GET
var_dump($request->getQuery('c'));
// 获取某一 POST
var_dump($request->getPost('id'));
```

### 3. HttpRequest 使用
```php
/**
 * test eg :
 *     http://composer.us/php-request/test/bootstrap.php?c=TestHttpRequest
 */
// 获取组件
$request = Request::httpRequest();
// 获取所有参数
var_dump($request->getParams());
// 获取 GET
var_dump($_GET);
// 获取 POST
var_dump($_POST);
// 获取某一参数
var_dump($request->getParams());
// 获取某一 GET
var_dump($request->getQuery('c'));
// 获取某一 POST
var_dump($request->getPost('id'));
// 获取 accept-types
var_dump($request->getAcceptTypes());
// 判断请求是否是 ajax
var_dump($request->getIsAjaxRequest());
// 获取 url的"query"部分
var_dump($request->getQueryString());
// 返回访问链接是否为安全链接(https).
var_dump($request->getIsSecureConnection());
// 返回主机名
var_dump($request->getHost());
// 获取 http 访问端口
var_dump($request->getServerPort());
// 获取 URL 来源
var_dump($request->getUrlReferrer());
// 获取用户访问客户端信息
var_dump($request->getUserAgent());
// 获取用户IP地址
var_dump($request->getUserHostAddress());
// 返回链接的请求类型，可以设置在POST请求里面，主要为了满足 RESTfull 请求
var_dump($request->getRequestType());
// 获取主文件路径
var_dump($request->getScriptFile());
// 获取普通访问链接的端口
var_dump($request->getPort());
// 获取安全链接（https）的端口
var_dump($request->getSecurePort());
// 获取访问链接的host信息
var_dump($request->getHostInfo());
// 获取应用链接文件名称
var_dump($request->getScriptUrl());
// 返回网页请求的 request_uri
var_dump($request->getRequestUri());
// 获取页面的 baseUrl
var_dump($request->getBaseUrl());
// 获取链接的 pathinfo
var_dump($request->getPathInfo());
// 获取当前链接的"url"
var_dump($request->getUrl());
```

## ====== 异常代码集合 ======

异常代码格式：1006 - XXX - XX （组件编号 - 文件编号 - 代码内异常）
```
 - 100600101 : 该程序是cli模式，只能在命令行模式下用脚步执行
 
 - 100600201 : 该程序是 http 模式，只能在 http 模式下用客户端访问
 - 100600202 : HttpRequest 无法解析 ScriptUrl
 - 100600203 : HttpRequest 无法解析 RequestUri
 - 100600204 : HttpRequest 无法解析 PathInfo
```