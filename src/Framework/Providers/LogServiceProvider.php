<?php

namespace Shiblati\Framework\Providers;

use Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use JetBrains\PhpStorm\Pure;
use Shiblati\Framework\Container;
use Shiblati\Framework\ServiceProviderInterface;

class LogServiceProvider implements ServiceProviderInterface
{
    public function register(Container|\Pimple\Container $container): Container|string
    {
        $container['log'] = new Logger('app');

        try {
            $container['log']->pushHandler(new StreamHandler(
                $this->logPath() . $this->logFile(), Logger::DEBUG
            ));
        } catch (Exception $e) {
            return $e->getMessage();
        }

        return $container;
    }

    #[Pure] private function logPath(): string
    {
        return env('APP_LOG_PATH');
    }

    #[Pure] private function logFile(): string
    {
        return env('APP_LOG_NAME') ?? 'app.log';
    }
}