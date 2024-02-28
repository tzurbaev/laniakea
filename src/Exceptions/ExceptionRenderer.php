<?php

declare(strict_types=1);

namespace Laniakea\Exceptions;

use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException as BaseValidationException;

readonly class ExceptionRenderer
{
    public function registerAll(Handler $handler): void
    {
        $this->registerBaseHttpException($handler);
        $this->registerValidationException($handler);
    }

    public function registerBaseHttpException(Handler $handler): void
    {
        $handler->renderable(fn (BaseHttpException $e, Request $request) => $this->render($e, $request));
    }

    public function registerValidationException(Handler $handler): void
    {
        $handler->renderable(
            fn (BaseValidationException $e, Request $request) => $this->render(new ValidationException($e), $request)
        );
    }

    public function render(BaseHttpException $e, Request $request): Response|JsonResponse
    {
        if (($e instanceof RenderableExceptionInterface) && !$request->wantsJson()) {
            return $this->getResponse($e, $request);
        }

        return $this->getJsonResponse($e, $request);
    }

    protected function getResponse(BaseHttpException & RenderableExceptionInterface $e, Request $request): Response
    {
        return new Response(
            $e->getView($request),
            $e->getHttpCode(),
            $e->getHeaders(),
        );
    }

    protected function getJsonResponse(BaseHttpException $e, Request $request): JsonResponse
    {
        return new JsonResponse([
            'error' => [
                'message' => $e->getErrorMessage(),
                'original_message' => $e->getOriginalMessage(),
                'code' => $e->getErrorCode(),
                'meta' => $e->getMeta(),
            ],
        ], $e->getHttpCode(), $e->getHeaders());
    }
}
