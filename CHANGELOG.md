# Changelog

All notable changes to `laniakea` will be documented in this file.

## Unreleased

- Allow to re-use model instances in Repository's `update` and `delete` methods;
- Add exception wrappers for `Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException` and `Illuminate\Auth\AuthenticationException` exceptions;
- Add separate JSON render method to exceptions renderer;
- Add support for custom exceptions renderer (side-by-side with exception-based renderers);
- Update docblocks.

## v1.0.0 - 2024-06-06

First stable release.
