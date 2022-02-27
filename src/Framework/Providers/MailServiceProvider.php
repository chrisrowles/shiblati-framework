<?php

namespace Shiblati\Framework\Providers;

use PHPMailer\PHPMailer\SMTP;
use Shiblati\Framework\Container;
use Shiblati\Framework\Mailer;
use Shiblati\Framework\Config\MailerConfig;
use Shiblati\Framework\ServiceProviderInterface;

class MailServiceProvider implements ServiceProviderInterface
{
    public function register(Container|\Pimple\Container $container): Container
    {
        $config = MailerConfig::init()->set([
            'enabled'    => true,
            'auth'       => env('MAIL_AUTH'),
            'debug'      => env('APP_DEBUG') ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF,
            'driver'     => env('MAIL_DRIVER'),
            'host'       => env('MAIL_HOST'),
            'username'   => env('MAIL_USERNAME'),
            'password'   => env('MAIL_PASSWORD'),
            'encryption' => env('MAIL_ENCRYPTION'),
            'port'       => env('MAIL_PORT'),
        ]);

        $container['mail'] = new Mailer($config);

        return $container;
    }
}