<?php

namespace App\Repository;

use App\Entity\TypePerformance;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TypePerformance|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypePerformance|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypePerformance[]    findAll()
 * @method TypePerformance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypePerformanceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TypePerformance::class);
    }

    // /**
    //  * @return TypePerformance[] Returns an array of TypePerformance objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypePerformance
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
