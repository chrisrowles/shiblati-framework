<?php

namespace Shiblati\Framework\Providers;

use Shiblati\Framework\Container;
use Shiblati\Framework\Models\Session;
use Shiblati\Framework\ServiceProviderInterface;

class SessionServiceProvider implements ServiceProviderInterface
{
    public function register(Container|\Pimple\Container $container): Container
    {
        $container['session'] = new Session($container);

        return $container;
    }
}