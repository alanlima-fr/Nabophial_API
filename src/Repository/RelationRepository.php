<?php

namespace App\Repository;

use App\Entity\Relation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Relation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Relation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Relation[]    findAll()
 * @method Relation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RelationRepository extends ServiceEntityRepository
{

    private $entity = 'App\Entity\Relation';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Relation::class);
    }

    public function findAllSortBy($sortBy = 'id', $sortOrder = 'desc')
    {
        $qb = $this->getEntityManager()->createQueryBuilder(); // Instanciation de la QueryBuilder
        $qb->select('entity')->from($this->entity, 'entity');  // SELECT FROM, basic simple  
        
        switch ($sortBy)
        {
            default:
                $qb->orderBy('entity.'.$sortBy, $sortOrder); // On effectue le trie
                break;
        }
        
        
        return $qb;
    }

    public function filterWith($qb,$array, $where)
    {

        // On filtre les résultat avec un where qui change selon le array (qui est la condition)
        switch ($where) 
        {
            
            case 'entity.idUser':
                $qb->andWhere('entity.idUser1 = :idUser1')->setParameter('idUser1', $array); // Tri selon l'idUser de la relation recherché (peut inporte la position)
                $qb->orWhere('entity.idUser2 = :idUser2')->setParameter('idUser2', $array); //
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
    //  * @return Relation[] Returns an array of Relation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Relation
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
