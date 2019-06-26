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
            default:
                $qb->orderBy('entity.'.$sortBy, $sortOrder); // On effectue le trie
                break;
        }

        return $qb;
    }

    public function filterWith($qb,$array, $where)
    {
        $or = $qb->expr()->orx();
        $array = explode(',', $array);
        foreach ($array as $value)
            $or->add($qb->expr()->eq($where, $value));
        $qb->andWhere($or);
        return $qb;
    }

    /**
     * Fonction qui fait le tri entre les évenements public (false) et privé (true) 
     */
    public function checkBoolSql($qb, $array)
    {
        if($array === 'true')
            $qb->andWhere('entity.privateEvent = :privateEvent')->setParameter('privateEvent', 1);
        else
            $qb->andWhere('entity.privateEvent = :privateEvent')->setParameter('privateEvent', 0);
        
            //dump($qb->getQuery()); die;
        return $qb;       
    }
    /**
     * Le but de cette fonction est d'ajouter les sous objets dans notre recherche
     * Puis d'effectuer la recherche avec la fonction textSearch()
     * 
     * EXEMPLE : je veux tout les test qui contiennent Paris
     *  je fais un leftJoin pour regarder dans les sous objets ville departement et region
     */
    public function prepTextSearch($qb, $textSearch,$where  = '' )
    {
        switch ($where) 
        {   
            case 'lieu' :
            // Ajout de jointure ultérieurement
                 return $qb = $this->textSearch($qb,
                    array('entity.lieu'),
                    $textSearch
                    );
                break;
            
            case 'date':
                $qb->where('entity.beginTime >= :beginTime')->setParameter('beginTime', $textSearch); // Filtre selon la date DE DEBUT de l'évenement. Peut prendre en compte l'heure mais il n'est normalment pas défini
                return $qb;
                break;

            default:
                return $qb = $this->textSearch($qb,
                    array('entity.name', 'entity.horaire', 'entity.nbrMax', 'entity.description'),
                    $textSearch
                    );
                break;
        }
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
