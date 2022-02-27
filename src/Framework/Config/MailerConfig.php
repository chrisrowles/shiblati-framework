<?php

namespace Shiblati\Framework\Config;

class MailerConfig extends Config
{
    public bool $enabled;

    public bool $auth;

    public mixed $debug;

    public string $driver;

    public string $host;

    public string $username;

    public string $password;

    public mixed $encryption;

    public int $port;

    public function set(array $config): MailerConfig
    {
        $this->enabled    = $config['enabled'];
        $this->auth       = $config['auth'];
        $this->debug      = $config['auth'];
        $this->driver     = $config['auth'];
        $this->host       = $config['auth'];
        $this->username   = $config['auth'];
        $this->password   = $config['auth'];
        $this->encryption = $config['auth'];
        $this->port       = $config['auth'];

        return $this;
    }
}