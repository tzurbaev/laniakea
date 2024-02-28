<?php

declare(strict_types=1);

namespace Laniakea\Exceptions;

class HttpNotFoundException extends BaseHttpException
{
    public const MESSAGE = 'Not found.';
    public const ERROR_CODE = 'not_found';
    public const HTTP_CODE = 404;
}
