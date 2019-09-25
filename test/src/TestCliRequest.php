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

class TestCliRequest extends Tester
{
    /**
     * 执行函数
     * @throws \Exception
     */
    public function run()
    {
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
    }
}