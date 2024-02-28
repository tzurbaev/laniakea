<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Exceptions;

use Illuminate\Foundation\Exceptions\Handler;
use Laniakea\Exceptions\ExceptionRenderer;

class FakeExceptionHandler extends Handler
{
    public function register(): void
    {
        app(ExceptionRenderer::class)->registerAll($this);
    }
}
