<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

use lox24\api_client\api\ServiceCode;
use lox24\api_client\api\TextEncoding;
use lox24\api_client\internals\TextEncodingConverter;
use lox24\api_client\Response;

readonly final class SmsItemResponse extends Response
{
    public string $id;
    public ?string $text;
    public string $senderId;
    public string $phone;
    public int $deliveryAt;
    public int $statusCode;
    public int $gatewaySentAt;
    public int $source;
    public int $dlrCode;
    public string $ip;
    public int $createdAt;
    public TextEncoding $textEncoding;
    public ?string $iso2;
    public ?string $voiceLang;
    public ?int $partsCount;
    public ?int $charsCount;
    public ?string $callbackData;
    public ?string $bulkId;
    public ?int $keyId;
    public ServiceCode $serviceCode;
    public bool $isTextDeleted;
    public ?float $price;
    public bool $isSent;
    public ?string $requests;
    public ?string $clicks;

    public function __construct(Response $response)
    {
        parent::__construct($response->getStatus(), $response->getData());
        $this->id = $response->getData()['uuid'] ?? '';
        $this->text = $response->getData()['text'] ?? null;
        $this->senderId = $response->getData()['sender_id'] ?? '';
        $this->phone = $response->getData()['phone'] ?? '';
        $this->deliveryAt = $response->getData()['delivery_at'] ?? 0;
        $this->statusCode = $response->getData()['status_code'] ?? 0;
        $this->gatewaySentAt = $response->getData()['gateway_sent_at'] ?? 0;
        $this->source = $response->getData()['source'] ?? 0;
        $this->dlrCode = $response->getData()['dlr_code'] ?? 0;
        $this->ip = $response->getData()['ip'] ?? '';
        $this->createdAt = $response->getData()['created_at'] ?? 0;
        $this->textEncoding = (new TextEncodingConverter())->toEnum($response->getData()['is_unicode'] ?? null);
        $this->iso2 = $response->getData()['iso2'] ?? null;
        $this->voiceLang = $response->getData()['voice_lang'] ?? null;
        $this->partsCount = $response->getData()['parts_count'] ?? null;
        $this->charsCount = $response->getData()['chars_count'] ?? null;
        $this->callbackData = $response->getData()['callback_data'] ?? null;
        $this->bulkId = $response->getData()['bulk_id'] ?? null;
        $this->keyId = $response->getData()['key_id'] ?? null;
        $this->serviceCode = ServiceCode::from($response->getData()['service_code']);
        $this->isTextDeleted = (bool)($response->getData()['is_text_deleted'] ?? false);
        $this->price = $response->getData()['price'] ?? null;
        $this->isSent = (bool)($response->getData()['is_sent'] ?? false);
        $this->requests = $response->getData()['requests'] ?? null;
        $this->clicks = $response->getData()['clicks'] ?? null;
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

    public function getStatusCode(): int
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

    public function getDlrCode(): int
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

    public function getIso2(): ?string
    {
        return $this->iso2;
    }

    public function getVoiceLang(): ?string
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