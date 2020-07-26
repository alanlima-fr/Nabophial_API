<?php

declare(strict_types=1);

namespace App\Controller\AppUser;

use App\Service\AppUserService;
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
     */
    public function __invoke(Request $request, NormalizerInterface $normalizer, AppUserService $appUserService): JsonResponse
    {
        $appUser = $appUserService->create($request->request->all());

        return new JsonResponse($normalizer->normalize($appUser, 'json', self::SERIALIZATION_GROUPS), Response::HTTP_CREATED);
    }
}
