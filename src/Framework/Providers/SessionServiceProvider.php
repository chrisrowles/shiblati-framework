<?php

namespace Shiblati\Framework\Providers;

use Shiblati\Framework\Models\Session;
use Shiblati\Framework\Container;
use Shiblati\Framework\ServiceProviderInterface;

/**
 * Class DatabaseServiceProvider
 */
class SessionServiceProvider implements ServiceProviderInterface
{
    /**
     * Register database service provider.
     *
     * @param Container $container
     * @return Container
     */
    public function register(Container $container): Container
    {
        $container['session'] = new Session($container);

        return $container;
    }
}