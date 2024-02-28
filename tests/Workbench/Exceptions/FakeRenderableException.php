<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Exceptions;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Laniakea\Exceptions\BaseHttpException;
use Laniakea\Exceptions\RenderableExceptionInterface;

class FakeRenderableException extends BaseHttpException implements RenderableExceptionInterface
{
    public const MESSAGE = 'Service not available';
    public const HTTP_CODE = 503;
    public const ERROR_CODE = 'service_not_available';

    public function getView(Request $request): View
    {
        return view('testing::exception', ['e' => $this]);
    }
}
