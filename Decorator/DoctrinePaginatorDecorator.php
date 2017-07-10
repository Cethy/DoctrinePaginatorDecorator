<?php

namespace Cethyworks\DoctrinePaginatorDecorator\Decorator;

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class DoctrinePaginatorDecorator
{
    /**
     * @var Paginator
     */
    protected $paginator;

    /**
     * @var int
     */
    protected $page;

    /**
     * @var int
     */
    protected $limit;

    /**
     * ActivityPaginator constructor.
     *
     * @param QueryBuilder $queryBuilder
     * @param int $page
     * @param int $limit
     */
    public function __construct(QueryBuilder $queryBuilder, $page, $limit)
    {
        $this->page  = $page;
        $this->limit = $limit;

        $this->build($queryBuilder);
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return bool
     */
    public function hasNextPage()
    {
        return $this->paginator->count() > ($this->limit*$this->page);
    }

    /**
     * Return query entities total count
     *
     * @return int
     */
    public function getCount()
    {
        return $this->paginator->count();
    }

    /**
     * Return entity list (limited w/ page & limit)
     *
     * @return []
     */
    public function getList()
    {
        return $this->paginator->getQuery()->execute();
    }

    protected function build(QueryBuilder $queryBuilder)
    {
        $queryBuilder
            ->setFirstResult($this->limit*($this->page-1))
            ->setMaxResults($this->limit)
        ;

        $this->paginator = $this->buildPaginator($queryBuilder);
    }

    /**
     * @param QueryBuilder $queryBuilder
     * @return Paginator
     */
    protected function buildPaginator(QueryBuilder $queryBuilder)
    {
        return new Paginator($queryBuilder);
    }
}
