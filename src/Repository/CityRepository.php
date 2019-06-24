<?php

namespace App\Repository;

use App\Entity\City;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method City|null find($id, $lockMode = null, $lockVersion = null)
 * @method City|null findOneBy(array $criteria, array $orderBy = null)
 * @method City[]    findAll()
 * @method City[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CityRepository extends ServiceEntityRepository
{
    private $entity = 'App\Entity\City';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, City::class);
    }

    public function findAllSortBy($sortBy = 'id', $sortOrder = 'desc') // par defaut on trie par id par ordre décroissant
    {
        $qb = $this->getEntityManager()->createQueryBuilder(); // Instanciation de la QueryBuilder
        $qb->select('entity')->from($this->entity, 'entity');  // SELECT FROM, basic simple

        // en fonction de ce avec quoi on trie
        switch ($sortBy)
        {
            case 'asc':
                $sortOrder = 'asc';
                $qb->orderBy('entity.'.$sortBy, $sortOrder); 
                break;
            default:
                $qb->orderBy('entity.'.$sortBy, $sortOrder); // On effectue le trie
                break;
        }
        return $qb; // On renvoie la QueryBuilder
    }

    public function filterWith($qb, $array, $where)
    {
         //Tri selon un nom de ville.
         // Il n'est pas obligé de recevoir le nom entier ou exacte de de la ville pour le chercher
         // (recherche comme sur le moteur google lorsque on tape ce que l'on cherche)
        $qb->where('entity.name LIKE :name')->setParameter('name', $array.'%');

        return $qb;
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
    //  * @return City[] Returns an array of City objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?City
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
