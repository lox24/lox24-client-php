<?php

declare(strict_types=1);

namespace lox24\api_client\api;

use lox24\api_client\api\sms\SmsApi;
use lox24\api_client\Client;

final class Lox24Api
{
    private ?SmsApi $sms = null;
    public function __construct(private readonly Client $client)
    {
    }

    public function sms(): SmsApi
    {
        if(!$this->sms) {
            $this->sms = new SmsApi($this->client);
        }
        return $this->sms;
    }
}