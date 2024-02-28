<?php

declare(strict_types=1);

namespace Laniakea\Exceptions;

use Illuminate\Http\Request;
use Illuminate\View\View;

interface RenderableExceptionInterface
{
    public function getView(Request $request): View;
}
