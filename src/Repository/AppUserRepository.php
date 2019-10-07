<?php

namespace App\Repository;

use App\Entity\AppUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;
use phpDocumentor\Reflection\Types\Integer;

/**
 * @method AppUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppUser[]    findAll()
 * @method AppUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppUserRepository extends ServiceEntityRepository
{
    private $entity = 'App:AppUser';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppUser::class);
    }

    /**
     * @param string $sortBy
     * @param string $sortOrder
     * @return QueryBuilder
     */
    public function findAllSortBy($sortBy = 'id', $sortOrder = 'desc') // par defaut on trie par id par ordre décroissant
    {
        $qb = $this->getEntityManager()->createQueryBuilder(); // Instanciation de la QueryBuilder
        $qb->select('entity')->from($this->entity, 'entity');  // SELECT FROM, basic simple

        // en fonction de ce avec quoi on trie
        switch ($sortBy)
        {
            default:
                $qb->orderBy('entity.'.$sortBy, $sortOrder); // On effectue le trie
                break;
        }
        return $qb; // On renvoie la QueryBuilder
    }

    /**
     * @param QueryBuilder $qb
     * @param String $textSearch
     * @return QueryBuilder
     */
    public function prepTextSearch(QueryBuilder $qb, String $textSearch)
    {
        //Cherche également dans departement et region
        $qb->leftJoin('entity.departement', 'tsDepartement')
            ->leftJoin('tsDepartement.region', 'tsRegion');


        return $qb = $this->textSearch($qb,
            array('entity.id', 'entity.name', 'tsDepartement.name', 'tsRegion.name'),
            $textSearch
        );

    }
}
