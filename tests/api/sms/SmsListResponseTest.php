<?php

declare(strict_types=1);

namespace lox24\api_client_tests\api\sms;

use lox24\api_client\api\sms\SmsItemResponse;
use lox24\api_client\api\sms\SmsListResponse;
use lox24\api_client\Response;
use PHPUnit\Framework\TestCase;

class SmsListResponseTest extends TestCase
{
    public function testGetItemClass(): void
    {
        $data = [
            'hydra:member' => [
                [
                    '@id' => '/sms/f5e0b24a-cd64-11ed-8d61-7c10c91d54a5',
                    '@type' => 'sms',
                    'text' => 'test text',
                    'sender_id' => 'Fee',
                    'phone' => '0000',
                    'delivery_at' => 0,
                    'status_code' => 100,
                    'gateway_sent_at' => 4294967295,
                    'source' => 0,
                    'dlr_code' => 0,
                    'ip' => '1.1.1.1',
                    'created_at' => 4294967295,
                    'is_unicode' => false,
                    'uuid' => 'f5e0b24a-cd64-11ed-8d61-7c10c91d54a5',
                    'iso2' => null,
                    'voice_lang' => null,
                    'parts_count' => null,
                    'chars_count' => 0,
                    'callback_data' => null,
                    'bulk_id' => null,
                    'key_id' => null,
                    'service_code' => 'direct',
                    'is_text_deleted' => false,
                    'price' => 5,
                    'is_sent' => true,
                    'requests' => '/sms/f5e0b24a-cd64-11ed-8d61-7c10c91d54a5/requests',
                    'clicks' => '/sms/f5e0b24a-cd64-11ed-8d61-7c10c91d54a5/clicks'
                ],
            ]
        ];
        $list = new SmsListResponse(new Response(200, $data));
        $this->assertInstanceOf(SmsItemResponse::class, $list->getData()[0]);
    }

}
