<?php

declare(strict_types=1);

namespace Laniakea\Exceptions;

use Illuminate\Http\Request;
use Illuminate\View\View;

interface RenderableExceptionInterface
{
    /**
     * Get the view instance for the exception.
     *
     * @param Request $request
     *
     * @return View
     */
    public function getView(Request $request): View;
}
