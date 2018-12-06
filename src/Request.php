<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-12-06
 * Version      :   1.0
 */

use Request\CliRequest;
use Request\HttpRequest;

defined('DS') or define('DS', DIRECTORY_SEPARATOR);

class Request
{
    private static $_httpRequest;
    private static $_cliRequest;

    /**
     * 判断请求是否是http
     * @return bool
     */
    public static function isHttpRequest()
    {
        return !preg_match("/cli/i", php_sapi_name());
    }

    /**
     * 获取 http web请求的访问组件
     * @return HttpRequest
     * @throws Exception
     */
    public static function httpRequest()
    {
        if (null === self::$_httpRequest) {
            self::$_httpRequest = HttpRequest::getInstance([
                'c-file' => 'request',
                'c-group' => 'http',
            ]);
        }
        return self::$_httpRequest;
    }

    /**
     * 获取 cli 命令行请求的访问组件
     * @return CliRequest
     * @throws Exception
     */
    public static function cliRequest()
    {
        if (null === self::$_cliRequest) {
            self::$_cliRequest = CliRequest::getInstance([
                'c-file' => 'request',
                'c-group' => 'cli',
            ]);
        }
        return self::$_cliRequest;
    }

    /**
     * 返回当前的访问组件
     * @return CliRequest|HttpRequest
     * @throws Exception
     */
    public static function getRequest()
    {
        return self::isHttpRequest() ? self::httpRequest() : self::cliRequest();
    }
}