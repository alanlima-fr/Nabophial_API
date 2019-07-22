<?php

namespace App\Repository;

use App\Entity\Place;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Place|null find($id, $lockMode = null, $lockVersion = null)
 * @method Place|null findOneBy(array $criteria, array $orderBy = null)
 * @method Place[]    findAll()
 * @method Place[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlaceRepository extends ServiceEntityRepository
{
    private $entity = 'App\Entity\Place';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Place::class);
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
            case 'entity.adresse':  
                $qb->where('entity.adresse LIKE :adresse')->setParameter('adresse', $array.'%'); //Tri selon un nom évenement l'adresse recherché . Il n'est pas obligé de recevoir le nom entier ou exacte de l'evenement pour le chercher (recherche comme sur le moteur google lorsque on tape ce que l'on cherche)
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
    //  * @return Place[] Returns an array of Place objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Place
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
