<?php

namespace Shiblati\Framework\Validators;

use Exception;

class UserLoginValidator extends AbstractValidator
{
    /** @var string  */
    public string $username;

    /** @var string  */
    public string $password;

    /** @var array|string[] */
    public array $validate = [
        'username',
        'password'
    ];

    /**
     * @throws Exception
     */
    public function __construct(mixed $params)
    {
        $this->validate($params);

        $this->username = $params->username;
        $this->password = $params->password;
    }
}