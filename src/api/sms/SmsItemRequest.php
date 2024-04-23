<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

use DateTimeInterface;
use JsonSerializable;
use lox24\api_client\api\ServiceCode;
use lox24\api_client\api\TextEncoding;
use lox24\api_client\api\VoiceLang;
use lox24\api_client\internals\TextEncodingConverter;

final class SmsItemRequest implements JsonSerializable
{

    public function __construct(
        private string             $senderId,
        private string             $phone,
        private string             $text,
        private ServiceCode        $serviceCode = ServiceCode::Direct,
        private ?DateTimeInterface $deliveryAt = null,
        private TextEncoding       $textEncoding = TextEncoding::Auto,
        private ?int               $source = null,
        private ?VoiceLang         $voiceLang = null,
        private bool               $isDeleteText = false,
        private ?string            $callbackData = null
    ) {
    }

    public function getSenderId(): string
    {
        return $this->senderId;
    }

    public function setSenderId(string $senderId): SmsItemRequest
    {
        $this->senderId = $senderId;

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): SmsItemRequest
    {
        $this->phone = $phone;

        return $this;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): SmsItemRequest
    {
        $this->text = $text;

        return $this;
    }

    public function getServiceCode(): ServiceCode
    {
        return $this->serviceCode;
    }

    public function setServiceCode(ServiceCode $serviceCode): SmsItemRequest
    {
        $this->serviceCode = $serviceCode;

        return $this;
    }

    public function getDeliveryAt(): ?DateTimeInterface
    {
        return $this->deliveryAt;
    }

    public function setDeliveryAt(?DateTimeInterface $deliveryAt): SmsItemRequest
    {
        $this->deliveryAt = $deliveryAt;

        return $this;
    }

    public function getTextEncoding(): TextEncoding
    {
        return $this->textEncoding;
    }

    public function setTextEncoding(TextEncoding $textEncoding): SmsItemRequest
    {
        $this->textEncoding = $textEncoding;

        return $this;
    }

    public function getSource(): ?int
    {
        return $this->source;
    }

    public function setSource(?int $source): SmsItemRequest
    {
        $this->source = $source;

        return $this;
    }

    public function getVoiceLang(): ?VoiceLang
    {
        return $this->voiceLang;
    }

    public function setVoiceLang(?VoiceLang $voiceLang): SmsItemRequest
    {
        $this->voiceLang = $voiceLang;

        return $this;
    }

    public function isDeleteText(): bool
    {
        return $this->isDeleteText;
    }

    public function setIsDeleteText(bool $isDeleteText): SmsItemRequest
    {
        $this->isDeleteText = $isDeleteText;

        return $this;
    }

    public function getCallbackData(): ?string
    {
        return $this->callbackData;
    }

    public function setCallbackData(?string $callbackData): SmsItemRequest
    {
        $this->callbackData = $callbackData;

        return $this;
    }

    /**
     * @return array<string, bool|int|string|null>
     */
    public function jsonSerialize(): array
    {
        $result = [
            'sender_id' => $this->senderId,
            'phone' => $this->phone,
            'text' => $this->text,
            'service_code' => $this->serviceCode->value,
            'is_unicode' => (new TextEncodingConverter())->fromEnum($this->textEncoding),
            'delivery_at' => $this->deliveryAt?->getTimestamp() ?: 0,
            'voice_lang' => $this->voiceLang->value ?? null,
            'is_delete_text' => $this->isDeleteText,
        ];

        if ($this->source !== null) {
            $result['source'] = $this->source;
        }

        if ($this->callbackData !== null) {
            $result['callback_data'] = $this->callbackData;
        }

        return $result;
    }


}