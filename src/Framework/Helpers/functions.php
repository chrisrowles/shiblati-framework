<?php

if (!function_exists('env')) {
    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param string $key
     *
     * @return string|array|bool|null
     */
    function env(string $key): string|array|bool|null
    {
        $value = getenv($key);

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;
            case 'false':
            case '(false)':
                return false;
            case 'empty':
            case '(empty)':
                return '';
            case 'null':
            case '(null)':
                return null;
        }

        if (str_starts_with($value, '"') && str_ends_with($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }
}

if (!function_exists('toArray')) {
    /**
     * Helper method to convert objects to arrays.
     *
     * @param $object
     *
     * @return array
     */
    function toArray($object): array
    {
        if (!is_object($object) && !is_array($object)) {
            return $object;
        }

        return array_map('toArray', (array) $object);
    }
}