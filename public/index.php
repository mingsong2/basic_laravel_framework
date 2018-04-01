<?php
require '../vendor/autoload.php';
$testClassMap = new \database\test\testClassMap;
$testClassMap->index();
p(222);
$mylog  = new \Monolog\Logger('wangshuo');