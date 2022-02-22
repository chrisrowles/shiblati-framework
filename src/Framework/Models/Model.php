<?php

namespace Shiblati\Framework\Models;

use Shiblati\Framework\Container;

/**
 * Abstract Class Model
 */
abstract class Model
{
    /** @var mixed $log */
    protected mixed $log;

    /** @var mixed $db */
    protected mixed $db;

    /**
     * Model constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->log = $container['log'];
        $this->db = $container['db'];
    }
}