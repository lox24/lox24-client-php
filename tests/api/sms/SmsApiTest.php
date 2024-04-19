<?php

declare(strict_types=1);

namespace lox24\api_client_tests\api\sms;

use lox24\api_client\api\sms\SmsApi;
use lox24\api_client\api\sms\SmsItemRequest;
use lox24\api_client\api\sms\SmsListFilter;
use lox24\api_client\Client;
use lox24\api_client\exceptions\access\InvalidCredentials;
use lox24\api_client\exceptions\access\TwoManyRequests;
use lox24\api_client\exceptions\ApiException;
use lox24\api_client\exceptions\ClientException;
use lox24\api_client\exceptions\service\RequestException;
use lox24\api_client\exceptions\service\UnexpectedException;
use lox24\api_client\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(SmsApi::class)]
final class SmsApiTest extends TestCase
{
    private MockObject|Client $clientMock;
    private SmsApi $smsApi;

    protected function setUp(): void
    {
        // Mock the Client dependency
        $this->clientMock = $this->createMock(Client::class);
        $this->smsApi = new SmsApi($this->clientMock);
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws UnexpectedException
     * @throws RequestException
     */
    public function testGetSmsListSuccess(): void
    {
        // Simulate a successful response
        $expectedResponse = new Response(200, $this->listData());
        $this->clientMock->method('send')->willReturn($expectedResponse);

        $filter = new SmsListFilter();
        $response = $this->smsApi->getSmsList($filter);

        $this->assertSame(200, $response->getTotalItems());
        $this->assertSame('f5e0b24a-cd64-11ed-8d61-7c10c91d54a5', $response->getData()[0]->getId());
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws RequestException
     */
    public function testGetSmsListThrowsUnexpectedExceptionOnEmptyResponse(): void
    {
        $this->expectException(UnexpectedException::class);
        $this->expectExceptionMessage('Response is empty');

        $this->clientMock->method('send')->willReturn(null);

        $this->smsApi->getSmsList();
    }

    /**
     * @throws TwoManyRequests
     * @throws InvalidCredentials
     * @throws ClientException
     * @throws UnexpectedException
     * @throws ApiException
     * @throws RequestException
     */
    public function testGetSmsItemSuccess(): void
    {
        // Simulate a successful response
        $expectedResponse = new Response(200, $this->itemData());
        $this->clientMock->method('send')->willReturn($expectedResponse);

        $response = $this->smsApi->getSmsItem('f5e0b24a-cd64-11ed-8d61-7c10c91d54a5');

        $this->assertSame('f5e0b24a-cd64-11ed-8d61-7c10c91d54a5', $response->getId());
    }

    /**
     * @throws TwoManyRequests
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws ClientException
     * @throws RequestException
     */
    public function testGetSmsItemThrowsUnexpectedExceptionOnEmptyResponse(): void
    {
        $this->expectException(UnexpectedException::class);
        $this->expectExceptionMessage('Response is empty');

        $this->clientMock->method('send')->willReturn(null);

        $this->smsApi->getSmsItem('f5e0b24a-cd64-11ed-8d61-7c10c91d54a5');
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws UnexpectedException
     * @throws RequestException
     */
    public function testDeleteSmsItemSuccess(): void
    {
        $this->expectNotToPerformAssertions();
        $expectedResponse = new Response(204, null);
        $this->clientMock->method('send')->willReturn($expectedResponse);

        $this->smsApi->deleteSmsItem('f5e0b24a-cd64-11ed-8d61-7c10c91d54a5');
    }

    /**
     * @throws TwoManyRequests
     * @throws InvalidCredentials
     * @throws ApiException
     * @throws ClientException
     * @throws RequestException
     */
    public function testDeleteSmsItemNoResponse(): void
    {
        $this->expectException(UnexpectedException::class);
        $this->clientMock->method('send')->willReturn(null);
        $this->smsApi->deleteSmsItem('f5e0b24a-cd64-11ed-8d61-7c10c91d54a5');
    }

    /**
     * @throws TwoManyRequests
     * @throws InvalidCredentials
     * @throws ApiException
     * @throws ClientException
     * @throws RequestException
     */
    public function testDeleteSmsItemResponseStatusCodeNot204(): void
    {
        $this->expectException(UnexpectedException::class);
        $expectedResponse = new Response(301, null);
        $this->clientMock->method('send')->willReturn($expectedResponse);
        $this->smsApi->deleteSmsItem('f5e0b24a-cd64-11ed-8d61-7c10c91d54a5');
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws InvalidCredentials
     * @throws ApiException
     * @throws UnexpectedException
     * @throws RequestException
     */
    public function testSendSmsRealSuccess(): void
    {
        $expectedResponse = new Response(201, $this->itemData());
        $this->clientMock->method('send')->willReturn($expectedResponse);

        $response = $this->smsApi->sendSms(new SmsItemRequest('sender', '+11232313131', 'text'));
        $this->assertSame('f5e0b24a-cd64-11ed-8d61-7c10c91d54a5', $response->getId());
    }

    /**
     * @throws TwoManyRequests
     * @throws InvalidCredentials
     * @throws UnexpectedException
     * @throws ApiException
     * @throws ClientException
     * @throws RequestException
     */
    public function testSendSmsDryRunSuccess(): void
    {
        $expectedResponse = new Response(201, $this->itemData());
        $this->clientMock->method('send')->willReturn($expectedResponse);

        $response = $this->smsApi->sendSms(new SmsItemRequest('sender', '+11232313131', 'text'), true);
        $this->assertSame('f5e0b24a-cd64-11ed-8d61-7c10c91d54a5', $response->getId());
    }

    /**
     * @throws TwoManyRequests
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws ClientException
     * @throws RequestException
     */
    public function testSendSmsEmptyResponse(): void
    {
        $this->expectException(UnexpectedException::class);
        $this->clientMock->method('send')->willReturn(null);
        $this->smsApi->sendSms(new SmsItemRequest('sender', '+11232313131', 'text'));
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws UnexpectedException
     * @throws RequestException
     */
    public function testCancelSmsListSuccess(): void
    {
        $expectedResponse = new Response(200, ['canceled_sms_count' => 20]);
        $this->clientMock->method('send')->willReturn($expectedResponse);

        $count = $this->smsApi->cancelSmsList();
        $this->assertSame(20, $count);
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws InvalidCredentials
     * @throws ApiException
     * @throws RequestException
     */
    public function testCancelSmsListWithEmptyResponse(): void
    {
        $this->expectException(UnexpectedException::class);
        $this->clientMock->method('send')->willReturn(null);

        $this->smsApi->cancelSmsList();
    }


    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws UnexpectedException
     * @throws RequestException
     */
    public function testDeleteSmsListSuccess(): void
    {
        $expectedResponse = new Response(200, ['deleted_sms_count' => 20]);
        $this->clientMock->method('send')->willReturn($expectedResponse);

        $count = $this->smsApi->deleteSmsList();
        $this->assertSame(20, $count);
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws InvalidCredentials
     * @throws ApiException
     * @throws RequestException
     */
    public function testDeleteSmsListWithEmptyResponse(): void
    {
        $this->expectException(UnexpectedException::class);
        $this->clientMock->method('send')->willReturn(null);

        $this->smsApi->deleteSmsList();
    }


    private function listData(): array
    {
        return [
            'hydra:totalItems' => 200,
            'hydra:member' => [
                $this->itemData(),
            ],
        ];
    }

    private function itemData(): array
    {
        return [
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
            'clicks' => '/sms/f5e0b24a-cd64-11ed-8d61-7c10c91d54a5/clicks',
        ];
    }

    // Additional tests for getSmsItem, deleteSmsItem, sendSms, cancelSmsList, deleteSmsList...
}
