<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use PHPUnit\Framework\TestCase;
use lox24\api_client\Endpoint;
use lox24\api_client\Method;
use lox24\api_client\Request;
use lox24\api_client\ListFilter;
use JsonSerializable;

final class RequestTest extends TestCase
{
    public function testGetUriWithoutFilter(): void
    {
        $endpoint = $this->createMock(Endpoint::class);
        $endpoint->method('getEndpoint')->willReturn('/api/test');
        $endpoint->method('getMethod')->willReturn(Method::GET); // Assuming Method::GET is valid

        $request = new Request($endpoint);

        $this->assertEquals('/api/test', $request->getUri());
    }

    public function testGetUriWithFilter(): void
    {
        $endpoint = $this->createMock(Endpoint::class);
        $endpoint->method('getEndpoint')->willReturn('/api/test');
        $endpoint->method('getMethod')->willReturn(Method::GET);

        $filter = $this->createMock(ListFilter::class);
        $filter->method('__toString')->willReturn('page=1&limit=10');

        $request = new Request($endpoint, null, $filter);

        $this->assertEquals('/api/test?page=1&limit=10', $request->getUri());
    }

    public function testGetMethod(): void
    {
        $endpoint = $this->createMock(Endpoint::class);
        $endpoint->method('getEndpoint')->willReturn('/api/test');
        $endpoint->method('getMethod')->willReturn(Method::GET);

        $request = new Request($endpoint);

        $this->assertSame(Method::GET, $request->getMethod());
    }

    public function testGetDataReturnsCorrectData(): void
    {
        $endpoint = $this->createMock(Endpoint::class);
        $data = $this->createMock(JsonSerializable::class);

        $request = new Request($endpoint, $data);

        $this->assertSame($data, $request->getData());
    }
}
