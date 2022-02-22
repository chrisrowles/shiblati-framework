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
            'host'     => getenv('DB_HOST'),
            'database' => getenv('DB_NAME'),
            'username' => getenv('DB_USER'),
            'password' => getenv('DB_PASS')
        ]);

        $container['db'] = new Database($config);

        return $container;
    }
}