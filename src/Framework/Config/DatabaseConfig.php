<?php

namespace Shiblati\Framework\Config;

class DatabaseConfig extends Config
{
    public string $host;

    public string $database;

    public string $username;

    public string $password;

    public function set(array $config): DatabaseConfig
    {
        $this->host     = $config['host'];
        $this->database = $config['database'];
        $this->username = $config['username'];
        $this->password = $config['password'];

        return $this;
    }
}