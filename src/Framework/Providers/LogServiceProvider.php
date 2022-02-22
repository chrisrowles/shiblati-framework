<?php

namespace Shiblati\Framework\Providers;

use Monolog\Logger;
use JetBrains\PhpStorm\Pure;
use Monolog\Handler\StreamHandler;
use Shiblati\Framework\Container;
use Shiblati\Framework\ServiceProviderInterface;

class LogServiceProvider implements ServiceProviderInterface
{
    public function register(Container|\Pimple\Container $container): Container|string
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

    #[Pure] private function logPath(): string
    {
        return getenv('APP_LOG_PATH');
    }
}