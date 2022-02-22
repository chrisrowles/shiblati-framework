<?php

namespace Shiblati\Framework\Providers;

use Klein\Klein;
use Shiblati\Framework\Container;
use Shiblati\Framework\ServiceProviderInterface;

class RouteServiceProvider implements ServiceProviderInterface
{
    public function register(Container|\Pimple\Container $container): Container
    {
        $container['router'] = new Klein();

        return $container;
    }
}