<?php
/**
 * Link         :   http://www.phpcorner.net
 * User         :   qingbing<780042175@qq.com>
 * Date         :   2019-09-25
 * Version      :   1.0
 */
require("../vendor/autoload.php");

define('ENV', "dev");

$className = \DBootstrap\TestCommand::getInstance()->getParam('c', null);

// php console.php --c=DebugCommand --id=5

try {
    if (null !== $className) {
        $class = "\Test\\{$className}";
    } else {
        $class = "\DBootstrap\\TestHelper";
    }
    /* @var $class \DBootstrap\Abstracts\Tester */
    $class::getInstance()->run();
} catch (\Exception $e) {
    var_dump($e);
}