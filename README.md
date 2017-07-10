Cethyworks\DoctrinePaginatorDecorator
===
Decorator/helper for Doctrine\ORM\Tools\Pagination\Paginator.

[![CircleCI](https://circleci.com/gh/Cethy/DoctrinePaginatorDecorator/tree/master.svg?style=shield)](https://circleci.com/gh/Cethy/DoctrinePaginatorDecorator/tree/master)


## Install

1\. Composer require

    $ composer require cethyworks/doctrine-paginator-decorator

## How to use

    $paginator = new DoctrinePaginatorDecorator($queryBuilder, $currentPage, $limitPerPage);
  
    $paginator->getPage(); // return current page
    $paginator->getLimit(); // return limit per page
    $paginator->hasNextPage(); // return true if there is enough result for a next page
    $paginator->getCount(); // return total entity count
    $paginator->getList(); // return entity list for current page
