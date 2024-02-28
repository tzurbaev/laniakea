<?php

declare(strict_types=1);

namespace Laniakea\Exceptions;

use Illuminate\Validation\ValidationException as BaseValidationException;

class ValidationException extends BaseHttpException
{
    public const MESSAGE = 'Validation failed.';
    public const ERROR_CODE = 'validation';
    public const HTTP_CODE = 422;

    public function __construct(private readonly BaseValidationException $validationException)
    {
        parent::__construct(static::MESSAGE, static::HTTP_CODE);

        $this->addMeta(['errors' => $this->validationException->errors()]);
    }

    public function getOriginalValidationException(): BaseValidationException
    {
        return $this->validationException;
    }

    public function getTranslatedErrorMessage(): ?string
    {
        $errors = $this->validationException->errors();
        $firstFailedField = array_key_first($errors);

        return $errors[$firstFailedField][0] ?? null;
    }
}
