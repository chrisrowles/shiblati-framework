<?php

namespace Shiblati\Framework;

use ArrayAccess;
use Pimple\Container as BaseContainer;

class Container extends BaseContainer implements ArrayAccess
{
    public function __construct(array $values = [])
    {
        parent::__construct($values);
    }
}