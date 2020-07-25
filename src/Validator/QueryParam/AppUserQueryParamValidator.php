<?php

declare(strict_types=1);

namespace App\Validator\QueryParam;

use App\Exception\InvalidQueryParamException;
use App\ValueObject\ExceptionMessageValueObject;

class AppUserQueryParamValidator
{
    /**
     * @throws InvalidQueryParamException
     *
     * @param array<string, mixed> $queryParams
     */
    public static function validate(array $queryParams): void
    {
        if (isset($queryParams['limit']) && $queryParams['limit'] <= 0) {
            throw new InvalidQueryParamException(ExceptionMessageValueObject::INVALID_QUERY_PARAM.' limit');
        }

        if (isset($queryParams['page']) && $queryParams['page'] <= 0) {
            throw new InvalidQueryParamException(ExceptionMessageValueObject::INVALID_QUERY_PARAM.' page');
        }

        if (isset($queryParams['sortBy']) && $queryParams['sortBy'] <= 0) {
            throw new InvalidQueryParamException(ExceptionMessageValueObject::INVALID_QUERY_PARAM.' sortBy');
        }

        if (isset($queryParams['sortOrder']) && in_array($queryParams['sortOrder'], ['asc', 'desc'])) {
            throw new InvalidQueryParamException(ExceptionMessageValueObject::INVALID_QUERY_PARAM.' sortOrder');
        }
    }
}
