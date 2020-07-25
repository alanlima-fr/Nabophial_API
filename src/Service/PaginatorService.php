<?php

declare(strict_types=1);

namespace App\Service;

use App\Representation\Pagination;
use Doctrine\ORM\QueryBuilder;
use Pagerfanta\Adapter\DoctrineORMAdapter;
use Pagerfanta\Pagerfanta;

class PaginatorService
{
    public const LIMIT = 25;
    public const PAGE = 1;

    /**
     * @param array<string> $queryParams
     */
    public static function paginate(QueryBuilder $queryBuilder, array $queryParams): Pagination
    {
        $limit = $queryParams['limit'] ?? self::LIMIT;
        $page = $queryParams['page'] ?? self::PAGE;

        $adapter = new DoctrineORMAdapter($queryBuilder, true, false);
        $pager = new Pagerfanta($adapter);
        $pager->setMaxPerPage((int) $limit);
        if (isset($queryParams['page'])) {
            $pager->setCurrentPage(((int) $page));
        }

        return new Pagination($pager);
    }
}
