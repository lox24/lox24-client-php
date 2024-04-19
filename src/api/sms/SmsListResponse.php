<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

use lox24\api_client\ListResponse;

readonly final class SmsListResponse extends ListResponse
{

    protected function getItemClass(): string
    {
        return SmsItemResponse::class;
    }


}