<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Exceptions;

use Laniakea\Exceptions\BaseHttpException;

class FakeTranslatableException extends BaseHttpException
{
    public const MESSAGE = 'Oops';
    public const ERROR_CODE = 'translatable.oops';
    public const HTTP_CODE = 500;

    protected function getTranslationNamespace(): string
    {
        return 'testing::exceptions.';
    }
}
