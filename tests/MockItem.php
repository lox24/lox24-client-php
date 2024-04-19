<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use lox24\api_client\Response;

class MockItem
{
    public function __construct(private Response $r) {}
}