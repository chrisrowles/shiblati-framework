<?php

namespace Shiblati\Framework\Providers;

use JetBrains\PhpStorm\Pure;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\Extension\DebugExtension;
use Shiblati\Framework\Extensions\Twig\UrlExtension;
use Shiblati\Framework\Extensions\Twig\AssetExtension;
use Shiblati\Framework\Extensions\Twig\DotenvExtension;
use Shiblati\Framework\Extensions\Twig\SessionExtension;
use Shiblati\Framework\Container;
use Shiblati\Framework\ServiceProviderInterface;

class ViewServiceProvider implements ServiceProviderInterface
{
    public function register(Container|\Pimple\Container $container): Container
    {
        $loader = new FilesystemLoader($this->viewPath());
        $container['view'] = new Environment($loader, [
            'cache' => getenv('APP_CACHE') ? $this->cachePath() : false,
            'debug' => getenv('APP_DEBUG'),
        ]);
        $container['view']->addGlobal('session', $_SESSION);
        $container['view']->addGlobal('request', $_REQUEST);

        $container['view']->addExtension(new DebugExtension());
        $container['view']->addExtension(new DotenvExtension());
        $container['view']->addExtension(new AssetExtension());
        $container['view']->addExtension(new SessionExtension());
        $container['view']->addExtension(new UrlExtension());

        return $container;
    }

    #[Pure] private function viewPath(): string
    {
        return getenv('APP_VIEW_PATH');
    }

    #[Pure] private function cachePath(): string
    {
        return getenv('APP_CACHE_PATH');
    }
}