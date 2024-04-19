<?php

declare(strict_types=1);
namespace lox24\api_client_tests;

use lox24\api_client\Config;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(Config::class)]
class ConfigTest extends TestCase
{
    public function testDefaultValues(): void
    {
        $config = new Config('test');
        $this->assertEquals('https://api.lox24.eu', $config->getApiUrl());
        $this->assertEquals('test', $config->getToken());
    }

    public function testCustomApiUrl(): void
    {
        $config = new Config('test', 'https://example.com');
        $this->assertEquals('https://example.com', $config->getApiUrl());
        $this->assertEquals('test', $config->getToken());
    }


}
