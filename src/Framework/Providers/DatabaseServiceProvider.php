<?php

namespace Shiblati\Framework\Providers;

use Shiblati\Framework\Container;
use Shiblati\Framework\Database;
use Shiblati\Framework\Config\DatabaseConfig;
use Shiblati\Framework\ServiceProviderInterface;

class DatabaseServiceProvider implements ServiceProviderInterface
{
    public function register(Container|\Pimple\Container $container): Container
    {
        $config = DatabaseConfig::init()->set([
            'host'     => env('DB_HOST'),
            'database' => env('DB_NAME'),
            'username' => env('DB_USER'),
            'password' => env('DB_PASS')
        ]);

        $container['db'] = new Database($config);

        return $container;
    }
}