<?php

declare(strict_types=1);

namespace lox24\api_client_tests\api;

use lox24\api_client\api\Lox24Api;
use lox24\api_client\api\sms\SmsApi;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use lox24\api_client\Client;

#[CoversClass(Lox24Api::class)]
class Lox24ApiTest extends TestCase
{
    public function testSms(): void
    {
        $this->expectNotToPerformAssertions();
        $client = $this->createMock(Client::class);
        $api = new Lox24Api($client);
        $api->sms();
    }

}
