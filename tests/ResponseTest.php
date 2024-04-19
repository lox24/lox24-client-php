<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use lox24\api_client\Response;

#[CoversClass(Response::class)]
final class ResponseTest extends TestCase
{
    public function testCanBeCreatedWithStatusAndData(): void
    {
        $status = 200;
        $data = ['key' => 'value'];

        $response = new Response($status, $data);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($status, $response->getStatus());
        $this->assertEquals($data, $response->getData());
    }

    public function testCanBeCreatedWithNullData(): void
    {
        $status = 404;
        $data = null;

        $response = new Response($status, $data);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals($status, $response->getStatus());
        $this->assertNull($response->getData());
    }
}
