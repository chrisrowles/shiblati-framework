<?php

namespace Shiblati\Framework\Http;

class Response extends \Klein\Response
{
    public function __construct(
        string $body = '',
        int|null $code = null,
        array $headers = []
    ) {
        parent::__construct($body, $code, $headers);
    }
}