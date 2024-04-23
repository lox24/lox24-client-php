<?php

declare(strict_types=1);

namespace lox24\api_client_tests\api\sms;

use lox24\api_client\api\ServiceCode;
use lox24\api_client\api\sms\SmsItemRequest;
use lox24\api_client\api\TextEncoding;
use lox24\api_client\api\VoiceLang;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SmsItemRequest::class)]
class SmsItemRequestTest extends TestCase
{
    public function testCanBeInstantiatedWithDefaultValues(): void
    {
        $sms = new SmsItemRequest(
            'sender',
            'phone',
            'text'
        );

        $this->assertEquals(ServiceCode::Direct, $sms->getServiceCode());
        $this->assertEquals(0, $sms->getDeliveryAt());
        $this->assertEquals(TextEncoding::Auto, $sms->getTextEncoding());
        $this->assertNull($sms->getSource());
        $this->assertNull($sms->getVoiceLang());
        $this->assertFalse($sms->isDeleteText());
        $this->assertNull($sms->getCallbackData());
    }

    public function testSetAndGetSender(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setSenderId('newSender');
        $this->assertEquals('newSender', $sms->getSenderId());
    }

    public function testSetAndGetPhone(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setPhone('newPhone');
        $this->assertEquals('newPhone', $sms->getPhone());
    }

    public function testSetAndGetText(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setText('newText');
        $this->assertEquals('newText', $sms->getText());
    }

    public function testSetAndGetServiceCode(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setServiceCode(ServiceCode::Direct);
        $this->assertEquals(ServiceCode::Direct, $sms->getServiceCode());
    }

    public function testSetAndGetDeliveryAt(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setDeliveryAt((new \DateTime())->setTimestamp(123456789));
        $this->assertEquals(123456789, $sms->getDeliveryAt()?->getTimestamp());
    }

    public function testSetAndGetTextEncoding(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setTextEncoding(TextEncoding::Auto);
        $this->assertEquals(TextEncoding::Auto, $sms->getTextEncoding());
    }

    public function testSetAndGetSource(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setSource(1);
        $this->assertEquals(1, $sms->getSource());
    }

    public function testSetAndGetVoiceLang(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setVoiceLang(VoiceLang::English);
        $this->assertEquals(VoiceLang::English, $sms->getVoiceLang());
    }

    public function testSetAndGetIsDeleteText(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setIsDeleteText(true);
        $this->assertTrue($sms->isDeleteText());
    }

    public function testSetAndGetCallbackData(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setCallbackData('callbackData');
        $this->assertEquals('callbackData', $sms->getCallbackData());
    }

    public function testTextEncodingAutoIsNullInArray(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setTextEncoding(TextEncoding::Auto);
        $this->assertNull($sms->jsonSerialize()['is_unicode']);

    }

    public function testTextEncodingUnicodeIsTrueInArray(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setTextEncoding(TextEncoding::Unicode);
        $this->assertTrue($sms->jsonSerialize()['is_unicode']);
    }

    public function testTextEncodingGsmIsFalseInArray(): void
    {
        $sms = new SmsItemRequest('sender', 'phone', 'text');
        $sms->setTextEncoding(TextEncoding::Gsm);
        $this->assertFalse($sms->jsonSerialize()['is_unicode']);
    }

    // Write similar tests for other setters and getters

    public function testJsonSerialize(): void
    {
        $sms = new SmsItemRequest(
            'sender',
            'phone',
            'message',
            ServiceCode::Direct,
            (new \DateTime())->setTimestamp(123456789),
            TextEncoding::Auto,
            1,
            VoiceLang::English,
            true,
            'callbackData'
        );

        $expectedArray = [
            'sender_id' => 'sender',
            'phone' => 'phone',
            'text' => 'message',
            'service_code' => ServiceCode::Direct->value,
            'is_unicode' => null,
            'delivery_at' => 123456789,
            'source' => 1,
            'voice_lang' => 'EN',
            'is_delete_text' => true,
            'callback_data' => 'callbackData',
        ];
// Failed asserting that '
// {"sender_id":"sender","phone":"phone","text":"message","service_code":"direct","is_unicode":null,"delivery_at":123456789,"voice_lang":"EN","is_delete_text":true,"source":1,"callback_data":"callbackData"}'
// {"sender_id":"sender","phone":"phone","text":"message","service_code":"direct","is_unicode":null,"deliveryAt":123456789,"source":1,"voiceLang":"EN","isDeleteText":true,"callbackData":"callbackData"}".
        $this->assertJsonStringEqualsJsonString(json_encode($expectedArray), json_encode($sms->jsonSerialize()));
    }
}
