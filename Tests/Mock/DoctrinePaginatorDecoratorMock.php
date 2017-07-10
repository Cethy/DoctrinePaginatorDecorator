<?php

namespace Cethyworks\DoctrinePaginatorDecorator\Tests\Mock;

use Cethyworks\DoctrinePaginatorDecorator\Decorator\DoctrinePaginatorDecorator;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * Mock used to overrides the DoctrinePaginator which use directly Query (which isnt mockable ...)
 */
class DoctrinePaginatorDecoratorMock extends DoctrinePaginatorDecorator
{
    public function __construct(QueryBuilder $queryBuilder, $page, $limit, $paginator)
    {
        $this->paginator = $paginator;

        parent::__construct($queryBuilder, $page, $limit);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return Paginator
     */
    protected function buildPaginator(QueryBuilder $queryBuilder)
    {
        return $this->paginator;
    }
}
