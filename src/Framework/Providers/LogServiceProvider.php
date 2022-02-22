<?php

namespace Shiblati\Framework\Providers;

use JetBrains\PhpStorm\Pure;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Shiblati\Framework\Container;
use Shiblati\Framework\ServiceProviderInterface;

/**
 * Class LogServiceProvider
 */
class LogServiceProvider implements ServiceProviderInterface
{
    /**
     * Register log service provider.
     *
     * @param Container $container
     * @return Container|string
     */
    public function register(Container $container): Container|string
    {
        $container['log'] = new Logger('app');

        try {
            $container['log']->pushHandler(new StreamHandler(
                $this->logPath(), Logger::DEBUG)
            );
        } catch (\Exception $e) {
            return $e->getMessage();
        }

        return $container;
    }

    /**
     * Resolve log path.
     *
     * @return string
     */
    #[Pure] private function logPath(): string
    {
        return env('APP_LOG_PATH');
    }
}