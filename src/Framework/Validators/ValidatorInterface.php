<?php

namespace Shiblati\Framework\Validators;

interface ValidatorInterface
{
    public static function check(): mixed;

    public function validate(mixed $data);

    public function params(mixed $params);
}