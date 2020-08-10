<?php

declare(strict_types=1);

namespace App\Controller\AppUser;

use App\Exception\InvalidBodyException;
use App\Service\AppUserService;
use App\Validator\AppUserValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class CreateAppUserController
{
    private const SERIALIZATION_GROUPS = [
        'id',
        'app_user',
    ];

    /**
     * @throws ExceptionInterface
     * @throws InvalidBodyException
     */
    public function __invoke(Request $request, NormalizerInterface $normalizer, AppUserService $appUserService): JsonResponse
    {
        AppUserValidator::validCreation($request->request->all());
        $appUser = $appUserService->create($request->request->all());

        return new JsonResponse($normalizer->normalize($appUser, 'json', self::SERIALIZATION_GROUPS), Response::HTTP_CREATED);
    }
}
