<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use lox24\api_client\ListFilter;

class MockListFilter extends ListFilter
{
    protected function isFieldExists(string $field): bool
    {
        return in_array($field, ['name', 'date', 'age', 'score', 'active', 'verified', 'query', 'text', 'photo', 'bio']);
    }

    protected function allowedOrderingFields(): array
    {
        return ['name', 'date'];
    }

    protected function allowedRangeFields(): array
    {
        return ['age', 'score'];
    }

    protected function allowedBooleanFields(): array
    {
        return ['active', 'verified'];
    }

    protected function allowedSearchFields(): array
    {
        return ['query', 'text'];
    }

    protected function allowedExistFields(): array
    {
        return ['photo', 'bio'];
    }
}