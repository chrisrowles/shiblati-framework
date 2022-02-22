<?php

namespace Shiblati\Framework;

use Shiblati\Framework\Container;

interface ServiceProviderInterface extends \Pimple\ServiceProviderInterface
{
    public function register(\Pimple\Container $container);
}
