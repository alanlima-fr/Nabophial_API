<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\AppUser;
use App\Exception\NotFoundException;
use App\Repository\AppUserRepository;
use App\Representation\Pagination;
use App\ValueObject\ExceptionMessageValueObject;

class AppUserService
{
    /** @var AppUserRepository */
    private $appUserRepository;

    public function __construct(AppUserRepository $appUserRepository)
    {
        $this->appUserRepository = $appUserRepository;
    }

    /**
     * @param array<string, mixed> $queryParams
     */
    public function getAll(array $queryParams = ['limit' => 25, 'page' => 1]): Pagination
    {
        $appUserListQueryBuilder = $this->appUserRepository->findAllAppUser($queryParams);

        return PaginatorService::paginate($appUserListQueryBuilder, $queryParams);
    }

    /**
     * @throws NotFoundException
     */
    public function getUser(int $appUserId): AppUser
    {
        $appUser = $this->appUserRepository->find($appUserId);
        if (!$appUser instanceof AppUser) {
            throw new NotFoundException(ExceptionMessageValueObject::NOT_FOUND);
        }

        return $appUser;
    }
}
