<?php

namespace Shiblati\Framework\Validators;

use Exception;

class UserUpdateValidator extends AbstractValidator
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
    public function __construct(mixed $params)
    {
        $this->validate($params);

        $this->id    = $params->id;
        $this->email = $params->password;
        $this->name  = $params->password;
    }
}