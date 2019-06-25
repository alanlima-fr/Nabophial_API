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

        // On filtre les résultat avec un where qui change selon le array (qui est la condition)
        switch ($where) 
        {
            
            case 'entity.lieu':
                $qb->andWhere('entity.lieu = :lieu')->setParameter('lieu', $array); // Tri selon le lieu de l'évenement 
                break;
            case 'entity.status':
                $qb->andWhere('entity.status = :status')->setParameter('status', $array); //Tri selon le status de l'évenement 
                break;
            case 'entity.privateEvent':
                $qb->andWhere('entity.privateEvent = :privateEvent')->setParameter('privateEvent', $array); //Tri selon les évenement privé ou public
                break;
            case 'entity.nom':  
                $qb->where('entity.nom LIKE :nom')->setParameter('nom', $array.'%'); //Tri selon un nom évenement. Il n'est pas obligé de recevoir le nom entier ou exacte de l'evenement pour le chercher (recherche comme sur le moteur google lorsque on tape ce que l'on cherche)
                break;
            case 'entity.beginTime':
                $qb->where('entity.beginTime >= :beginTime')->setParameter('beginTime', $array); // Tri selon la date DE DEBUT de l'évenement. Peut prendre en compte l'heure mais il n'est normalment pas défini
                break;
        }

           return $qb;

    }

    /**
     * Le but de cette fonction est d'ajouter les sous objets dans notre recherche
     * Puis d'effectuer la recherche avec la fonction textSearch()
     * 
     * EXEMPLE : je veux tout les test qui contiennent Paris
     *  je fais un leftJoin pour regarder dans les sous objets ville departement et region
     */
    public function prepTextSearch($qb, $textSearch)
    {
        return $qb = $this->textSearch($qb,
            array('entity.id', 'entity.name', 'entity.lieu', 'entity.beginTime', 'entity.endDate', 'entity.horaire', 'entity.nbrMax', 'entity.description', 'entity.privateEvent', 'entity.status'),
            $textSearch
        );
    }

    /**
     * fields = la ou on va faire notre recherche
     * value = la valeur que l'on recherche
     */
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
