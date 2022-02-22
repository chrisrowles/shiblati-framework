<?php

/*----------------------------------------
 | Bootstrap the application              |
 ----------------------------------------*/
require_once __DIR__.'/../config/bootstrap.php';

/*----------------------------------------
 | Dispatch the application               |
 ----------------------------------------*/
/** @var \Shiblati\Framework\Container */
$app['router']->dispatch();
