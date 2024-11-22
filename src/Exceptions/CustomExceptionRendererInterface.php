<?php

declare(strict_types=1);

namespace Laniakea\Exceptions;

use Illuminate\Http\Request;
use Illuminate\View\View;

interface CustomExceptionRendererInterface
{
    /**
     * Get the view instance for the given exception.
     *
     * @param BaseHttpException $e
     * @param Request           $request
     *
     * @return View
     */
    public function getView(BaseHttpException $e, Request $request): View;
}
