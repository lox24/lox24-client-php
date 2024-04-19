<?php

declare(strict_types=1);

namespace lox24\api_client;

use JsonSerializable;

final readonly class Request
{

    public function __construct(
        private Endpoint          $endpoint,
        private ?JsonSerializable $data = null,
        private ?ListFilter       $filter = null
    ) {
    }

    public function getUri(): string
    {
        return $this->endpoint->getEndpoint() . ($this->filter ? '?' : '') . $this->filter;
    }

    public function getMethod(): Method
    {
        return $this->endpoint->getMethod();
    }

    public function getData(): ?JsonSerializable
    {
        return $this->data;
    }

}