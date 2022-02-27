<?php

namespace Shiblati\Framework\Config;

abstract class Config
{
    /** @var mixed $_instance */
    protected static mixed $instance;

    public static function init(): mixed
    {
        $singleton = get_called_class();
        self::$instance = new $singleton();

        return self::$instance;
    }
}