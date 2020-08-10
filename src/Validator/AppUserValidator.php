<?php

declare(strict_types=1);

namespace App\Validator;

use App\Exception\InvalidBodyException;
use App\ValueObject\ExceptionMessageValueObject;

class AppUserValidator
{
    /**
     * @throws InvalidBodyException
     *
     * @param array<string, mixed> $data
     */
    public static function validCreation(array $data): void
    {
        if (!isset($data['email'])) {
            throw new InvalidBodyException(ExceptionMessageValueObject::MISSING_KEY_IN_BODY.' email');
        }

        if (!isset($data['plainPassword'])) {
            throw new InvalidBodyException(ExceptionMessageValueObject::MISSING_KEY_IN_BODY.' plainPassword');
        }
    }
}
