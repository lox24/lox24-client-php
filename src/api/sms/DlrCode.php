<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

enum DlrCode: int
{
    case Undefined = 0;
    case Ok = 1;
    case Queue = 2;
    case SubmitAck = 4;
    case Expired = 8;
    case Rejected = 16;

}