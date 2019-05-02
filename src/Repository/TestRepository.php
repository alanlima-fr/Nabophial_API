<?php

namespace App\Repository;

use App\Entity\Test;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Test|null find($id, $lockMode = null, $lockVersion = null)
 * @method Test|null findOneBy(array $criteria, array $orderBy = null)
 * @method Test[]    findAll()
 * @method Test[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestRepository extends ServiceEntityRepository
{
    private $entity = 'App\Entity\Test';

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Test::class);
    }

    public function findAllSortBy($sortBy = 'id', $sortOrder = 'desc') // par defaut on trie par id par ordre décroissant
    {
        $qb = $this->getEntityManager()->createQueryBuilder(); // Instanciation de la QueryBuilder
        $qb->select('entity')->from($this->entity, 'entity');  // SELECT FROM, basic simple

        // en fonction de ce avec quoi on trie 
        switch ($sortBy)
        {
            case 'test':
                break;
            default:
                $qb->orderBy('entity.'.$sortBy, $sortOrder); // On effectue le trie
                break;
        }
        return $qb; // On renvoie la QueryBuilder
    }

    /**
     * Prend en paramètre:
     *  la QueryBuilder instancié
     *  un array qui contient toutes les valeurs envoyés dans l'url
     *  where qui contient ou le filtrage s'execute
     * 
     * EXEMPLE : je veux récuperer tout les tests dont l'age est 16 ou 17
     *  array = 16,17
     *  where = 'entity.age'
     */
    public function filterWith($qb, $array, $where) 
    {
        $or = $qb->expr()->orx();
        $array = explode(',', $array);
        foreach ($array as $value)
            $or->add($qb->expr()->eq($where, $value));
        $qb->andWhere($or);

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
        $qb->leftJoin('entity.city', 'tsCity')
                ->leftJoin('tsCity.departement', 'tsDepartement')
                ->leftJoin('tsDepartement.region', 'tsRegion');

        return $qb = $this->textSearch($qb,
            array('entity.id', 'entity.name', 'entity.age', 'tsCity.name', 'tsDepartement.name', 'tsRegion.name'),
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

    /**
     * Ici on définit le maximum de resultat retourné ainsi que la pagination
     * 
     * Exemple : on a 100 Test, mais on ne veut qu'en afficher 20 par 20
     *  limit=20
     *  ce qui fait qu'au maximum on a 5 pages :
     *    page=1 (= les 20 premiers resultats)
     *    page=2 (= les 20 suivants resultats)
     *    page=3 (= les 20 suivants resultats)
     *    ect, ... 
     */
    public function pageLimit($qb, $page, $limit)
    {
        $qb->setFirstResult(($page-1) * $limit);

        if ($limit > 100)
            $limit = 100;
    
        $qb->setMaxResults($limit);

        return $qb;
    }

    // /**
    //  * @return Test[] Returns an array of Test objects
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
    public function findOneBySomeField($value): ?Test
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
