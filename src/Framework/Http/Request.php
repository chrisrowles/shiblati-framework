<?php

namespace Shiblati\Framework\Http;

class Request extends \Klein\Request
{
    public function __construct(
        array $get = [],
        array $post = [],
        array $cookies = [],
        array $server = [],
        array $files = array(),
        $body = null
    ) {
        parent::__construct($get, $post, $cookies, $server, $files, $body);
    }
}