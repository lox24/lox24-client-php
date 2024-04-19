<?php

/***
 * This example demonstrates how to send a message using the Lox24 API.
 * As HTTP client Guzzle is used.
 * Please install Guzzle before running this example:
 * `composer require guzzlehttp/guzzle`
 */


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
    'text'
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

