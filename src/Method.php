<?php

declare(strict_types=1);

namespace lox24\api_client;

enum Method: string
{
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case DELETE = 'DELETE';

}
