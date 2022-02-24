<?php

namespace Shiblati\Framework;

use Exception;
use Shiblati\Framework\Validators\ValidatorInterface;

abstract class Validator implements ValidatorInterface
{
    /** @var array  */
    public array $validate;

    /** @var mixed $_instance */
    protected static mixed $instance;

    public static function check(): mixed
    {
        $singleton = get_called_class();
        self::$instance = new $singleton();

        return self::$instance;
    }

    /**
     * @param mixed $data
     * @return bool
     * @throws Exception
     */
    public function validate(mixed $data): bool
    {
        if (!empty($data)) {
            $missing = [];
            foreach($this->validate as $attribute) {
                if (!isset($data->{$attribute})) {
                    $missing[$attribute] = $attribute;
                }
            }

            if (empty($missing)) {
                return true;
            } else {
                throw new Exception('The following parameters are missing from the request: '
                    . implode(', ', $missing));
            }
        } else {
            throw new Exception('No parameters detected.');
        }
    }
}