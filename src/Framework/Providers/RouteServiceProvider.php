<?php

namespace Shiblati\Framework\Providers;

use Shiblati\Framework\Container;
use Shiblati\Framework\Http\Router;
use Shiblati\Framework\ServiceProviderInterface;

class RouteServiceProvider implements ServiceProviderInterface
{
    public function register(Container|\Pimple\Container $container): Container
    {
        $container['router'] = new Router();

        return $container;
    }
}