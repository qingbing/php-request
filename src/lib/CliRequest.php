<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-12-06
 * Version      :   1.0
 */
namespace Request;

use Abstracts\Request;
use Helper\Exception;

/**
 * Class CliRequest
 * @package Request
 */
class CliRequest extends Request
{
    private $_scriptFile;
    private $_getVar = 'params';

    /**
     * 设置GET参数的标记
     * @param string $getVar
     */
    public function setGetVar($getVar)
    {
        $this->_getVar = $getVar;
    }

    /**
     * Cli request 初始化
     * @throws Exception
     */
    public function init()
    {
        if (!preg_match("/cli/i", php_sapi_name())) {
            throw new Exception("该程序是cli模式，只能在命令行模式下用脚步执行", 100600101);
        }

        // 计算命令绝对路径
        $scriptName = $this->getScriptName();
        $pwd = getcwd();
        $this->_scriptFile = (strpos($scriptName, $pwd) === 0) ? $scriptName : ($pwd . DS . $scriptName);

        for ($i = 1; $i < $_SERVER['argc']; $i++) {
            $arg = $_SERVER['argv'][$i];
            // 用 "--" 开头作为参数标志
            if (0 !== strpos($arg, '--')) {
                continue;
            }
            // 参数名和参数用 "=" 隔开
            $pos = strpos($arg, '=');
            if (false === $pos) { // 没有分隔符
                continue;
            }
            $name = trim(substr($arg, 2, $pos - 2));
            $value = trim(substr($arg, $pos + 1));
            if ($name === $this->_getVar) {
                // POST query
                $this->parseGetVal($value);
                continue;
            }
            $_POST[$name] = $value;
        }
    }

    /**
     * 解析 GET 的 query字符串
     * @param $var
     */
    protected function parseGetVal($var)
    {
        $kvs = explode("&", $var);
        foreach ($kvs as $kv) {
            $_kva = explode('=', $kv);
            if (count($_kva) < 2) {
                continue;
            }
            $k = trim($_kva[0]);
            if (empty($k)) {
                continue;
            }
            $_GET[$k] = trim($_kva[1]);
        }
    }

    /**
     * @return mixed
     */
    public function getScriptFile()
    {
        return $this->_scriptFile;
    }
}