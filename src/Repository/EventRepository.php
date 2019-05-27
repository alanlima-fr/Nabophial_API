<?php

namespace App\Repository;

use App\Entity\Event;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Event|null find($id, $lockMode = null, $lockVersion = null)
 * @method Event|null findOneBy(array $criteria, array $orderBy = null)
 * @method Event[]    findAll()
 * @method Event[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventRepository extends ServiceEntityRepository
{
    private $entity = 'App\Entity\Event';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Event::class);
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

    public function filterWith($qb,$array, $where)
    {

        
        switch ($where)
        {
            
            case 'entity.lieu':
                $qb->andWhere('entity.lieu = :lieu')->setParameter('lieu', $array);
                break;
            case 'entity.status':
                $qb->andWhere('entity.status = :status')->setParameter('status', $array);
                break;
            case 'entity.privateEvent':
                $qb->andWhere('entity.privateEvent = :privateEvent')->setParameter('privateEvent', $array);
                break;
            case 'entity.nom':  
                $qb->where('entity.nom LIKE :nom')->setParameter('nom', $array.'%');
                break;
            case 'entity.beginTime':
                $qb->where('entity.beginTime >= :beginTime')->setParameter('beginTime', $array);
                break;
        }

           return $qb;

        //Doit filter Date,horaire
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
    //  * @return Event[] Returns an array of Event objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Event
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
