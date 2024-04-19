<?php

namespace lox24\api_client_tests\api;

use lox24\api_client\api\TextEncoding;
use lox24\api_client\internals\TextEncodingConverter;
use PHPUnit\Framework\TestCase;

class TextEncodingConverterTest extends TestCase
{

    public function testFromEnum(): void
    {
        $converter = new TextEncodingConverter();
        $this->assertNull($converter->fromEnum(TextEncoding::Auto));
        $this->assertTrue($converter->fromEnum(TextEncoding::Unicode));
        $this->assertFalse($converter->fromEnum(TextEncoding::Gsm));
    }

    public function testToEnum(): void
    {
        $converter = new TextEncodingConverter();

        $this->assertEquals(TextEncoding::Auto, $converter->toEnum(null));
        $this->assertEquals(TextEncoding::Unicode, $converter->toEnum(true));
        $this->assertEquals(TextEncoding::Gsm, $converter->toEnum(false));
    }
}
