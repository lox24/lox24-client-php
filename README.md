# LOX24 SMS Gateway PHP Client

## Documentation

The documentation for the LOX24 API can be found [here][https://doc.lox24.eu].

## Versions

`lox24-client-php` uses [Semantic Versioning](https://semver.org) for all changes. [See this document](VERSIONS.md) for details.

### Supported PHP Versions

This library supports the following PHP implementations:

- PHP 8.2
- PHP 8.3

## Installation

### Compatible PSR-7 and PSR-17 Libraries

This project is compatible with a range of libraries adhering to the [PSR-17](https://www.php-fig.org/psr/psr-17/) and [PSR-18](https://www.php-fig.org/psr/psr-18/) standards. 
Below is a list of well-known libraries that provide robust support for these standards, 
ensuring flexibility and interoperability in your application's HTTP client implementation.

You could use your own or any PSR-17/PSR-18 compatible libraries, e.g.:

- [GuzzleHttp](https://github.com/guzzle/guzzle)
- [Symfony/http-client](https://symfony.com/doc/current/http_client.html#psr-18-and-psr-17)


### Install with Composer
`lox24-client-php` is available on Packagist as the [`lox24/sdk`](https://packagist.org/packages/lox24/sdk) package:

```shell
composer require lox24/sdk
```

### Usage example with Guzzle HTTP client

Add Guzzle HTTP client implementation to your project:

```shell
composer require lox24/sdk
```

Example of the script which sends an SMS using LOX24's REST API and PHP:

```php
// Send an SMS using LOX24's REST API and PHP
<?php

include 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory as GuzzleRequestFactory;
use lox24\api_client\api\Lox24Api;
use lox24\api_client\api\sms\SmsItemRequest;
use lox24\api_client\Client;
use lox24\api_client\Config;
use lox24\api_client\exceptions\access\AccessException;
use lox24\api_client\exceptions\ApiException;
use lox24\api_client\exceptions\ClientException;
use lox24\api_client\exceptions\resource\ResourceException;
use lox24\api_client\exceptions\service\ServiceException;

$token = 'your_token_here';
$config = new Config($token);
$httpClient = new GuzzleClient();
$requestFactory = new GuzzleRequestFactory();
$client = new Client($httpClient, $requestFactory, $config);
$api = new Lox24Api($client);


$sms = new SmsItemRequest(
    'sender',
    '+1234567890',
    'sms text'
);

try {
    $response = $api->sms()->sendSms($sms, true);
    echo "ID: {$response->getId()}\n";
    echo "Price: {$response->getPrice()}";
} catch (AccessException $e) {
    echo "Access error: {$e->getMessage()}\n";
} catch (ResourceException $e) {
    echo "Resource error: {$e->getMessage()}\n";
} catch (ServiceException $e) {
    echo "Service error: {$e->getMessage()}\n";
} catch (ClientException $e) {
    echo "Client error: {$e->getMessage()}\n";
} catch (ApiException $e) {
    echo "Server error: {$e->getMessage()}\n";
}

```

## Usage examples

For more examples, see the [examples](examples) directory.

## Exception Handling in LOX24 SMS Gateway Client

The LOX24 SMS Gateway Client library defines several exceptions to handle various error scenarios gracefully. 
All custom exception classes extend the base `ApiException` class.

### Access Exceptions (AccessException)
Thrown when there is a generic access-related error.
- `AccountBlocked`: Thrown when the user account is blocked due to policy violations or suspicious activities.
- `InvalidCredentials`: Thrown when provided credentials are invalid.
- `IpBlocked`: Thrown when requests from the current IP are blocked.
- `NotEnoughFundsPerOperation`: Thrown when the account balance is insufficient for the operation.
- `TwoManyRequests`: Thrown when the rate limit is exceeded.

### Resource Exceptions (ResourceException)
A generic exception for resource-related errors.
- `BadRequest`: Thrown when the server cannot process the request due to a client error.
- `ResourceNotFound`: Thrown when a requested resource is not found.
- `UnprocessableRequest`: Thrown when the server understands the request but is unable to process the contained instructions.

### Service Exceptions (ServiceException)
Thrown when there is a generic service-related error.
- `RequestException`: Thrown when there is an issue with the request sent to the service.
- `UnexpectedException`: Thrown when an unexpected condition was encountered, .e.g. server unavailable.

### `ClientException`
This exception is a type of `ApiException` that represents errors that occur due to problems on the client side. 
It acts as a base exception for client-related issues. 
For example, this exception should be thrown when the client's request is malformed or 
when there is a failure in client-side logic before making a request to the server.


#### Handling Exceptions

Here's how you might catch and handle these exceptions in your application:

```php
try {
    // Code that interacts with the SMS Gateway Client
} catch (ApiException $e) {
    // Handle all exceptions derived from ApiException
    error_log($e->getMessage());
    // Additional error handling...
}

```


## Getting help

If you've instead found a bug in the library or would like new features added, go ahead and open issues or pull requests against this repo!
