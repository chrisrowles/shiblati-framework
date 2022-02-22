<?php

namespace Shiblati\Framework\Providers;

use Klein\Klein;
use Shiblati\Framework\Container;
use Shiblati\Framework\ServiceProviderInterface;

/**
 * Class RouteServiceProvider
 */
class RouteServiceProvider implements ServiceProviderInterface
{
    /**
     * Register route service provider.
     *
     * @param Container $container
     * @return Container
     */
    public function register(Container $container): Container
    {
        $container['router'] = new Klein();

        return $container;
    }
}