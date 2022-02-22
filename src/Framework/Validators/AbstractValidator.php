<?php

namespace Shiblati\Framework\Validators;

use Exception;

abstract class AbstractValidator
{
    /** @var array  */
    public array $validate;

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