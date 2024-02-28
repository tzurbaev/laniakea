<?php

declare(strict_types=1);

namespace Laniakea\Resources\Middleware;

use Illuminate\Container\Container;
use Illuminate\Http\Request;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;
use Laniakea\Resources\Requests\ResourceRequest;

class SetResourceRequest
{
    public function handle(Request $request, callable $next)
    {
        Container::getInstance()->instance(
            ResourceRequestInterface::class,
            $this->getFreshResourceRequest($request),
        );

        return $next($request);
    }

    protected function getFreshResourceRequest(Request $request): ResourceRequestInterface
    {
        return new ResourceRequest($request);
    }
}
