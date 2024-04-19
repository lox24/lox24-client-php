<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use lox24\api_client\exceptions\ApiException;
use lox24\api_client\exceptions\ClientException;
use lox24\api_client\ListFilter;
use lox24\api_client\ListFilterOrdering;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListFilter::class)]
final class ListFilterTest extends TestCase
{
    private MockListFilter $filter;

    protected function setUp(): void
    {
        $this->filter = new MockListFilter();
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testOrderByNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->orderBy('foo', ListFilterOrdering::ASC);
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testOrderByAddsCorrectly(): void
    {
        $this->filter->orderBy('name', ListFilterOrdering::ASC);
        $this->filter->orderBy('date', ListFilterOrdering::DESC);
        $this->assertEquals('_order[name]=asc&_order[date]=desc', urldecode($this->filter->__toString()));
        $this->assertSame(['_order' => ['name' => 'asc', 'date' => 'desc']], $this->filter->jsonSerialize());
    }

    /**
     * @throws ApiException
     * @throws ClientException
     */
    public function testRemoveOrderByWorksCorrectly(): void
    {
        $this->filter->orderBy('name', ListFilterOrdering::ASC)->removeOrderBy('name');
        $this->assertEquals('', (string)$this->filter);
        $this->assertEmpty($this->filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testBetweenSetsCorrectly(): void
    {
        $this->filter->between('age', 18, 30);
        $this->assertSame('age[between]=18..30', urldecode((string)$this->filter));
        $this->assertSame(['age' => ['between' => '18..30']], $this->filter->jsonSerialize());
    }

    /**
     * @throws ApiException
     * @throws ClientException
     */
    public function testRemoveBetweenWorksCorrectly(): void
    {
        $this->filter->between('age', 18, 30)->removeBetween('age');
        $this->assertEquals('', (string)$this->filter);
        $this->assertEmpty($this->filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testBettwenWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->between('foo', 1, 2);
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testBooleanSetsCorrectly(): void
    {
        $this->filter->boolean('active', true);
        $this->assertSame('active=1', (string)$this->filter);
        $this->assertSame(['active' => 1], $this->filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testRemoveBooleanWorksCorrectly(): void
    {
        $this->filter->boolean('active', true)->removeBoolean('active');
        $this->assertEquals('', (string)$this->filter);
        $this->assertEmpty($this->filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testBooleanWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->boolean('foo', true);
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testSearchAddsCorrectly(): void
    {
        $this->filter->search('query', 'test');
        $this->filter->search('query', 'test2');
        $this->filter->search('text', 'some text');
        $this->assertStringContainsString(
            'query[0]=test&query[1]=test2&text[0]=some text',
            urldecode((string)$this->filter)
        );
        $this->assertSame(
            ['query' => ['test', 'test2'], 'text' => ['some text']],
            $this->filter->jsonSerialize()
        );

    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testRemoveOneFieldSearchWorksCorrectly(): void
    {
        $this->filter
            ->search('query', 'test')
            ->search('text', 'some text')
            ->removeSearch('query', 'test');

        $this->assertEquals('text[0]=some text', urldecode((string)$this->filter));
        $this->assertSame(['text' => ['some text']], $this->filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testRemoveAllFieldsSearchWorksCorrectly(): void
    {
        $this->filter
            ->search('query', 'test')
            ->search('query', 'some text')
            ->removeSearch('query');

        $this->assertEquals('', (string)$this->filter);
        $this->assertEmpty($this->filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testSearchWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->search('foo', 'test');
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testRemoveSearchWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->removeSearch('foo', 'test');
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testExistAndNotExistWorksCorrectly(): void
    {
        $this->filter->exist('photo')->notExist('bio');
        $this->assertSame('exists[photo]=1&exists[bio]=0', urldecode((string)$this->filter));
        $this->assertSame(['exists' => ['photo' => 1, 'bio' => 0]], $this->filter->jsonSerialize());
    }

    /**
     * @throws ApiException
     * @throws ClientException
     */
    public function testRemoveExistWorksCorrectly(): void
    {
        $this->filter->exist('photo')->notExist('bio')->removeExist();
        $this->assertEquals('', (string)$this->filter);
        $this->assertEmpty($this->filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testExistsWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->exist('foo');
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testNotExistsWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->notExist('foo');
    }

    public function testJsonSerializeInitialEmpty(): void
    {
        $this->assertEquals([], $this->filter->jsonSerialize());
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testGreater(): void
    {
        $this->filter->greater('age', 18);
        $this->assertEquals(['age' => ['gt' => 18]], $this->filter->jsonSerialize());
        $this->assertSame('age[gt]=18', urldecode((string)$this->filter));
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testRemoveGreater(): void
    {
        $this->filter->greater('age', 18)->removeGreater('age');
        $this->assertEmpty((string)$this->filter);
        $this->assertEquals([], $this->filter->jsonSerialize());
    }

    public function testGreaterOrEqual(): void
    {
        $this->filter->greaterOrEqual('age', 18);
        $this->assertEquals(['age' => ['gte' => 18]], $this->filter->jsonSerialize());
        $this->assertSame('age[gte]=18', urldecode((string)$this->filter));
    }

    public function testRemoveGreaterOrEqual(): void
    {
        $this->filter->greaterOrEqual('age', 18)->removeGreaterOrEqual('age');
        $this->assertEmpty((string)$this->filter);
        $this->assertEquals([], $this->filter->jsonSerialize());
    }

    /**
     * @throws ApiException
     */
    public function testGreaterWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->greater('invalidField', 10);
    }

    /**
     * @throws ApiException
     */
    public function testGreaterOrEqualWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->greaterOrEqual('invalidField', 10);
    }

    public function testLess(): void
    {
        $this->filter->less('age', 65);
        $this->assertEquals(['age' => ['lt' => 65]], $this->filter->jsonSerialize());
        $this->assertSame('age[lt]=65', urldecode((string)$this->filter));
    }

    public function testRemoveLess(): void
    {
        $this->filter->less('age', 65)->removeLess('age');
        $this->assertEmpty((string)$this->filter);
        $this->assertEquals([], $this->filter->jsonSerialize());
    }

    public function testLessOrEqual(): void
    {
        $this->filter->lessOrEqual('age', 65);
        $this->assertEquals(['age' => ['lte' => 65]], $this->filter->jsonSerialize());
        $this->assertSame('age[lte]=65', urldecode((string)$this->filter));
    }

    /**
     * @throws ClientException
     * @throws ApiException
     */
    public function testRemoveLessOrEqual(): void
    {
        $this->filter->lessOrEqual('age', 65)->removeLessOrEqual('age');
        $this->assertEmpty((string)$this->filter);
        $this->assertEquals([], $this->filter->jsonSerialize());
    }

    /**
     * @throws ApiException
     */
    public function testLessOrEqualWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->lessOrEqual('invalidField', 10);
    }

    /**
     * @throws ApiException
     */
    public function testLessWithNotAllowedField(): void
    {
        $this->expectException(ClientException::class);
        $this->filter->less('invalidField', 10);
    }


}