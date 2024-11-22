<?php

declare(strict_types=1);

use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Route;
use Laniakea\Exceptions\BaseHttpException;
use Laniakea\Resources\Middleware\SetResourceRequest;
use Laniakea\Tests\Workbench\Exceptions\FakeRenderableException;
use Laniakea\Tests\Workbench\Exceptions\FakeTranslatableException;
use Laniakea\Tests\Workbench\Exceptions\FakeValidationExceptionRequest;
use Laniakea\Tests\Workbench\Http\Controllers\ArticlesApiController;
use Laniakea\Versions\Middleware\SetApiVersion;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

Route::group(['prefix' => '/__testing', 'as' => 'testing.', 'middleware' => []], function () {
    Route::group(['prefix' => '/exceptions', 'as' => 'exceptions.'], function () {
        Route::get('/base', fn () => throw new BaseHttpException())->name('base');
        Route::get('/base/message', fn () => throw new BaseHttpException('Example Message'))->name('base.message');
        Route::get('/translated', fn () => throw new FakeTranslatableException())->name('translated');
        Route::post('/validation', fn (FakeValidationExceptionRequest $request) => response()->json(['message' => $request->input('message')]))->name('validation');
        Route::get('/renderable/view', fn () => throw new FakeRenderableException())->name('renderable.view');
        Route::get('/renderable/custom', fn () => throw new BaseHttpException())->name('renderable.custom');
        Route::get('/authentication', fn () => throw new AuthenticationException('Unauthenticated'))->name('authentication');
        Route::get('/access-denied', fn () => throw new AccessDeniedHttpException('Access denied'))->name('accessDenied');
    });

    Route::group(['middleware' => SetResourceRequest::class], function () {
        Route::group(['prefix' => '/api', 'as' => 'api.', 'middleware' => ['api']], function () {
            Route::group(['prefix' => '/v1', 'as' => 'v1.', 'middleware' => [SetApiVersion::class.':v1']], function () {
                Route::group(['prefix' => '/articles', 'as' => 'articles.'], function () {
                    Route::get('/', [ArticlesApiController::class, 'index'])->name('index');
                    Route::get('/{article}', [ArticlesApiController::class, 'show'])->name('show');
                });
            });

            Route::group(['prefix' => '/v2', 'as' => 'v2.', 'middleware' => [SetApiVersion::class.':v2']], function () {
                Route::group(['prefix' => '/articles', 'as' => 'articles.'], function () {
                    Route::get('/', [ArticlesApiController::class, 'index'])->name('index');
                    Route::get('/{article}', [ArticlesApiController::class, 'show'])->name('show');
                });
            });
        });
    });
});
