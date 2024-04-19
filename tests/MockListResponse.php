<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use lox24\api_client\ListResponse;

final readonly class MockListResponse extends ListResponse
{
    protected function getItemClass(): string
    {
        return MockItem::class;
    }
}