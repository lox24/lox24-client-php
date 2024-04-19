<?php

declare(strict_types=1);

namespace lox24\api_client\api\sms;

use lox24\api_client\Client;
use lox24\api_client\Endpoint;
use lox24\api_client\exceptions\access\InvalidCredentials;
use lox24\api_client\exceptions\access\TwoManyRequests;
use lox24\api_client\exceptions\ApiException;
use lox24\api_client\exceptions\ClientException;
use lox24\api_client\exceptions\service\RequestException;
use lox24\api_client\exceptions\service\UnexpectedException;
use lox24\api_client\Method;
use lox24\api_client\Request;

readonly final class SmsApi
{

    private const SMS_URI = '/sms';
    private const SMS_BATCH_CANCEL_URI = '/sms/ops/batch_cancel';
    private const SMS_BATCH_DELETE_URI = '/sms/ops/batch_delete';

    public function __construct(private Client $client)
    {
    }

    /**
     * @throws ClientException
     * @throws RequestException
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws TwoManyRequests
     * @throws UnexpectedException
     */
    public function getSmsList(?SmsListFilter $filter = null): SmsListResponse
    {
        $req = new Request(new Endpoint(Method::GET, self::SMS_URI), null, $filter);
        $res = $this->client->send($req);
        if(!$res) {
            throw new UnexpectedException('Response is empty');
        }
        return new SmsListResponse($res, $filter);
    }

    /**
     * @throws ClientException
     * @throws TwoManyRequests
     * @throws InvalidCredentials
     * @throws UnexpectedException
     * @throws ApiException
     * @throws RequestException
     */
    public function getSmsItem(string $id): SmsItemResponse
    {
        $req = new Request(new Endpoint(Method::GET, self::SMS_URI . "/$id"));
        $res = $this->client->send($req);
        if(!$res) {
            throw new UnexpectedException('Response is empty');
        }
        return new SmsItemResponse($res);
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws UnexpectedException
     * @throws InvalidCredentials
     * @throws ApiException
     * @throws RequestException
     */
    public function deleteSmsItem(string $id): void
    {
        $req = new Request(new Endpoint(Method::GET, self::SMS_URI . "/$id"));
        $res = $this->client->send($req);
        if(!$res) {
            throw new UnexpectedException('Response is empty');
        }

        if($res->getStatus() !== 204) {
            throw new UnexpectedException('Response status code is not 204');
        }
    }


    /**
     * @throws TwoManyRequests
     * @throws UnexpectedException
     * @throws InvalidCredentials
     * @throws ClientException
     * @throws ApiException
     * @throws RequestException
     */
    public function sendSms(SmsItemRequest $sms, bool $isDryrun = false): SmsItemResponse
    {
        $uri = $isDryrun ? self::SMS_URI . '/dryrun' : self::SMS_URI;
        $req = new Request(new Endpoint(Method::POST, $uri), $sms);
        $res = $this->client->send($req);
        if(!$res) {
            throw new UnexpectedException('Response is empty');
        }
        return new SmsItemResponse($res);
    }

    /**
     * @throws TwoManyRequests
     * @throws ClientException
     * @throws UnexpectedException
     * @throws ApiException
     * @throws InvalidCredentials
     * @throws RequestException
     */
    public function cancelSmsList(?SmsListFilter $filter = null): int
    {
        $e = new Endpoint(Method::POST, self::SMS_BATCH_CANCEL_URI);
        $req = new Request($e, null, $filter);
        $res = $this->client->send($req);
        if(!$res) {
            throw new UnexpectedException('Response is empty');
        }
        return (new SmsBatchOperationResponse($res))->getAffectedRows();
    }

    /**
     * @throws TwoManyRequests
     * @throws UnexpectedException
     * @throws InvalidCredentials
     * @throws ClientException
     * @throws ApiException
     * @throws RequestException
     */
    public function deleteSmsList(?SmsListFilter $filter = null): int
    {
        $e = new Endpoint(Method::POST, self::SMS_BATCH_DELETE_URI);
        $req = new Request($e, null, $filter);
        $res = $this->client->send($req);
        if(!$res) {
            throw new UnexpectedException('Response is empty');
        }
        return (new SmsBatchOperationResponse($res))->getAffectedRows();
    }
}