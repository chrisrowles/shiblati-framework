<?php

namespace Shiblati\Framework\Validators;

use Exception;
use Shiblati\Framework\Validator;

class UserLoginValidator extends Validator implements ValidatorInterface
{
    /** @var string */
    public string $username;

    /** @var string */
    public string $password;

    /** @var array|string[] */
    public array $validate = [
        'username',
        'password',
    ];

    /**
     * @throws Exception
     */
    public function params(mixed $params): UserLoginValidator
    {
        $this->validate($params);

        $this->username = $params->username;
        $this->password = $params->password;

        return $this;
    }
}