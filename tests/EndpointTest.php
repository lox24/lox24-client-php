<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use lox24\api_client\Endpoint;
use lox24\api_client\Method;

#[CoversClass(Endpoint::class)]
final class EndpointTest extends TestCase
{
    private $method;
    private $endpoint;
    private $endpointObject;

    protected function setUp(): void
    {
        $this->method = Method::GET; // Assuming Method is an enum or a class with constants
        $this->endpoint = '/api/test';
        $this->endpointObject = new Endpoint($this->method, $this->endpoint);
    }

    public function testGetMethodReturnsCorrectMethod(): void
    {
        $this->assertSame($this->method, $this->endpointObject->getMethod());
    }

    public function testGetEndpointReturnsCorrectEndpoint(): void
    {
        $this->assertSame($this->endpoint, $this->endpointObject->getEndpoint());
    }
}
