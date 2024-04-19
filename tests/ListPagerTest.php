<?php

declare(strict_types=1);

namespace lox24\api_client_tests;

use lox24\api_client\ListPager;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;

#[CoversClass(ListPager::class)]
final class ListPagerTest extends TestCase
{
    public function testGetFirstWithKeyPresent(): void
    {
        $data = ['hydra:first' => 1];
        $listPager = new ListPager($data);
        $this->assertSame(1, $listPager->getFirst());
    }

    public function testGetFirstWithKeyAbsent(): void
    {
        $listPager = new ListPager([]);
        $this->assertNull($listPager->getFirst());
    }

    public function testGetCurrentWithKeyPresent(): void
    {
        $data = ['@id' => 2];
        $listPager = new ListPager($data);
        $this->assertSame(2, $listPager->getCurrent());
    }

    public function testGetCurrentWithKeyAbsent(): void
    {
        $listPager = new ListPager([]);
        $this->assertNull($listPager->getCurrent());
    }

    public function testGetLastWithKeyPresent(): void
    {
        $data = ['hydra:last' => 3];
        $listPager = new ListPager($data);
        $this->assertSame(3, $listPager->getLast());
    }

    public function testGetLastWithKeyAbsent(): void
    {
        $listPager = new ListPager([]);
        $this->assertNull($listPager->getLast());
    }

    public function testGetNextWithKeyPresent(): void
    {
        $data = ['hydra:next' => 4];
        $listPager = new ListPager($data);
        $this->assertSame(4, $listPager->getNext());
    }

    public function testGetNextWithKeyAbsent(): void
    {
        $listPager = new ListPager([]);
        $this->assertNull($listPager->getNext());
    }
}
