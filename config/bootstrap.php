<?php

session_start();

/*----------------------------------------
 | Auto-load classes                      |
 ----------------------------------------*/
require_once __DIR__.'/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

/*----------------------------------------
 | Register service providers             |
 ----------------------------------------*/
$app = new Shiblati\Framework\Container();

$app->register(new Shiblati\Framework\Providers\LogServiceProvider());
$app->register(new Shiblati\Framework\Providers\DatabaseServiceProvider());
$app->register(new Shiblati\Framework\Providers\RouteServiceProvider());
$app->register(new Shiblati\Framework\Providers\SessionServiceProvider());
$app->register(new Shiblati\Framework\Providers\ViewServiceProvider());

/**
 * boot method to fetch services from the container
 *
 * @param $dependency
 * @return mixed
 */
function app($dependency = null): mixed
{
    global $app;

    return $app->offsetExists($dependency)
        ? $app->offsetGet($dependency)
        : false;
}

/*----------------------------------------
 | Load controllers                       | 
 ----------------------------------------*/
require_once __DIR__.'/../config/controllers.php';

/*----------------------------------------
 | Load application routes                |
 ----------------------------------------*/
require_once __DIR__.'/../config/routes.php';

new Shiblati\Framework\Handlers\ExceptionHandler($app);
