<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

use lox24\api_client\api\ServiceCode;
use lox24\api_client\api\TextEncoding;
use lox24\api_client\internals\TextEncodingConverter;

final class SmsItemRequest implements \JsonSerializable
{

    public function __construct(
        private string       $senderId,
        private string       $phone,
        private string       $text,
        private ServiceCode  $serviceCode = ServiceCode::Direct,
        private int          $deliveryAt = 0,
        private TextEncoding $textEncoding = TextEncoding::Auto,
        private ?int         $source = null,
        private ?string      $voiceLang = null,
        private bool         $isDeleteText = false,
        private ?string      $callbackData = null
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

    public function getDeliveryAt(): int
    {
        return $this->deliveryAt;
    }

    public function setDeliveryAt(int $deliveryAt): SmsItemRequest
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

    public function getVoiceLang(): ?string
    {
        return $this->voiceLang;
    }

    public function setVoiceLang(?string $voiceLang): SmsItemRequest
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

    public function jsonSerialize(): array
    {
        $result = [
            'sender_id' => $this->senderId,
            'phone' => $this->phone,
            'text' => $this->text,
            'service_code' => $this->serviceCode->value,
            'is_unicode' => (new TextEncodingConverter())->fromEnum($this->textEncoding),
            'deliveryAt' => $this->deliveryAt,
            'voiceLang' => $this->voiceLang,
            'isDeleteText' => $this->isDeleteText,
        ];

        if ($this->source !== null) {
            $result['source'] = $this->source;
        }

        if($this->callbackData !== null) {
            $result['callbackData'] = $this->callbackData;
        }

        return $result;
    }


}