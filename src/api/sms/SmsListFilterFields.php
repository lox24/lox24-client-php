<?php

namespace lox24\api_client\api\sms;

enum SmsListFilterFields: string
{
    case CreatedAt = 'created_at';
    case GatewaySentAt = 'gateway_sent_at';
    case DeliveryAt = 'delivery_at';
    case CharsCount = 'chars_count';
    case PartsCount = 'parts_count';
    case Source = 'source';
    case IsSent = 'is_sent';
    case TextEncoding = 'is_unicode';
    case Id = 'uuid';
    case PhoneCountry = 'iso2';
    case DlrCode = 'dlr_code';
    case StatusCode = 'status_code';
    case Bulk = 'bulk';
    case Text = 'text';
    case Phone = 'phone';
    case SenderId = 'sender_id';
    case Ip = 'ip';
    case KeyId = 'key_id';
    case CallbackData = 'callback_data';

}

