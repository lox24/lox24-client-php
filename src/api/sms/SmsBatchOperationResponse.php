<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

use lox24\api_client\Response;

final readonly class SmsBatchOperationResponse extends Response
{

    public function __construct(private Response $response)
    {
        parent::__construct($response->getStatus(), $response->getData());
    }

    public function getAffectedRows(): int
    {
        if(!$this->response->getData()) {
            return 0;
        }

        $value = array_values($this->response->getData())[0] ?? 0;

        if(!is_scalar($value)) {
            return 0;
        }

        return (int)$value;
    }


}