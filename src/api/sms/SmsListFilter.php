<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

use lox24\api_client\api\TextEncoding;
use lox24\api_client\exceptions\ApiException;
use lox24\api_client\exceptions\ClientException;
use lox24\api_client\exceptions\InvalidArgumentException;
use lox24\api_client\internals\TextEncodingConverter;
use lox24\api_client\ListFilter;

final class SmsListFilter extends ListFilter
{

    /**
     * @throws ApiException
     * @throws ClientException
     */
    public function filterByTextEncoding(TextEncoding $textEncoding): self
    {
        $field = SmsListFilterFields::TextEncoding->value;

        if($textEncoding === TextEncoding::Gsm || $textEncoding === TextEncoding::Unicode) {
            /** @var bool $value */
            $value = (new TextEncodingConverter())->fromEnum($textEncoding);
            $this->boolean($field, $value);
        } else {
            $m = sprintf("Invalid text encoding filter value '%s'. Please use '%s' or '%s'",
                $textEncoding->name, TextEncoding::Gsm->name, TextEncoding::Unicode->name
            );
            InvalidArgumentException::throw($m);
        }

        return $this;
    }

    public function removeFilterByTextEncoding(): self
    {
        $this->removeBoolean(SmsListFilterFields::TextEncoding->value);
        return $this;
    }

    protected function isFieldExists(string $field): bool
    {
        return (bool)SmsListFilterFields::tryFrom($field);
    }

    protected function allowedOrderingFields(): array
    {
        return [
            SmsListFilterFields::CreatedAt->value,
            SmsListFilterFields::GatewaySentAt->value,
        ];
    }

    protected function allowedRangeFields(): array
    {
        return [
            SmsListFilterFields::CreatedAt->value,
            SmsListFilterFields::GatewaySentAt->value,
            SmsListFilterFields::DeliveryAt->value,
            SmsListFilterFields::CharsCount->value,
            SmsListFilterFields::PartsCount->value,
            SmsListFilterFields::Source->value,
        ];
    }

    protected function allowedBooleanFields(): array
    {
        return [
            SmsListFilterFields::IsSent->value,
            SmsListFilterFields::TextEncoding->value,
        ];
    }

    protected function allowedSearchFields(): array
    {
        return [
            SmsListFilterFields::Id->value,
            SmsListFilterFields::PhoneCountry->value,
            SmsListFilterFields::DlrCode->value,
            SmsListFilterFields::StatusCode->value,
            SmsListFilterFields::GatewaySentAt->value,
            SmsListFilterFields::DeliveryAt->value,
            SmsListFilterFields::CreatedAt->value,
            SmsListFilterFields::CharsCount->value,
            SmsListFilterFields::PartsCount->value,
            SmsListFilterFields::Bulk->value,
            SmsListFilterFields::Source->value,
            SmsListFilterFields::Text->value,
            SmsListFilterFields::Phone->value,
            SmsListFilterFields::SenderId->value,
            SmsListFilterFields::Ip->value,
            SmsListFilterFields::KeyId->value,
            SmsListFilterFields::CallbackData->value,
        ];
    }

    protected function allowedExistFields(): array
    {
        return [];
    }


}