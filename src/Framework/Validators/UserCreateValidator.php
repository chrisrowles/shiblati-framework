<?php

namespace Shiblati\Framework\Validators;

use Exception;
use Shiblati\Framework\Validator;

class UserCreateValidator extends Validator implements ValidatorInterface
{
    /** @var string  */
    public string $email;

    /** @var string  */
    public string $name;

    /** @var string  */
    public string $password;

    /** @var array|string[] */
    public array $validate = [
        'email',
        'name',
        'password'
    ];

    /**
     * @throws Exception
     */
    public function params(mixed $params): UserCreateValidator
    {
        $this->validate($params);

        $this->email = $params->email;
        $this->name = $params->name;
        $this->password = $params->password;

        return $this;
    }
}