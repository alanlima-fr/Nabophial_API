<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

class DefaultRepository extends ServiceEntityRepository
{
    protected $entity;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, $this->entity);
    }

    /**
     * @return QueryBuilder
     */
    protected function init()
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->select('entity')->from($this->entity, 'entity');

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param string $array $array
     * @param string $where
     * @return QueryBuilder
     */
    public function filterWith(QueryBuilder $qb, string $array, string $where)
    {
        $or = $qb->expr()->orx();
        $array = explode(',', $array);

        foreach ($array as $value) {
            $or->add($qb->expr()->eq($where, $value));
        }
        $qb->andWhere($or);

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param array $fields
     * @param string $value
     * @return QueryBuilder
     */
    public function textSearch(QueryBuilder $qb, array $fields, string $value)
    {
        $or = $qb->expr()->orx();

        foreach ($fields as $field) {
            $or->add($qb->expr()->like($field, $qb->expr()->literal('%'.$value.'%')));
        }
        $qb->andWhere($or);

        return $qb;
    }

    /**
     * @param QueryBuilder $qb
     * @param Int $page
     * @param Int $limit
     * @return QueryBuilder
     */
    public function pageLimit(QueryBuilder $qb, Int $page, Int $limit)
    {
        $qb->setFirstResult(($page-1) * $limit);

        if ($limit > 100)
            $limit = 100;

        $qb->setMaxResults($limit);

        return $qb;
    }
}