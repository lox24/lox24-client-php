<?php

namespace lox24\api_client_tests\api\sms;

use lox24\api_client\api\ServiceCode;
use lox24\api_client\api\sms\SmsItemResponse;
use lox24\api_client\api\TextEncoding;
use lox24\api_client\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SmsItemResponse::class)]
class SmsItemResponseTest extends TestCase
{
    /**
     * @throws \JsonException
     */
    public function testArrayParsing()
    {
        $str = '{
            "@id": "/sms/9fe26332-cdbe-11ee-8d61-7c10c91d54a5",
            "@type": "sms",
            "text": "test sms text",
            "sender_id": "Auth0",
            "phone": "+1234567890",
            "delivery_at": 0,
            "status_code": 100,
            "gateway_sent_at": 1708192910,
            "source": 0,
            "dlr_code": 1,
            "ip": "1.1.1.1",
            "created_at": 1708192910,
            "is_unicode": false,
            "uuid": "9fe26332-cdbe-11ee-8d61-7c10c91d54a5",
            "iso2": "GE",
            "voice_lang": "EN",
            "parts_count": 1,
            "chars_count": 94,
            "callback_data": null,
            "bulk_id": null,
            "key_id": 8355,
            "service_code": "direct",
            "is_text_deleted": false,
            "price": 0.095,
            "is_sent": true,
            "requests": "/sms/9fe26332-cdbe-11ee-8d61-7c10c91d54a5/requests",
            "clicks": "/sms/9fe26332-cdbe-11ee-8d61-7c10c91d54a5/clicks"
        }';

        $r = new Response(200, json_decode($str, true, 512, JSON_THROW_ON_ERROR));
        $response = new SmsItemResponse($r);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals('9fe26332-cdbe-11ee-8d61-7c10c91d54a5', $response->getId());
        $this->assertEquals('test sms text', $response->getText());
        $this->assertEquals('Auth0', $response->getSenderId());
        $this->assertEquals('+1234567890', $response->getPhone());
        $this->assertEquals(0, $response->getDeliveryAt());
        $this->assertEquals(100, $response->getStatusCode());
        $this->assertEquals(1708192910, $response->getGatewaySentAt());
        $this->assertEquals(0, $response->getSource());
        $this->assertEquals(1, $response->getDlrCode());
        $this->assertEquals('1.1.1.1', $response->getIp());
        $this->assertEquals(1708192910, $response->getCreatedAt());
        $this->assertEquals(TextEncoding::Gsm, $response->getTextEncoding());
        $this->assertEquals('GE', $response->getIso2());
        $this->assertEquals('EN', $response->getVoiceLang());
        $this->assertEquals(1, $response->getPartsCount());
        $this->assertEquals(94, $response->getCharsCount());
        $this->assertNull($response->getCallbackData());
        $this->assertNull($response->getBulkId());
        $this->assertEquals(8355, $response->getKeyId());
        $this->assertEquals(ServiceCode::Direct, $response->getServiceCode());
        $this->assertFalse($response->isTextDeleted());
        $this->assertEquals(0.095, $response->getPrice());
        $this->assertTrue($response->isSent());
        $this->assertEquals('/sms/9fe26332-cdbe-11ee-8d61-7c10c91d54a5/requests', $response->getRequests());
        $this->assertEquals('/sms/9fe26332-cdbe-11ee-8d61-7c10c91d54a5/clicks', $response->getClicks());


    }

}
