<?php

declare(strict_types=1);

namespace App\Controller\AppUser;

use App\Exception\NotFoundException;
use App\Service\AppUserService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class UpdateAppUserController
{
    private const SERIALIZATION_GROUPS = [
        'id',
        'app_user',
    ];

    /**
     * @throws ExceptionInterface
     * @throws NotFoundException
     */
    public function __invoke(Request $request, NormalizerInterface $normalizer, AppUserService $appUserService, int $appUserId): JsonResponse
    {
        $appUser = $appUserService->update($request->request->all(), $appUserId);

        return new JsonResponse($normalizer->normalize($appUser, 'json', self::SERIALIZATION_GROUPS), Response::HTTP_OK);
    }
}
