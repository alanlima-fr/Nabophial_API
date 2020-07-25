<?php

namespace App\Repository;

use App\Entity\AppUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\ORMException;
use Doctrine\ORM\QueryBuilder;

/**
 * @method AppUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method AppUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method AppUser[]    findAll()
 * @method AppUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AppUserRepository extends ServiceEntityRepository
{
    /** @var string */
    private $entity = AppUser::class;
    /** @var string[] */
    private const SORT_BY_PARAM = [
        'id' => 'a.id',
        'lastName' => 'a.lastName',
        'firstName' => 'a.firstName',
        'birthday' => 'a.birthday',
        'email' => 'a.email',
        'number' => 'a.number',
        'male' => 'a.male',
    ];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AppUser::class);
    }

    /**
     * @param array<string> $queryParams
     */
    public function findAllAppUser(array $queryParams): QueryBuilder
    {
        $queryBuilder = $this->createQueryBuilder('a');

        if (isset($queryParams['sortBy'])) {
            $this->sortBy($queryBuilder, $queryParams);
        }

        return $queryBuilder;
    }

    /**
     * @param array<string> $queryParams
     */
    private function sortBy(QueryBuilder $queryBuilder, array $queryParams): void
    {
        $order = null;
        if (isset($queryParams['sortBy'], $queryParams['sortOrder']) && 'desc' === $queryParams['sortOrder']) {
            $order = $queryParams['sortOrder'];
        }

        $queryBuilder->addOrderBy(self::SORT_BY_PARAM[$queryParams['sortBy']], $order);
    }

    /**
     * @throws ORMException
     */
    public function save(AppUser $appUser): void
    {
        if ($appUser->isNew()) {
            $this->getEntityManager()->persist($appUser);
        }

        $this->getEntityManager()->flush();
    }
}
