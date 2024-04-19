<?php

declare(strict_types=1);

namespace lox24\api_client;

final readonly class Endpoint
{

    public function __construct(
        private Method $method,
        private string $endpoint)
    {
    }

    public function getMethod(): Method
    {
        return $this->method;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }


}