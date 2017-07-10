<?php

namespace Cethyworks\DoctrinePaginatorDecorator\Tests\Decorator;

use Cethyworks\DoctrinePaginatorDecorator\Tests\Mock\DoctrinePaginatorDecoratorMock;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use PHPUnit\Framework\TestCase;
use Cethyworks\DoctrinePaginatorDecorator\Decorator\DoctrinePaginatorDecorator;

class DoctrinePaginatorDecoratorTest extends TestCase
{
    /**
     * @var QueryBuilder|\PHPUnit_Framework_MockObject_MockObject $queryBuilder
     */
    protected $queryBuilder;

    protected function setUp()
    {
        parent::setUp();
        $this->queryBuilder = $this->getMockBuilder(QueryBuilder::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
    }

    public function dataTestGetPageAndGetLimit()
    {
        return [
            [1, 1, 0, 1],
            [2, 9, 9, 9],
            [3, 7, 14, 7],
        ];
    }

    /**
     * @dataProvider dataTestGetPageAndGetLimit
     */
    public function testGetPageAndGetLimit($page, $limit, $expectedSetFirstResult, $expectedSetMaxResults)
    {
        $this->queryBuilder
            ->expects($this->once())
            ->method('setFirstResult')
            ->with($expectedSetFirstResult)
            ->willReturn($this->queryBuilder)
        ;
        $this->queryBuilder
            ->expects($this->once())
            ->method('setMaxResults')
            ->with($expectedSetMaxResults)
            ->willReturn($this->queryBuilder)
        ;

        $paginator = new DoctrinePaginatorDecorator($this->queryBuilder, $page, $limit);

        $this->assertEquals($page, $paginator->getPage());
        $this->assertEquals($limit, $paginator->getLimit());
    }


    public function dataTestHasNextPage()
    {
        return [
            [1, 1, 0, false],
            [1, 1, 1, false],
            [1, 1, 2, true],

            [10, 10, 99, false],
            [10, 10, 100, false],
            [10, 10, 101, true],
        ];
    }

    /**
     * @dataProvider dataTestHasNextPage
     */
    public function testHasNextPage($page, $limit, $total, $expectedHasNextPage)
    {
        $this->queryBuilder->expects($this->once())->method('setFirstResult')->willReturn($this->queryBuilder);
        $this->queryBuilder->expects($this->once())->method('setMaxResults')->willReturn($this->queryBuilder);

        $doctrinePaginatorMock = $this->getMockBuilder(Paginator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $doctrinePaginatorMock->expects($this->once())
            ->method('count')
            ->willReturn($total)
        ;


        $paginator = new DoctrinePaginatorDecoratorMock($this->queryBuilder, $page, $limit, $doctrinePaginatorMock);

        $this->assertEquals($expectedHasNextPage, $paginator->hasNextPage());
    }

    public function testGetCount()
    {
        $total = 33;

        $this->queryBuilder->expects($this->once())->method('setFirstResult')->willReturn($this->queryBuilder);
        $this->queryBuilder->expects($this->once())->method('setMaxResults')->willReturn($this->queryBuilder);

        $doctrinePaginatorMock = $this->getMockBuilder(Paginator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $doctrinePaginatorMock->expects($this->once())
            ->method('count')
            ->willReturn($total)
        ;


        $paginator = new DoctrinePaginatorDecoratorMock($this->queryBuilder, 1, 2, $doctrinePaginatorMock);

        $this->assertEquals($total, $paginator->getCount());
    }

    public function testGetList()
    {
        $list = ['foo', 'bar'];

        $this->queryBuilder->expects($this->once())->method('setFirstResult')->willReturn($this->queryBuilder);
        $this->queryBuilder->expects($this->once())->method('setMaxResults')->willReturn($this->queryBuilder);

        $query = $this->getMockBuilder(AbstractQuery::class)
            ->setMethods(['execute'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass()
        ;
        $query->expects($this->once())
            ->method('execute')
            ->willReturn($list)
        ;

        $doctrinePaginatorMock = $this->getMockBuilder(Paginator::class)
            ->disableOriginalConstructor()
            ->getMock()
        ;
        $doctrinePaginatorMock->expects($this->once())
            ->method('getQuery')
            ->willReturn($query)
        ;

        $paginator = new DoctrinePaginatorDecoratorMock($this->queryBuilder, 1, 3, $doctrinePaginatorMock);

        $this->assertEquals($list, $paginator->getList());
    }
}
