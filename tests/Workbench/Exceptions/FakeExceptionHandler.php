<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laniakea\Exceptions\BaseHttpException;
use Laniakea\Exceptions\ExceptionRenderer;
use Laniakea\Exceptions\Wrappers\WrappedAccessDeniedHttpException;
use Laniakea\Exceptions\Wrappers\WrappedAuthenticationException;
use Laniakea\Exceptions\Wrappers\WrappedValidationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class FakeExceptionHandler extends Handler
{
    public function register(): void
    {
        /** @var ExceptionRenderer $renderer */
        $renderer = app(ExceptionRenderer::class);

        $renderer->withCustomRenderer(new FakeCustomExceptionRenderer());

        // Register wrapped exception renderers.
        $this->renderable(fn (AuthenticationException $e, Request $request) => $renderer->renderJsonOnly(
            new WrappedAuthenticationException($e),
            $request
        ));

        $this->renderable(fn (AccessDeniedHttpException $e, Request $request) => $renderer->renderJsonOnly(
            new WrappedAccessDeniedHttpException($e),
            $request
        ));

        $this->renderable(fn (ValidationException $e, Request $request) => $renderer->renderJsonOnly(
            new WrappedValidationException($e),
            $request,
        ));

        // Register base HTTP exception renderer.
        $this->renderable(fn (BaseHttpException $e, Request $request) => $renderer->render($e, $request));
    }
}
