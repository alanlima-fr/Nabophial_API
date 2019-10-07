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

        // Philippe.H : Normalment, on en aura pas besoin mais je le  laisse pour le moment au cas ou . 
        
        $or = $qb->expr()->orx();
        $array = explode(',', $array);
        foreach ($array as $value)
            $or->add($qb->expr()->eq($where, $value));
        $qb->andWhere($or);

        return $qb;
    }

    public function prepTextSearch($qb, $textSearch)
    {
        //Cherche également dans city ,departement et region
        //Mise en commentaire car il n'y a pas de liaison ManytoOne a 'ville' de l'entité place pour le moment.
        //$qb->leftJoin('entity.place', 'tsPlace')
        //        ->leftJoin('tsPlace.city', 'tsCity')
        //        ->leftJoin('tsCity.departement', 'tsDepartement')
        //        ->leftJoin('tsDepartement.region', 'tsRegion'); 
            
            
        return $qb = $this->textSearch($qb,
        array('entity.id', 'entity.adresse','entity.ville'),
            $textSearch
            );
              
    }

    public function textSearch($qb, array $fields, $value)
    {
        $or = $qb->expr()->orx();
        foreach ($fields as $field)
            $or->add($qb->expr()->like($field, $qb->expr()->literal('%'.$value.'%')));
        $qb->andWhere($or);

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
