<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

use lox24\api_client\api\ServiceCode;
use lox24\api_client\api\TextEncoding;
use lox24\api_client\api\VoiceLang;
use lox24\api_client\internals\TextEncodingConverter;
use lox24\api_client\Response;

readonly final class SmsItemResponse extends Response
{
    public string $id;
    public ?string $text;
    public string $senderId;
    public string $phone;
    public int $deliveryAt;
    public SmsStatus $statusCode;
    public int $gatewaySentAt;
    public int $source;
    public DlrCode $dlrCode;
    public string $ip;
    public int $createdAt;
    public TextEncoding $textEncoding;
    public ?string $countryCode;
    public ?VoiceLang $voiceLang;
    public int $partsCount;
    public int $charsCount;
    public ?string $callbackData;
    public ?string $bulkId;
    public ?int $keyId;
    public ServiceCode $serviceCode;
    public bool $isTextDeleted;
    public float $price;
    public bool $isSent;
    public ?string $requests;
    public ?string $clicks;

    public function __construct(Response $response)
    {
        parent::__construct($response->getStatus(), $response->getData());
        $this->id = (string)($response->getData()['uuid'] ?? '');
        $text = $response->getData()['text'] ?? null;
        $this->text = is_scalar($text) ? (string)$text : null;
        $this->senderId = (string)($response->getData()['sender_id'] ?? '');
        $this->phone = (string)($response->getData()['phone'] ?? '');
        $this->deliveryAt = (int)($response->getData()['delivery_at'] ?? 0);
        $this->statusCode = SmsStatus::From((int)($response->getData()['status_code'] ?? 0));
        $this->gatewaySentAt = (int)($response->getData()['gateway_sent_at'] ?? 0);
        $this->source = (int)($response->getData()['source'] ?? 0);
        $this->dlrCode = DlrCode::From((int)($response->getData()['dlr_code'] ?? 0));
        $this->ip = (string)($response->getData()['ip'] ?? '');
        $this->createdAt = (int)($response->getData()['created_at'] ?? 0);

        $isUnicode = $response->getData()['is_unicode'] ?? null;
        $this->textEncoding = (new TextEncodingConverter())->toEnum($isUnicode !== null ? (boolean)$isUnicode : null);

        $country = $response->getData()['iso2'] ?? null;
        $this->countryCode = is_scalar($country) ? (string)$country : null;
        $voice = $response->getData()['voice_lang'] ?? null;
        $this->voiceLang = $voice ? VoiceLang::from((string)$voice) : null;
        $this->partsCount = (int)($response->getData()['parts_count'] ?? 0);
        $this->charsCount = (int)($response->getData()['chars_count'] ?? 0);
        $callback = $response->getData()['callback_data'] ?? null;
        $this->callbackData = $callback !== null ? (string)$callback : null;
        $bulkId = $response->getData()['bulk_id'] ?? null;
        $this->bulkId = $bulkId ? (string)$bulkId : null;
        $keyId = $response->getData()['key_id'] ?? null;
        $this->keyId = $keyId ? (int)$keyId : null;
        $this->serviceCode = ServiceCode::from((string)($response->getData()['service_code'] ?? ''));
        $this->isTextDeleted = (bool)($response->getData()['is_text_deleted'] ?? false);
        $this->price = (float)($response->getData()['price'] ?? 0.0);
        $this->isSent = (bool)($response->getData()['is_sent'] ?? false);
        $requestUri = $response->getData()['requests'] ?? null;
        $this->requests = $requestUri ? (string)$requestUri : null;
        $clicksUri = $response->getData()['clicks'] ?? null;
        $this->clicks = $clicksUri ? (string)$clicksUri : null;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function getSenderId(): string
    {
        return $this->senderId;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function getDeliveryAt(): int
    {
        return $this->deliveryAt;
    }

    public function getStatusCode(): SmsStatus
    {
        return $this->statusCode;
    }

    public function getGatewaySentAt(): int
    {
        return $this->gatewaySentAt;
    }

    public function getSource(): int
    {
        return $this->source;
    }

    public function getDlrCode(): DlrCode
    {
        return $this->dlrCode;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function getTextEncoding(): TextEncoding
    {
        return $this->textEncoding;
    }

    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    public function getVoiceLang(): ?VoiceLang
    {
        return $this->voiceLang;
    }

    public function getPartsCount(): ?int
    {
        return $this->partsCount;
    }

    public function getCharsCount(): ?int
    {
        return $this->charsCount;
    }

    public function getCallbackData(): ?string
    {
        return $this->callbackData;
    }

    public function getBulkId(): ?string
    {
        return $this->bulkId;
    }

    public function getKeyId(): ?int
    {
        return $this->keyId;
    }

    public function getServiceCode(): ServiceCode
    {
        return $this->serviceCode;
    }

    public function isTextDeleted(): bool
    {
        return $this->isTextDeleted;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function isSent(): bool
    {
        return $this->isSent;
    }

    public function getRequests(): ?string
    {
        return $this->requests;
    }

    public function getClicks(): ?string
    {
        return $this->clicks;
    }

}