<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

enum SmsStatus: int
{
    case New = 0;
    case Sent = 100;
    case Blocked = 208;
    case Canceled = 410;
    case GatewayError = 2000;
    case NoFunds = 3000;
    case SystemError = 5000;

}