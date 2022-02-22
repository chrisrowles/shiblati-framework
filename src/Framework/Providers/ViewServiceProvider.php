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

/**
 * Class ViewServiceProvider
 */
class ViewServiceProvider implements ServiceProviderInterface
{
    /**
     * Register view service provider.
     *
     * @param Container $container
     * @return Container
     */
    public function register(Container $container): Container
    {
        $loader = new FilesystemLoader($this->viewPath());
        $container['view'] = new Environment($loader, [
            'cache' => env('APP_CACHE') ? $this->cachePath() : false,
            'debug' => env('APP_DEBUG'),
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

    /**
     * Resolve view path.
     *
     * @return string
     */
    #[Pure] private function viewPath(): string
    {
        return env('APP_VIEW_PATH');
    }

    /**
     * Resolve cache path.
     *
     * @return string
     */
    #[Pure] private function cachePath(): string
    {
        return env('APP_CACHE_PATH');
    }
}