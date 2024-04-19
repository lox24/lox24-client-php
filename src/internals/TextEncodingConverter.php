<?php

declare(strict_types=1);

namespace lox24\api_client\internals;

use lox24\api_client\api\TextEncoding;

readonly final class TextEncodingConverter
{

    public function fromEnum(TextEncoding $textEncoding): ?bool
    {
        $isUnicode = $textEncoding === TextEncoding::Unicode;
        return !$isUnicode && $textEncoding === TextEncoding::Auto ? null : $isUnicode;
    }

    public function toEnum(?bool $value): TextEncoding
    {
        return match ($value) {
            true => TextEncoding::Unicode,
            false => TextEncoding::Gsm,
            default => TextEncoding::Auto,
        };
    }


}