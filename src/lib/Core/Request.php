<?php
/**
 * Created by PhpStorm.
 * User: charles
 * Date: 2018/11/1
 * Time: 下午2:48
 */

namespace Request\Core;

use Abstracts\Component;

abstract class Request extends Component
{
    /**
     * 获取请求传递所有参数值
     * @return array the GET and POST parameter
     */
    public function getParams()
    {
        return array_merge($_GET, $_POST);
    }

    /**
     * 获取请求传递参数值
     * @param string $name
     * @param mixed $defaultValue
     * @return array the GET or POST parameter
     */
    public function getParam($name, $defaultValue = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : (isset($_POST[$name]) ? $_POST[$name] : $defaultValue);
    }

    /**
     * 从 GET 列表里面获取参数
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed the GET parameter
     */
    public function getQuery($name, $defaultValue = null)
    {
        return isset($_GET[$name]) ? $_GET[$name] : $defaultValue;
    }

    /**
     * 从 POST 列表里面获取参数
     * @param string $name
     * @param mixed $defaultValue
     * @return mixed POST parameter
     */
    public function getPost($name, $defaultValue = null)
    {
        return isset($_POST[$name]) ? $_POST[$name] : $defaultValue;
    }

    /**
     * 获取脚本相对路径
     * @return mixed
     */
    public function getScriptName()
    {
        return $_SERVER['SCRIPT_NAME'];
    }
}