<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Exceptions;

use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Laniakea\Exceptions\BaseHttpException;
use Laniakea\Exceptions\ExceptionRenderer;

class FakeExceptionHandler extends Handler
{
    public function register(): void
    {
        /** @var ExceptionRenderer $renderer */
        $renderer = app(ExceptionRenderer::class);

        $this->renderable(fn (BaseHttpException $e, Request $request) => $renderer->render($e, $request));
        $this->renderable(fn (ValidationException $e, Request $request) => $renderer->renderValidationException($e, $request));
    }
}
