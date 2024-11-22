<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Exceptions;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Laniakea\Exceptions\BaseHttpException;
use Laniakea\Exceptions\CustomExceptionRendererInterface;

class FakeCustomExceptionRenderer implements CustomExceptionRendererInterface
{
    public function getView(BaseHttpException $e, Request $request): View
    {
        return view('testing::custom-exception', ['e' => $e]);
    }
}
