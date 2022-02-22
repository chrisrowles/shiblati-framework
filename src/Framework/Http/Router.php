<?php

namespace Shiblati\Framework\Http;

use Klein\Klein;
use Klein\ServiceProvider;
use Klein\AbstractRouteFactory;
use Klein\DataCollection\RouteCollection;

class Router extends Klein
{
    public function __construct(
        ServiceProvider $service = null,
        mixed $app = null,
        RouteCollection $routes = null,
        AbstractRouteFactory $route_factory = null
    ) {
        parent::__construct($service, $app, $routes, $route_factory);
    }
}