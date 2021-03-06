<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2018-05-19
 * Version      :   1.0
 */
require("../vendor/autoload.php");

// 定义环境变量
defined("PHP_ENV") or define("PHP_ENV", "dev");
// 定义配置存放目录
defined("CONFIG_PATH") or define("CONFIG_PATH", dirname(realpath(".")) . "/conf");
// 定义配置缓存的存放目录
defined("RUNTIME_PATH") or define("RUNTIME_PATH", dirname(realpath(".")) . "/runtime");

try {
    if ($_GET['c'] || !empty($_GET['c'])) {
        $className = $_GET['c'];
        $class = "\Test\\{$className}";
    } else {
        $class = "\DBootstrap\TestHelper";
    }
    /* @var $class \DBootstrap\Abstracts\Tester */
    $class::getInstance()->run();
} catch (Exception $e) {
    var_dump($e);
}