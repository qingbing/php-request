<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-10-27
 * Version      :   1.0
 */

namespace Test;

use Components\Request;
use DBootstrap\Abstracts\Tester;

class TestHttpRequest extends Tester
{
    /**
     * 执行函数
     * @throws \Exception
     */
    public function run()
    {
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
    }
}