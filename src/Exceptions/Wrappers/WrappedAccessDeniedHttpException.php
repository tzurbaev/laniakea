<?php

declare(strict_types=1);

namespace Laniakea\Exceptions\Wrappers;

use Laniakea\Exceptions\BaseHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class WrappedAccessDeniedHttpException extends BaseHttpException
{
    public const ERROR_CODE = 'access_denied';
    public const HTTP_CODE = 403;

    public function __construct(private readonly AccessDeniedHttpException $accessDeniedException)
    {
        parent::__construct(
            $this->accessDeniedException->getMessage(),
            $this->accessDeniedException->getStatusCode() ?? static::HTTP_CODE,
            $this->accessDeniedException,
        );
    }

    /**
     * Get the original access denied exception.
     *
     * @return AccessDeniedHttpException
     */
    public function getOriginalAccessDeniedException(): AccessDeniedHttpException
    {
        return $this->accessDeniedException;
    }

    /**
     * Get HTTP headers.
     *
     * @return array
     */
    public function getHeaders(): array
    {
        return [
            ...parent::getHeaders(),
            ...$this->accessDeniedException->getHeaders(),
        ];
    }
}
