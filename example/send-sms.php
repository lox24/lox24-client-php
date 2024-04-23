<?php


include dirname(__DIR__, 2).'/vendor/autoload.php';

/******************************************************************************************************
 * WARNING: This is a sample script uses Guzzle library as PSR-7 and PSR-17 implementation.            *
 * You are able to use any other PSR-7 and PSR-17 compatible libraries or your own implementation.    *
 ******************************************************************************************************/

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Psr7\HttpFactory as GuzzleRequestFactory;
use lox24\api_client\api\Lox24Api;
use lox24\api_client\api\ServiceCode;
use lox24\api_client\api\sms\SmsItemRequest;
use lox24\api_client\api\TextEncoding;
use lox24\api_client\api\VoiceLang;
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

// Create SMS request with minimal required data
// API Documentation: https://doc.lox24.eu/#tag/sms/operation/api_sms_post_collection
$sms1 = new SmsItemRequest(
    'sender',
    '+1234567890',
    'sms text'
);

// Create SMS request with all required data
// API Documentation: https://doc.lox24.eu/#tag/sms/operation/api_sms_post_collection
$deleteTextAfterDelivery = true;
$sms2 = new SmsItemRequest(
    'sender',
    '+1234567890',
    'sms text',
    ServiceCode::Text2Speech,
    new DateTime('+1 hour'),
    TextEncoding::Unicode,
    123456,
    VoiceLang::English,
    $deleteTextAfterDelivery,
    'your data for callback',
);

try {
    $response = $api->sms()->sendSms($sms1, true);
    echo "ID: {$response->getId()}\n";
    echo "Price: {$response->getPrice()}";
    $response2 = $api->sms()->sendSms($sms2, true);
    echo "ID: {$response2->getId()}\n";
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

