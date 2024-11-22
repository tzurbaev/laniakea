<?php

declare(strict_types=1);

use Laniakea\Exceptions\BaseHttpException;
use Laniakea\Exceptions\ValidationException;
use Laniakea\Exceptions\Wrappers\WrappedAccessDeniedHttpException;
use Laniakea\Exceptions\Wrappers\WrappedAuthenticationException;
use Laniakea\Tests\Workbench\Exceptions\FakeRenderableException;
use Laniakea\Tests\Workbench\Exceptions\FakeTranslatableException;

it('should render base exception', function () {
    $this->getJson(route('testing.exceptions.base'))
        ->assertStatus(BaseHttpException::HTTP_CODE)
        ->assertExactJson([
            'error' => [
                'message' => BaseHttpException::MESSAGE,
                'original_message' => BaseHttpException::MESSAGE,
                'code' => BaseHttpException::ERROR_CODE,
                'meta' => [],
            ],
        ]);
});

it('should render base exception with custom message', function () {
    $this->getJson(route('testing.exceptions.base.message'))
        ->assertStatus(BaseHttpException::HTTP_CODE)
        ->assertExactJson([
            'error' => [
                'message' => 'Example Message',
                'original_message' => 'Example Message',
                'code' => BaseHttpException::ERROR_CODE,
                'meta' => [],
            ],
        ]);
});

it('should render exception with translated message', function () {
    $this->getJson(route('testing.exceptions.translated'))
        ->assertStatus(FakeTranslatableException::HTTP_CODE)
        ->assertExactJson([
            'error' => [
                'message' => trans('testing::exceptions.'.FakeTranslatableException::ERROR_CODE.'.message'),
                'original_message' => FakeTranslatableException::MESSAGE,
                'code' => FakeTranslatableException::ERROR_CODE,
                'meta' => [],
            ],
        ]);
});

it('should render validation exception with wrapper exception', function () {
    $this->postJson(route('testing.exceptions.validation'))
        ->assertStatus(ValidationException::HTTP_CODE)
        ->assertExactJson([
            'error' => [
                'message' => 'The message field is required.',
                'original_message' => ValidationException::MESSAGE,
                'code' => ValidationException::ERROR_CODE,
                'meta' => [
                    'errors' => [
                        'message' => ['The message field is required.'],
                    ],
                ],
            ],
        ]);
});

it('should render Laravel/Symfony exceptions with wrappers', function (string $route, string $message, string $exception) {
    $this->getJson(route('testing.exceptions.'.$route))
        ->assertStatus($exception::HTTP_CODE)
        ->assertExactJson([
            'error' => [
                'message' => $message,
                'original_message' => $message,
                'code' => $exception::ERROR_CODE,
                'meta' => [],
            ],
        ]);
})->with([
    ['route' => 'authentication', 'message' => 'Unauthenticated', 'exception' => WrappedAuthenticationException::class],
    ['route' => 'accessDenied', 'message' => 'Access denied', 'exception' => WrappedAccessDeniedHttpException::class],
]);

it('should render exception view for non-JSON requests', function () {
    $this->get(route('testing.exceptions.renderable.view'))
        ->assertStatus(FakeRenderableException::HTTP_CODE)
        ->assertSee('Rendered with exception renderer')
        ->assertSee('Message (exception): '.FakeRenderableException::MESSAGE)
        ->assertSee('Code (exception): '.FakeRenderableException::ERROR_CODE);
});

it('should not render exception view for JSON requests', function () {
    $this->getJson(route('testing.exceptions.renderable.view'))
        ->assertStatus(FakeRenderableException::HTTP_CODE)
        ->assertDontSee('Rendered with exception renderer')
        ->assertDontSee('Message (exception): '.FakeRenderableException::MESSAGE)
        ->assertDontSee('Code (exception): '.FakeRenderableException::ERROR_CODE)
        ->assertExactJson([
            'error' => [
                'message' => FakeRenderableException::MESSAGE,
                'original_message' => FakeRenderableException::MESSAGE,
                'code' => FakeRenderableException::ERROR_CODE,
                'meta' => [],
            ],
        ]);
});

it('should render exception views for non-JSON requests via custom renderer', function () {
    $this->get(route('testing.exceptions.renderable.custom'))
        ->assertStatus(BaseHttpException::HTTP_CODE)
        ->assertSee('Rendered with custom renderer')
        ->assertSee('Message (custom): '.BaseHttpException::MESSAGE)
        ->assertSee('Code (custom): '.BaseHttpException::ERROR_CODE);
});
