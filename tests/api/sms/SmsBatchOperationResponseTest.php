<?php

declare(strict_types=1);

namespace lox24\api_client_tests\api\sms;

use lox24\api_client\api\sms\SmsBatchOperationResponse;
use lox24\api_client\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SmsBatchOperationResponse::class)]
class SmsBatchOperationResponseTest extends TestCase
{
    public function testAffectedRowsCounter(): void
    {
        $response = new SmsBatchOperationResponse(new Response(200, ['affected_rows' => 1]));
        $this->assertEquals(1, $response->getAffectedRows());
    }

    public function testAffectedRowsCounterManyProps(): void
    {
        $response = new SmsBatchOperationResponse(new Response(200, ['affected_rows' => 1, 'other' => 'value']));
        $this->assertEquals(1, $response->getAffectedRows());
    }

    public function testEmptyResponse(): void
    {
        $response = new SmsBatchOperationResponse(new Response(200, null));
        $this->assertEquals(0, $response->getAffectedRows());
    }


}
