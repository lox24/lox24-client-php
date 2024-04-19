<?php

declare(strict_types=1);

namespace lox24\api_client\exceptions;

use Psr\Http\Message\ResponseInterface;

abstract class ApiException extends \Exception
{
    private ?ResponseInterface $response = null;

    final public function __construct(string $message, int $code = 0, ?\Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @throws ApiException|static
     */
    public static function throw(string $message, ?ResponseInterface $response = null): void
    {
        throw (new static($message, 0, null))->setResponse($response);
    }


    public function getResponse(): ?ResponseInterface
    {
        return $this->response;
    }

    public function setResponse(?ResponseInterface $response): self
    {
        $this->response = $response;
        return $this;
    }


}