<?php

declare(strict_types=1);

namespace Laniakea\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException as BaseValidationException;

readonly class ExceptionRenderer
{
    /**
     * Render BaseHttpException or RenderableExceptionInterface.
     *
     * @param BaseHttpException $e
     * @param Request           $request
     *
     * @return JsonResponse|Response|void
     */
    public function render(BaseHttpException $e, Request $request)
    {
        if ($request->wantsJson()) {
            return $this->getJsonResponse($e, $request);
        } elseif ($e instanceof RenderableExceptionInterface) {
            return $this->getResponse($e, $request);
        }
    }

    /**
     * Render validation exception as BaseHttpException (JSON only).
     *
     * @param BaseValidationException $e
     * @param Request                 $request
     *
     * @return JsonResponse|Response|void|null
     */
    public function renderValidationException(BaseValidationException $e, Request $request)
    {
        if ($request->wantsJson()) {
            return $this->render(new ValidationException($e), $request);
        }
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
