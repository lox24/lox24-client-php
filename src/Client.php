<?php

declare(strict_types=1);

namespace lox24\api_client;

use JsonException;
use lox24\api_client\exceptions\access\AccessException;
use lox24\api_client\exceptions\access\AccountBlocked;
use lox24\api_client\exceptions\access\InvalidCredentials;
use lox24\api_client\exceptions\access\IpBlocked;
use lox24\api_client\exceptions\access\NotEnoughFundsPerOperation;
use lox24\api_client\exceptions\access\TwoManyRequests;
use lox24\api_client\exceptions\ApiException;
use lox24\api_client\exceptions\ClientException;
use lox24\api_client\exceptions\InvalidArgumentException;
use lox24\api_client\exceptions\resource\BadRequest;
use lox24\api_client\exceptions\resource\ResourceNotFound;
use lox24\api_client\exceptions\resource\UnprocessableRequest;
use lox24\api_client\exceptions\service\RequestException;
use lox24\api_client\exceptions\service\ServiceException;
use lox24\api_client\exceptions\service\UnexpectedException;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Throwable;

readonly final class Client
{
    private const AUTH_HEADER = 'X-LOX24-AUTH-TOKEN';
    private const USER_AGENT = 'LOX24-API-CLIENT 0.1';

    public function __construct(
        private ClientInterface         $client,
        private RequestFactoryInterface $requestFactory,
        private Config                  $config
    ) {
    }


    /**
     * @throws UnexpectedException
     * @throws InvalidCredentials
     * @throws TwoManyRequests
     * @throws ApiException
     * @throws ClientException
     * @throws RequestException
     */
    public function send(Request $r): ?Response
    {
        $response = null;
        try {
            $response = $this->sendRequest($r);
        } catch (ApiException $e) {
            throw $e;
        } catch (JsonException $e) {
            throw new RequestException("An error occurred while sending the message: ".$e->getMessage());
        } catch (Throwable $e) {
            throw (new ClientException("Unexpected error: {$e->getMessage()}", 0, $e))
                ->setResponse($response);
        }

        return $response;
    }

    /**
     * @throws ApiException
     * @throws AccessException|AccountBlocked|InvalidCredentials|IpBlocked|NotEnoughFundsPerOperation|TwoManyRequests
     * @throws RequestException|BadRequest|ResourceNotFound|UnprocessableRequest
     * @throws ServiceException|RequestException|UnexpectedException
     * @throws ClientException|InvalidArgumentException
     */
    private function sendRequest(Request $r): Response
    {
        $request = $this->requestFactory->createRequest(
            $r->getMethod()->value,
            $this->config->getApiUrl().$r->getUri(),
        );

        $request = $request->withHeader(self::AUTH_HEADER, $this->config->getToken());
        $request = $request->withHeader('Accept', 'application/json+ld');
        $request = $request->withHeader('Content-Type', 'application/json');
        $request = $request->withHeader('User-Agent', self::USER_AGENT);

        try {
            if ($r->getData() !== null) {
                $request->getBody()->write(json_encode($r->getData(), JSON_THROW_ON_ERROR));
            }
            $response = $this->client->sendRequest($request);
        } catch (ClientExceptionInterface|JsonException $e) {
            new ClientException("An error occurred while sending the message: ".$e->getMessage(), 0, $e);
        }

        $this->processStatusCode($response);
        $content = (string)$response->getBody();
        try {
            $data = $content ? json_decode($content, true, 512, JSON_THROW_ON_ERROR) : null;
        } catch (JsonException $e) {
            throw new UnexpectedException("An error occurred while decoding the response: ".$e->getMessage());
        }

        return new Response($response->getStatusCode(), $data);
    }

    /**
     * @throws UnexpectedException
     * @throws InvalidCredentials
     * @throws TwoManyRequests
     * @throws ApiException
     */
    private function processStatusCode(ResponseInterface $response): void
    {
        $code = $response->getStatusCode();
        $content = $response->getBody()->getContents();
        match (true) {
            $code < 400 => null,
            $code === 400 => BadRequest::throw("Bad request: $content", $response),
            $code === 401 => InvalidCredentials::throw("Invalid credentials: $content", $response),
            $code === 402 => NotEnoughFundsPerOperation::throw("Not enough funds per operation: $content", $response),
            $code === 403 => AccountBlocked::throw("Account blocked or API key is not active: $content", $response),
            $code === 404 => ResourceNotFound::throw("Resource not found: $content", $response),
            $code === 422 => UnprocessableRequest::throw("Request's data unprocessable: $content", $response),
            $code === 429 => TwoManyRequests::throw("Two many requests: $content", $response),
            $code >= 500 => UnexpectedException::throw("Server error: [$code] $content", $response),
            default => RequestException::throw("An error occurred: [$code] $content", $response),
        };
    }


}