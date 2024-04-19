<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use lox24\api_client\ListFilter;
use lox24\api_client\ListPager;
use lox24\api_client\ListResponse;
use lox24\api_client\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\MockObject\Exception;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListResponse::class)]
final class ListResponseTest extends TestCase
{
    private MockObject|Response $response;
    private MockObject|ListFilter $filter;

    /**
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->response = $this->createMock(Response::class);
        $this->filter = $this->createMock(ListFilter::class);
    }

    public function testGetData(): void
    {
        $data = [
            'hydra:member' => [
                ['id' => 1, 'name' => 'Item 1'],
                ['id' => 2, 'name' => 'Item 2']
            ],
            'hydra:totalItems' => 2,
            'hydra:view' => []
        ];

        $this->response->method('getData')->willReturn($data);
        $listResponse = new MockListResponse($this->response, $this->filter);

        $items = $listResponse->getData();
        $this->assertCount(2, $items);
        $this->assertInstanceOf(MockItem::class, $items[0]);
        $this->assertInstanceOf(MockItem::class, $items[1]);
        $this->assertSame($this->filter, $listResponse->getFilter());
    }

    public function testGetTotalItems(): void
    {
        $data = ['hydra:totalItems' => 10];
        $this->response->method('getData')->willReturn($data);
        $listResponse = new MockListResponse($this->response, $this->filter);

        $totalItems = $listResponse->getTotalItems();
        $this->assertEquals(10, $totalItems);
    }

    public function testGetPager(): void
    {
        $data = ['hydra:view' => []];
        $this->response->method('getData')->willReturn($data);
        $listResponse = new MockListResponse($this->response, $this->filter);

        $pager = $listResponse->getPager();
        $this->assertInstanceOf(ListPager::class, $pager);
    }
}