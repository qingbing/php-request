<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-10-27
 * Version      :   1.0
 */

namespace Test;

use Components\Request;
use TestCore\Tester;

class TestRequest extends Tester
{
    /**
     * 执行函数
     * @throws \Exception
     */
    public function run()
    {
        // 判断是否是 http 请求
        $isHttpRequest = Request::isHttpRequest();
        var_dump($isHttpRequest);

        // 获取当前请求
        $request = Request::getRequest();
        var_dump($request);

        // 获取当前 http 请求，当确定是只在 http 模式中使用时建议使用该方法获取 request 请求
//        $httpRequest = Request::httpRequest();
//        var_dump($httpRequest);

        // 获取当前 console 请求，当确定是只在 console 模式中使用时建议使用该方法获取 request 请求
//        $cliRequest = Request::cliRequest();
//        var_dump($cliRequest);
    }
}