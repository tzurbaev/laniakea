# Changelog

All notable changes to `laniakea` will be documented in this file.

## v1.1.1 - 2025-02-28

- Added resource transformers (inspired by [league/fractal](https://github.com/thephpleague/fractal) and [spatie/laravel-fractal](https://github.com/spatie/laravel-fractal));
- Added support for Laravel 12.

## Unreleased

- Allow to re-use model instances in Repository's `update` and `delete` methods;
- Add exception wrappers for `Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException` and `Illuminate\Auth\AuthenticationException` exceptions;
- Add separate JSON render method to exceptions renderer;
- Add support for custom exceptions renderer (side-by-side with exception-based renderers);
- Update docblocks.

## v1.0.0 - 2024-06-06

First stable release.
