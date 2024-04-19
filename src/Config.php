<?php

declare(strict_types=1);

namespace lox24\api_client;

readonly final class Config
{
    public const API_URL = 'https://api.lox24.eu';

    public function __construct(
        #[\SensitiveParameter] private string $token,
        private string $apiUrl = self::API_URL
    )
    {
    }

    public function getApiUrl(): string
    {
        return $this->apiUrl;
    }

    public function getToken(): string
    {
        return $this->token;
    }

}