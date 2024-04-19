<?php

declare(strict_types=1);

namespace lox24\api_client;

readonly class Response
{

    public function __construct(
        private int $status,
        private ?array $data
    )
    {
    }

    public function getStatus(): int
    {
        return $this->status;
    }

    public function getData(): ?array
    {
        return $this->data;
    }
}