<?php

namespace Shiblati\Framework\Validators;

use Exception;
use Shiblati\Framework\Validator;

class UserUpdateValidator extends Validator implements ValidatorInterface
{
    /** @var string  */
    public string $id;

    /** @var string  */
    public string $email;

    /** @var string  */
    public string $name;

    /** @var array|string[] */
    public array $validate = [
        'id',
        'email',
        'name'
    ];

    /**
     * @throws Exception
     */
    public function params(mixed $params): UserUpdateValidator
    {
        $this->validate($params);

        $this->id    = $params->id;
        $this->email = $params->password;
        $this->name  = $params->password;

        return $this;
    }
}