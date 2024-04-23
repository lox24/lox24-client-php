<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use JsonException;
use lox24\api_client\api\sms\SmsItemRequest;
use lox24\api_client\Client;
use lox24\api_client\Config;
use lox24\api_client\Endpoint;
use lox24\api_client\exceptions\access\AccountBlocked;
use lox24\api_client\exceptions\access\InvalidCredentials;
use lox24\api_client\exceptions\access\NotEnoughFundsPerOperation;
use lox24\api_client\exceptions\access\TwoManyRequests;
use lox24\api_client\exceptions\ApiException;
use lox24\api_client\exceptions\ClientException;
use lox24\api_client\exceptions\resource\BadRequest;
use lox24\api_client\exceptions\resource\ResourceNotFound;
use lox24\api_client\exceptions\resource\UnprocessableRequest;
use lox24\api_client\exceptions\service\RequestException;
use lox24\api_client\exceptions\service\UnexpectedException;
use lox24\api_client\Method;
use lox24\api_client\Request;
use lox24\api_client\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

class ClientTest extends TestCase
{
    private MockObject|ClientInterface $httpClientMock;
    private MockObject|RequestFactoryInterface $requestFactoryMock;
    private MockObject|RequestInterface $requestMock;
    private MockObject|ResponseInterface $responseMock;
    private MockObject|StreamInterface $streamMock;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->httpClientMock = $this->createMock(ClientInterface::class);
        $this->requestFactoryMock = $this->createMock(RequestFactoryInterface::class);
        $this->requestMock = $this->createMock(RequestInterface::class);
        $this->responseMock = $this->createMock(ResponseInterface::class);
        $this->streamMock = $this->createMock(StreamInterface::class);
    }

    /**
     * @throws ClientException
     * @throws JsonException
     * @throws TwoManyRequests
     * @throws InvalidCredentials
     * @throws UnexpectedException
     * @throws ApiException
     * @throws RequestException
     */
    public function testSendSuccessfulRequest(): void
    {
        $config = new Config('test-token', 'https://api.lox24.eu');
        $client = new Client($this->httpClientMock, $this->requestFactoryMock, $config);

        $request = new Request(new Endpoint(Method::GET, '/test'));
        $responseData = ['success' => true];

        $this->requestFactoryMock->method('createRequest')
                                 ->willReturn($this->requestMock);

        $this->requestMock->method('withHeader')->willReturnSelf();
        $this->requestMock->method('getBody')->willReturn(
            $this->createMock(StreamInterface::class)
        );

        $responseStr = json_encode($responseData, JSON_THROW_ON_ERROR);
        $this->streamMock->method('getContents')->willReturn($responseStr);
        $this->streamMock->method('__toString')->willReturn($responseStr);

        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);

        $this->httpClientMock->method('sendRequest')->willReturn($this->responseMock);

        $response = $client->send($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(200, $response->getStatus());
        $this->assertEquals($responseData, $response->getData());
    }

    /**
     * @throws ClientException
     * @throws JsonException
     * @throws TwoManyRequests
     * @throws InvalidCredentials
     * @throws UnexpectedException
     * @throws ApiException
     * @throws RequestException
     */
    public function testRequestDataPassedSuccessToHttpRequest()
    {
        $config = new Config('test-token', 'https://api.lox24.eu');
        $client = new Client($this->httpClientMock, $this->requestFactoryMock, $config);


        $item = new SmsItemRequest('sender', '+12345566899', 'message');
        $requestData = new Request(new Endpoint(Method::GET, '/test'), $item);
        $responseData = ['success' => true];

        $this->requestFactoryMock->method('createRequest')
                                 ->willReturn($this->requestMock);

        $this->requestMock->method('withHeader')->willReturnSelf();
        $this->streamMock->expects($this->once())
                         ->method('write')
                         ->with($this->equalTo(json_encode($item, JSON_THROW_ON_ERROR)));
        $this->requestMock->method('getBody')->willReturn($this->streamMock);

        $this->httpClientMock->method('sendRequest')->willReturn($this->responseMock);

        $this->responseMock->method('getStatusCode')->willReturn(200);
        $this->responseMock->method('getBody')->willReturn($this->streamMock);
        $this->streamMock->method('getContents')->willReturn(json_encode($responseData, JSON_THROW_ON_ERROR));

        $client->send($requestData);
    }

    /**
     * @throws ClientException
     * @throws JsonException
     * @throws TwoManyRequests
     * @throws InvalidCredentials
     * @throws UnexpectedException
     * @throws ApiException
     * @throws RequestException
     */
    #[DataProvider('exceptionByResponseCode')]
    public function testExceptionByResponseCode(int $code, ?string $exception): void
    {
        if ($exception === null) {
            $this->expectNotToPerformAssertions();
        } else {
            $this->expectException($exception);
        }

        $config = new Config('invalid-token', 'https://api.lox24.eu');
        $client = new Client($this->httpClientMock, $this->requestFactoryMock, $config);

        $requestData = new Request(new Endpoint(Method::GET, '/test'));

        $this->requestFactoryMock->method('createRequest')->willReturn($this->requestMock);
        $this->requestMock->method('withHeader')->willReturnSelf();
        $this->httpClientMock->method('sendRequest')->willReturn($this->responseMock);

        $this->responseMock->method('getStatusCode')->willReturn($code);

        $this->streamMock->method('getContents')->willReturn(json_encode([], JSON_THROW_ON_ERROR));

        $client->send($requestData);
    }


    public static function exceptionByResponseCode(): array
    {
        $data = [];
        foreach (range(400, 499) as $i) {
            $data[$i] = ['code' => $i, 'exception' => RequestException::class];
        }

        $data[201] = ['code' => 201, 'exception' => null];
        $data[204] =    ['code' => 204, 'exception' => null];
        $data[400] =    ['code' => 400, 'exception' => BadRequest::class];
        $data[401] =    ['code' => 401, 'exception' => InvalidCredentials::class];
        $data[402] =    ['code' => 402, 'exception' => NotEnoughFundsPerOperation::class];
        $data[403] =    ['code' => 403, 'exception' => AccountBlocked::class];
        $data[404] =    ['code' => 404, 'exception' => ResourceNotFound::class];
        $data[422] =    ['code' => 422, 'exception' => UnprocessableRequest::class];
        $data[429] =    ['code' => 429, 'exception' => TwoManyRequests::class];
        $data[500] =    ['code' => 500, 'exception' => ApiException::class];
        $data[503] =    ['code' => 503, 'exception' => UnexpectedException::class];

        return $data;
    }


    /**
     * @throws TwoManyRequests
     * @throws UnexpectedException
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws RequestException
     */
    public function testThrowableExceptions(): void
    {
        $this->expectException(ClientException::class);
        $this->requestFactoryMock->method('createRequest')->willThrowException(new \Exception());
        $config = new Config('token', 'https://api.lox24.eu');
        $client = new Client($this->httpClientMock, $this->requestFactoryMock, $config);
        $requestData = new Request(new Endpoint(Method::GET, '/test'));
        $client->send($requestData);
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws InvalidCredentials
     * @throws ApiException
     * @throws UnexpectedException
     */
    public function testApiExceptions(): void
    {
        $this->expectException(InvalidCredentials::class);
        $this->requestFactoryMock->method('createRequest')->willThrowException(
            new InvalidCredentials('InvalidCredentials')
        );
        $config = new Config('token', 'https://api.lox24.eu');
        $client = new Client($this->httpClientMock, $this->requestFactoryMock, $config);
        $requestData = new Request(new Endpoint(Method::GET, '/test'));
        $client->send($requestData);
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws InvalidCredentials
     * @throws ApiException
     * @throws UnexpectedException
     */
    public function testJsonExceptions(): void
    {
        $this->expectException(ClientException::class);
        $this->requestFactoryMock->method('createRequest')->willThrowException(new JsonException());
        $config = new Config('token', 'https://api.lox24.eu');
        $client = new Client($this->httpClientMock, $this->requestFactoryMock, $config);
        $requestData = new Request(new Endpoint(Method::GET, '/test'));
        $client->send($requestData);
    }

}
