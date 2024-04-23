<?php

declare(strict_types=1);

namespace lox24\api_client\exceptions\resource;

use JsonException;
use lox24\api_client\exceptions\ApiException;

final class BadRequest extends ApiException
{


    /**
     * @return array<string, mixed>|null
     */
    public function getDescription(): ?array
    {
        $body = $this->getResponse()?->getBody()->getContents();
        try {
            /** @var array<string, mixed>|null $result */
            $result = $body ? (array)json_decode($body, true, 512, JSON_THROW_ON_ERROR) : null;
        } catch (JsonException) {
            $result = null;
        }

        return $result;
    }


}