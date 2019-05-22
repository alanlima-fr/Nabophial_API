<?php

namespace App\Repository;

use App\Entity\Img;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Img|null find($id, $lockMode = null, $lockVersion = null)
 * @method Img|null findOneBy(array $criteria, array $orderBy = null)
 * @method Img[]    findAll()
 * @method Img[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImgRepository extends ServiceEntityRepository
{
    private $entity = 'App\Entity\Img';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Img::class);
    }

    public function findAllSortBy($sortBy = 'id', $sortOrder = 'desc')
    {
        $qb = $this->getEntityManager()->createQueryBuilder(); // Instanciation de la QueryBuilder
        $qb->select('entity')->from($this->entity, 'entity');  // SELECT FROM, basic simple  
        
        switch ($sortBy)
        {
            case 'asc':
                $sortOrder = 'asc';
                $qb->orderBy('entity.'.$sortBy, $sortOrder); // On effectue le trie
                break;
            default:
                $qb->orderBy('entity.'.$sortBy, $sortOrder); // On effectue le trie
                break;
        }
        
        
        return $qb;
    }

    public function pageLimit($qb, $page, $limit)
    {
        $qb->setFirstResult(($page-1) * $limit);

        if ($limit > 100)
            $limit = 100;
    
        $qb->setMaxResults($limit);

        return $qb;
    }


    // /**
    //  * @return Img[] Returns an array of Img objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Img
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
