<?php

declare(strict_types=1);

namespace Laniakea\Exceptions;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException as BaseValidationException;
use Illuminate\View\View;
use Laniakea\Exceptions\Wrappers\WrappedValidationException;

class ExceptionRenderer
{
    /**
     * Custom exception renderer.
     * This will be used if the exception is not an instance of RenderableExceptionInterface.
     *
     * @var CustomExceptionRendererInterface|null
     */
    private ?CustomExceptionRendererInterface $customExceptionRenderer = null;

    /**
     * Set custom exceptions renderer.
     *
     * @param CustomExceptionRendererInterface $customExceptionRenderer
     *
     * @return static
     */
    public function withCustomRenderer(CustomExceptionRendererInterface $customExceptionRenderer): static
    {
        $this->customExceptionRenderer = $customExceptionRenderer;

        return $this;
    }

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
        } elseif (!is_null($this->customExceptionRenderer)) {
            return $this->getViewResponse(
                $this->customExceptionRenderer->getView($e, $request),
                $e,
                $request,
            );
        }
    }

    /**
     * Render JSON response for the exception.
     *
     * @param BaseHttpException $e
     * @param Request           $request
     *
     * @return JsonResponse
     */
    public function renderJsonOnly(BaseHttpException $e, Request $request): JsonResponse
    {
        if ($request->wantsJson()) {
            return $this->getJsonResponse($e, $request);
        }
    }

    /**
     * Render validation exception as BaseHttpException (JSON only).
     *
     * @param BaseValidationException $e
     * @param Request                 $request
     *
     * @deprecated Use renderJsonOnly() with WrappedValidadtionException instead.
     *
     * @return JsonResponse|Response|void|null
     */
    public function renderValidationException(BaseValidationException $e, Request $request)
    {
        if ($request->wantsJson()) {
            return $this->render(new WrappedValidationException($e), $request);
        }
    }

    /**
     * Get JSON response for the exception.
     *
     * @param BaseHttpException $e
     * @param Request           $request
     *
     * @return JsonResponse
     */
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

    /**
     * Get view response for the renderable exception.
     *
     * @param RenderableExceptionInterface&BaseHttpException $e
     * @param Request                                        $request
     *
     * @return Response
     */
    protected function getResponse(BaseHttpException & RenderableExceptionInterface $e, Request $request): Response
    {
        return $this->getViewResponse($e->getView($request), $e, $request);
    }

    /**
     * Get view response for the exception.
     *
     * @param View              $view
     * @param BaseHttpException $e
     * @param Request           $request
     *
     * @return Response
     */
    protected function getViewResponse(View $view, BaseHttpException $e, Request $request): Response
    {
        return new Response($view, $e->getHttpCode(), $e->getHeaders());
    }
}
