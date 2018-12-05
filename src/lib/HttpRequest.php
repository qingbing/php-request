<?php
/**
 * Created by PhpStorm.
 * User: charles
 * Date: 2018/10/31
 * Time: 上午10:51
 */

namespace Request;

use Helper\Exception;
use Request\Core\Request;

class HttpRequest extends Request
{
    private $_hostInfo;
    private $_scriptUrl;
    private $_requestUri;
    private $_baseUrl;
    private $_pathInfo;
    private $_url;

    /**
     * http request 初始化
     * @throws Exception
     */
    public function init()
    {
        if (preg_match("/cli/i", php_sapi_name())) {
            throw new Exception("该程序是 http 模式，只能在 http 模式下用客户端访问", 100900201);
        }
        // 请求 normalize
        $this->normalizeRequest();
    }

    /**
     * 确保请求参数都是普通的字符串
     */
    protected function normalizeRequest()
    {
        // normalize request
        if (function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) {
            isset($_GET) && ($_GET = $this->stripSlashes($_GET));
            isset($_POST) && ($_POST = $this->stripSlashes($_POST));
            isset($_REQUEST) && ($_REQUEST = $this->stripSlashes($_REQUEST));
            isset($_COOKIE) && ($_COOKIE = $this->stripSlashes($_COOKIE));
        }
    }

    /**
     * 转义参数的特殊字符
     * @param mixed $data
     * @return mixed
     */
    protected function stripSlashes(&$data)
    {
        return is_array($data) ? array_map([$this, 'stripSlashes'], $data) : stripslashes($data);
    }

    /**
     * 获取 accept-types
     * @return string
     */
    public function getAcceptTypes()
    {
        return isset($_SERVER['HTTP_ACCEPT']) ? $_SERVER['HTTP_ACCEPT'] : null;
    }

    /**
     * 判断请求是否是 ajax
     * @return bool
     */
    public function getIsAjaxRequest()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }

    /**
     * 获取 url的"query"部分
     * @return string
     */
    public function getQueryString()
    {
        return isset($_SERVER['QUERY_STRING']) ? $_SERVER['QUERY_STRING'] : '';
    }

    /**
     * 返回访问链接是否为安全链接(https).
     * @return bool
     */
    public function getIsSecureConnection()
    {
        return isset($_SERVER['HTTPS']) && !strcasecmp($_SERVER['HTTPS'], 'on');
    }

    /**
     * 返回主机名
     * @return string
     */
    public function getHost()
    {
        return isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : $_SERVER['SERVER_NAME'];
    }

    /**
     * 获取 http 访问端口
     * @return string
     */
    public function getServerPort()
    {
        return $_SERVER['SERVER_PORT'];
    }

    /**
     * 获取 URL 来源
     * @return string
     */
    public function getUrlReferrer()
    {
        return isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
    }

    /**
     * 获取用户访问客户端信息
     * @return string
     */
    public function getUserAgent()
    {
        return isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : null;
    }

    /**
     * 获取用户IP地址
     * @return string
     */
    public function getUserHostAddress()
    {
        return isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1';
    }

    /**
     * 返回链接的请求类型，可以设置在POST请求里面，主要为了满足 RESTfull 请求
     * 类型有：GET, POST, HEAD, PUT, PATCH, DELETE.
     * @return string
     */
    public function getRequestType()
    {
        if (isset($_POST['_method'])) {
            return strtoupper($_POST['_method']);
        } elseif (isset($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE'])) {
            return strtoupper($_SERVER['HTTP_X_HTTP_METHOD_OVERRIDE']);
        }

        return strtoupper(isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET');
    }

    /**
     * 获取主文件路径
     * @return string
     */
    public function getScriptFile()
    {
        return realpath($_SERVER['SCRIPT_FILENAME']);
    }

    /**
     * 获取普通访问链接的端口
     * @return int
     */
    public function getPort()
    {
        return !$this->getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 80;
    }

    /**
     * 获取安全链接（https）的端口
     * @return int
     */
    public function getSecurePort()
    {
        return $this->getIsSecureConnection() && isset($_SERVER['SERVER_PORT']) ? (int)$_SERVER['SERVER_PORT'] : 443;
    }

    /**
     * 获取访问链接的host信息
     * @param string $schema => https | http
     * @return string
     */
    public function getHostInfo($schema = '')
    {
        if (null === $this->_hostInfo) {
            $http = ($secure = $this->getIsSecureConnection()) ? 'https' : 'http';

            if (isset($_SERVER['HTTP_HOST'])) {
                $this->_hostInfo = $http . '://' . $_SERVER['HTTP_HOST'];
            } else {
                $this->_hostInfo = $http . '://' . $_SERVER['SERVER_NAME'];
                $port = $secure ? $this->getSecurePort() : $this->getPort();
                if (($port !== 80 && !$secure) || ($port !== 443 && $secure)) {
                    $this->_hostInfo .= ':' . $port;
                }
            }
        }

        if ('' !== $schema) {
            $secure = $this->getIsSecureConnection();
            if ($secure && $schema === 'https' || !$secure && $schema === 'http') {
                return $this->_hostInfo;
            }

            $port = $schema === 'https' ? $this->getSecurePort() : $this->getPort();
            if ($port !== 80 && $schema === 'http' || $port !== 443 && $schema === 'https') {
                $port = ':' . $port;
            } else {
                $port = '';
            }

            $pos = strpos($this->_hostInfo, ':');
            return $schema . substr($this->_hostInfo, $pos, strcspn($this->_hostInfo, ':', $pos + 1) + 1) . $port;
        } else
            return $this->_hostInfo;
    }


    /**
     * 获取应用链接文件名称
     * @return string
     * @throws Exception
     */
    public function getScriptUrl()
    {
        if (null === $this->_scriptUrl) {
            $scriptName = basename($_SERVER['SCRIPT_FILENAME']);
            if (basename($_SERVER['SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['SCRIPT_NAME'];
            } else if (basename($_SERVER['PHP_SELF']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['PHP_SELF'];
            } else if (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === $scriptName) {
                $this->_scriptUrl = $_SERVER['ORIG_SCRIPT_NAME'];
            } else if (false !== ($pos = strpos($_SERVER['PHP_SELF'], '/' . $scriptName))) {
                $this->_scriptUrl = substr($_SERVER['SCRIPT_NAME'], 0, $pos) . '/' . $scriptName;
            } else if (isset($_SERVER['DOCUMENT_ROOT']) && strpos($_SERVER['SCRIPT_FILENAME'], $_SERVER['DOCUMENT_ROOT']) === 0) {
                $this->_scriptUrl = str_replace('\\', '/', str_replace($_SERVER['DOCUMENT_ROOT'], '', $_SERVER['SCRIPT_FILENAME']));
            } else {
                throw new Exception("HttpRequest 无法解析 ScriptUrl", 100900202);
            }
        }
        return $this->_scriptUrl;
    }

    /**
     * 返回网页请求的 request_uri
     * @return mixed|string
     * @throws Exception
     */
    public function getRequestUri()
    {
        if (null === $this->_requestUri) {
            if (isset($_SERVER['HTTP_X_REWRITE_URL'])) { // IIS
                $this->_requestUri = $_SERVER['HTTP_X_REWRITE_URL'];
            } else if (isset($_SERVER['REQUEST_URI'])) {
                $this->_requestUri = $_SERVER['REQUEST_URI'];
                if (isset($_SERVER['HTTP_HOST'])) {
                    if (false !== strpos($this->_requestUri, $_SERVER['HTTP_HOST'])) {
                        $this->_requestUri = preg_replace('/^\w+:\/\/[^\/]+/', '', $this->_requestUri);
                    }
                } else {
                    $this->_requestUri = preg_replace('/^(http|https):\/\/[^\/]+/i', '', $this->_requestUri);
                }
            } else if (isset($_SERVER['ORIG_PATH_INFO'])) { // IIS 5.0 CGI
                $this->_requestUri = $_SERVER['ORIG_PATH_INFO'];
                if (!empty($_SERVER['QUERY_STRING'])) {
                    $this->_requestUri .= '?' . $_SERVER['QUERY_STRING'];
                }
            } else {
                throw new Exception("HttpRequest 无法解析 RequestUri", 100900203);
            }
        }
        return $this->_requestUri;
    }

    /**
     * 获取页面的 baseUrl
     * @param bool $absolute
     * @return string
     * @throws Exception
     */
    public function getBaseUrl($absolute = false)
    {
        if (null === $this->_baseUrl) {
            $this->_baseUrl = rtrim(dirname($this->getScriptUrl()), '\\/');
        }
        return $absolute ? $this->getHostInfo() . $this->_baseUrl : $this->_baseUrl;
    }

    /**
     * 获取链接的 pathinfo
     * @return string
     * @throws Exception
     */
    public function getPathInfo()
    {
        if (null === $this->_pathInfo) {
            $pathInfo = $this->getRequestUri();
            if (false !== ($pos = strpos($pathInfo, '?'))) {
                $pathInfo = substr($pathInfo, 0, $pos);
            }
            $pathInfo = urldecode($pathInfo);
            $scriptUrl = $this->getScriptUrl();
            $baseUrl = $this->getBaseUrl();
            if (0 === strpos($pathInfo, $scriptUrl)) {
                $pathInfo = substr($pathInfo, strlen($scriptUrl));
            } else if ('' === $baseUrl || 0 === strpos($pathInfo, $baseUrl)) {
                $pathInfo = substr($pathInfo, strlen($baseUrl));
            } else if (0 === strpos($_SERVER['PHP_SELF'], $scriptUrl)) {
                $pathInfo = substr($_SERVER['PHP_SELF'], strlen($scriptUrl));
            } else {
                throw new Exception("HttpRequest 无法解析 PathInfo", 100900204);
            }
            $this->_pathInfo = trim($pathInfo, '/');
        }
        return $this->_pathInfo;
    }

    /**
     * 获取当前链接的"url"
     * @return string
     * @throws Exception
     */
    public function getUrl()
    {
        if (null === $this->_url) {
            if (isset($_SERVER['REQUEST_URI'])) {
                return $this->_url = $_SERVER['REQUEST_URI'];
            }
            $this->_url = $this->getScriptUrl();
            if ('' !== ($pathInfo = $this->getPathInfo())) {
                $this->_url .= '/' . $pathInfo;
            }
            if ('' !== ($queryString = $this->getQueryString())) {
                $this->_url .= '?' . $queryString;
            }
        }
        return $this->_url;
    }

    /**
     * 跳转新链接
     * @param mixed $url
     * @param bool $terminate
     * @param int $statusCode
     */
    public function redirect($url, $terminate = true, $statusCode = 302)
    {
        if (0 === strpos($url, '/') && 0 !== strpos($url, '//')) {
            $url = $this->getHostInfo() . $url;
        }
        header('Location: ' . $url, true, $statusCode);
        if ($terminate) {
            exit;
        }
    }
}