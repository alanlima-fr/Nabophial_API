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
        return $this->appUserRepository->findAllAppUser($queryParams);
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

    /**
     * @param array<string, mixed> $submittedData
     */
    public function create(array $submittedData): AppUser
    {
        $appUser = new AppUser();

        $appUser->setEmail($submittedData['email'])
            ->setPlainPassword($submittedData['plainPassword']);
        unset($submittedData['email'], $submittedData['plainPassword']);

        foreach ($submittedData as $attribute => $value) {
            $method = 'set'.ucfirst($attribute);
            if (!method_exists($appUser, $method)) {
                continue;
            }

            $appUser->{$method}($value);
        }

        return $appUser;
    }

    /**
     * @throws NotFoundException
     *
     * @param array<string, mixed> $submittedData
     *
     * TODO : Finish this part
     */
    public function update(array $submittedData, int $appUserId): AppUser
    {
        $appUser = $this->appUserRepository->find($appUserId);
        if (!$appUser instanceof AppUser) {
            throw new NotFoundException(ExceptionMessageValueObject::NOT_FOUND);
        }

        return $appUser;
    }
}
