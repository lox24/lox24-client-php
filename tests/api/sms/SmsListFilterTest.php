<?php

declare(strict_types=1);

namespace lox24\api_client_tests\api\sms;

use lox24\api_client\api\sms\SmsListFilter;
use lox24\api_client\api\sms\SmsListFilterFields;
use lox24\api_client\api\TextEncoding;
use lox24\api_client\exceptions\ApiException;
use lox24\api_client\exceptions\ClientException;
use lox24\api_client\exceptions\InvalidArgumentException;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(SmsListFilter::class)]
class SmsListFilterTest extends TestCase
{
    /**
     * @throws ApiException
     * @throws ClientException
     */
    public function testAllowedFields(): void
    {
        $this->expectNotToPerformAssertions();
        $filter = new SmsListFilter();
        $filter->orderBy(SmsListFilterFields::CreatedAt->value);
        $filter->orderBy(SmsListFilterFields::GatewaySentAt->value);
        $filter->search(SmsListFilterFields::Text->value, 'test');
        $filter->search(SmsListFilterFields::Id->value, '11112222-3333-4444-5555-666677778888');
        $filter->search(SmsListFilterFields::PhoneCountry->value, 'US');
        $filter->search(SmsListFilterFields::DlrCode->value, 16);
        $filter->search(SmsListFilterFields::StatusCode->value, 0);
        $filter->search(SmsListFilterFields::GatewaySentAt->value, 1342424223);
        $filter->search(SmsListFilterFields::DeliveryAt->value, 0);
        $filter->search(SmsListFilterFields::CreatedAt->value, 1342424223);
        $filter->search(SmsListFilterFields::CharsCount->value, 16);
        $filter->search(SmsListFilterFields::PartsCount->value, 1);
        $filter->search(SmsListFilterFields::Bulk->value, '66112222-3333-4444-5555-666677778888');
        $filter->search(SmsListFilterFields::Source->value, 454353453);
        $filter->search(SmsListFilterFields::Text->value, 'test');
        $filter->search(SmsListFilterFields::Phone->value, '+1223423443545');
        $filter->search(SmsListFilterFields::SenderId->value, 'foo');
        $filter->search(SmsListFilterFields::Ip->value, '1.1.1.1');
        $filter->search(SmsListFilterFields::KeyId->value, 13123);
        $filter->search(SmsListFilterFields::CallbackData->value, 'some code');
        $filter->between(SmsListFilterFields::CreatedAt->value, 1342424223, 1342424223);
        $filter->between(SmsListFilterFields::GatewaySentAt->value, 1342424223, 1342424223);
        $filter->between(SmsListFilterFields::DeliveryAt->value, 1342424223, 1342424223);
        $filter->between(SmsListFilterFields::CharsCount->value, 1, 100);
        $filter->between(SmsListFilterFields::PartsCount->value, 1, 100);
        $filter->between(SmsListFilterFields::Source->value, 1, 100);
        $filter->boolean(SmsListFilterFields::IsSent->value, true);

    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testFilterByTextEncodingGsm(): void
    {
        $filter = new SmsListFilter();
        $filter->filterByTextEncoding(TextEncoding::Gsm);
        $this->assertSame('is_unicode=0', (string)$filter);
        $this->assertSame(['is_unicode' => 0], $filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testFilterByTextEncodingUnicode(): void
    {
        $filter = new SmsListFilter();
        $filter->filterByTextEncoding(TextEncoding::Unicode);
        $this->assertSame('is_unicode=1', (string)$filter);
        $this->assertSame(['is_unicode' => 1], $filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testFilterByTextEncodingAuto(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $filter = new SmsListFilter();
        $filter->filterByTextEncoding(TextEncoding::Auto);

    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testRemoveFilterByTextEncoding(): void
    {
        $filter = new SmsListFilter();
        $filter->filterByTextEncoding(TextEncoding::Unicode);
        $filter->removeFilterByTextEncoding();
        $this->assertEmpty($filter->jsonSerialize());

    }


    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testExistsFilter(): void
    {
        $this->expectException(ClientException::class);
        $filter = new SmsListFilter();
        $filter->exist('some_field');

    }

}
