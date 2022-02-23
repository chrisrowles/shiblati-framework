<?php

namespace Shiblati\Framework\Providers;

use Shiblati\Database\Config;
use Shiblati\Database\Database;
use Shiblati\Framework\Container;
use Shiblati\Framework\ServiceProviderInterface;

class DatabaseServiceProvider implements ServiceProviderInterface
{
    public function register(Container|\Pimple\Container $container): Container
    {
        $config = Config::init()->set([
            'host'     => env('DB_HOST'),
            'database' => env('DB_NAME'),
            'username' => env('DB_USER'),
            'password' => env('DB_PASS')
        ]);

        $container['db'] = new Database($config);

        return $container;
    }
}