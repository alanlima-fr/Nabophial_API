<?php

declare(strict_types=1);

namespace App\ValueObject;

class ExceptionMessageValueObject
{
    public const INVALID_QUERY_PARAM = 'invalid query param given : ';
    public const INVALID_PARAM = 'invalid param given';
    public const INVALID_JSON = 'invalid json';
    public const INVALID_EMAIL = 'invalid email';

    public const NOT_FOUND = 'not found';
    public const NON_UNIQUE = 'non unique ';

    public const EMPTY_BODY = 'empty body given';
    public const INVALID_BODY = 'this key is invalid : ';
    public const MISSING_KEY_IN_BODY = 'this key is missing : ';
}
