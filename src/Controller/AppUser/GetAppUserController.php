<?php

declare(strict_types=1);

namespace App\Controller\AppUser;

use App\Exception\InvalidQueryParamException;
use App\Service\AppUserService;
use App\Validator\QueryParam\AppUserQueryParamValidator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GetAppUserController
{
    private const SERIALIZATION_GROUPS = [
        'groups' => [
            'AppUser',
            'history',
            'id',
            'pagination',
        ],
    ];

    /**
     * @throws ExceptionInterface
     * @throws InvalidQueryParamException
     */
    public function __invoke(Request $request, NormalizerInterface $normalizer, AppUserService $appUserService, ?int $appUserId): JsonResponse
    {
        $parameter = $appUserId;
        $method = 'getUser';

        if (null === $appUserId) {
            $method = 'getAll';
            $parameter = $request->query->all();
            AppUserQueryParamValidator::validate($parameter);
        }

        $response = $appUserService->{$method}($parameter);

        return new JsonResponse($normalizer->normalize($response, 'json', self::SERIALIZATION_GROUPS));
    }
}
