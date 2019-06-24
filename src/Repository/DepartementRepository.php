<?php

namespace App\Repository;

use App\Entity\Departement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Departement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Departement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Departement[]    findAll()
 * @method Departement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepartementRepository extends ServiceEntityRepository
{
    private $entity = 'App\Entity\Departement';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Departement::class);
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
                $qb->orderBy('entity.'.$sortBy, $sortOrder); // On effectue le trie
                break;
            default:
                $qb->orderBy('entity.'.$sortBy, $sortOrder); // On effectue le trie
                break;
        }
        return $qb; // On renvoie la QueryBuilder
    }

    public function filterWith($qb, $array, $where)
    {
        //Tri selon un nom de département.
        // Il n'est pas obligé de recevoir le nom entier ou exacte du département pour le chercher
        // (recherche comme sur le moteur google lorsque on tape ce que l'on cherche)

        $qb->where('entity.name LIKE :name')->setParameter('name', $array.'%'); 
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
    //  * @return Departement[] Returns an array of Departement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Departement
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
