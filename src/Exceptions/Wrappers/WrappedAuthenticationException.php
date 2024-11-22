<?php

declare(strict_types=1);

namespace Laniakea\Exceptions\Wrappers;

use Illuminate\Auth\AuthenticationException;
use Laniakea\Exceptions\BaseHttpException;

class WrappedAuthenticationException extends BaseHttpException
{
    public const ERROR_CODE = 'unauthenticated';
    public const HTTP_CODE = 401;

    public function __construct(private readonly AuthenticationException $authenticationException)
    {
        parent::__construct(
            $this->authenticationException->getMessage(),
            static::HTTP_CODE,
            $this->authenticationException,
        );
    }

    /**
     * Get the original authentication exception.
     *
     * @return AuthenticationException
     */
    public function getOriginalAuthenticationException(): AuthenticationException
    {
        return $this->authenticationException;
    }
}
