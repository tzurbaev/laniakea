<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laniakea\Tests\Workbench\Factories\AuthorFactory;
use Laniakea\Tests\Workbench\Settings\AuthorSetting;
use Laniakea\Tests\Workbench\Settings\AuthorSettingsDecorator;
use Laniakea\Tests\Workbench\Settings\Enums\ArticleCategoryType;

uses(RefreshDatabase::class);

it('should use default values', function () {
    $author = AuthorFactory::new()->create();

    expect($author->settings)->toBeNull();

    $decorator = $author->getSettingsDecorator();

    expect($decorator)->toBeInstanceOf(AuthorSettingsDecorator::class)
        ->and($decorator->getSettings())->toBe(AuthorSetting::getDefaults());
});

it('should retrieve default values', function (string|BackedEnum $name, mixed $expected) {
    $author = AuthorFactory::new()->create();
    $decorator = $author->getSettingsDecorator();

    expect($decorator->getValue($name))->toBe($expected);
})->with([
    [AuthorSetting::IS_EDITORIAL, false],
    [AuthorSetting::IS_ADMIN->value, false],
    [AuthorSetting::COMMENTS_ENABLED->value, true],
    [AuthorSetting::SIGNATURE, null],
    [AuthorSetting::ALLOWED_TYPES, []],
]);

it('should access values from decorated methods', function () {
    $author = AuthorFactory::new()->create();
    $decorator = $author->getSettingsDecorator();

    expect($decorator->isEditorial())->toBeFalse()
        ->and($decorator->isAdmin())->toBeFalse()
        ->and($decorator->areCommentsEnabled())->toBeTrue();
});

it('should use persisted values', function () {
    $author = AuthorFactory::new()->create([
        'settings' => [
            AuthorSetting::IS_EDITORIAL->value => true,
            AuthorSetting::IS_ADMIN->value => true,
            AuthorSetting::COMMENTS_ENABLED->value => false,
            AuthorSetting::SIGNATURE->value => 'My Signature',
            AuthorSetting::ALLOWED_TYPES->value => [
                ArticleCategoryType::NEWS->value,
                ArticleCategoryType::BLOG->value,
            ],
        ],
    ]);

    expect($author->settings)->not->toBeNull()
        ->and($author->getSettingsDecorator()->getSettings())->toBe([
            AuthorSetting::IS_EDITORIAL->value => true,
            AuthorSetting::IS_ADMIN->value => true,
            AuthorSetting::COMMENTS_ENABLED->value => false,
            AuthorSetting::SIGNATURE->value => 'My Signature',
            AuthorSetting::ALLOWED_TYPES->value => [
                ArticleCategoryType::NEWS,
                ArticleCategoryType::BLOG,
            ],
        ]);
});

it('should retrieve persisted values', function (string|BackedEnum $name, mixed $expected) {
    $author = AuthorFactory::new()->create([
        'settings' => [
            AuthorSetting::IS_EDITORIAL->value => true,
            AuthorSetting::IS_ADMIN->value => true,
            AuthorSetting::COMMENTS_ENABLED->value => false,
            AuthorSetting::SIGNATURE->value => 'My Signature',
            AuthorSetting::ALLOWED_TYPES->value => [
                ArticleCategoryType::NEWS->value,
                ArticleCategoryType::BLOG->value,
            ],
        ],
    ]);

    expect($author->getSettingsDecorator()->getValue($name))->toBe($expected);
})->with([
    [AuthorSetting::IS_EDITORIAL, true],
    [AuthorSetting::IS_ADMIN->value, true],
    [AuthorSetting::COMMENTS_ENABLED->value, false],
    [AuthorSetting::SIGNATURE, 'My Signature'],
    [AuthorSetting::ALLOWED_TYPES, [ArticleCategoryType::NEWS, ArticleCategoryType::BLOG]],
]);

it('should access persisted values from decorated methods', function () {
    $author = AuthorFactory::new()->create([
        'settings' => [
            AuthorSetting::IS_EDITORIAL->value => true,
            AuthorSetting::IS_ADMIN->value => true,
            AuthorSetting::COMMENTS_ENABLED->value => false,
            AuthorSetting::SIGNATURE->value => 'My Signature',
            AuthorSetting::ALLOWED_TYPES->value => [
                ArticleCategoryType::NEWS->value,
                ArticleCategoryType::BLOG->value,
            ],
        ],
    ]);

    $decorator = $author->getSettingsDecorator();

    expect($decorator->isEditorial())->toBeTrue()
        ->and($decorator->isAdmin())->toBeTrue()
        ->and($decorator->areCommentsEnabled())->toBeFalse()
        ->and($decorator->getAllowedTypes())->toBe([
            ArticleCategoryType::NEWS,
            ArticleCategoryType::BLOG,
        ]);
});

it('should update values', function () {
    $author = AuthorFactory::new()->create();

    expect($author->settings)->toBeNull();

    $decorator = $author->getSettingsDecorator();

    expect($decorator)->toBeInstanceOf(AuthorSettingsDecorator::class)
        ->and($decorator->getSettings())->toBe(AuthorSetting::getDefaults());

    $decorator->update([
        AuthorSetting::IS_EDITORIAL->value => true,
        AuthorSetting::IS_ADMIN->value => true,
        AuthorSetting::COMMENTS_ENABLED->value => false,
        AuthorSetting::SIGNATURE->value => 'My Signature',
        AuthorSetting::ALLOWED_TYPES->value => [
            ArticleCategoryType::NEWS->value,
            ArticleCategoryType::BLOG->value,
        ],
    ]);

    expect($decorator->getSettings())->not->toBe(AuthorSetting::getDefaults())
        ->and($decorator->getSettings())->toBe([
            AuthorSetting::IS_EDITORIAL->value => true,
            AuthorSetting::IS_ADMIN->value => true,
            AuthorSetting::COMMENTS_ENABLED->value => false,
            AuthorSetting::SIGNATURE->value => 'My Signature',
            AuthorSetting::ALLOWED_TYPES->value => [
                ArticleCategoryType::NEWS,
                ArticleCategoryType::BLOG,
            ],
        ])
        ->and($author->fresh()->settings)->toBe([
            AuthorSetting::IS_EDITORIAL->value => true,
            AuthorSetting::IS_ADMIN->value => true,
            AuthorSetting::COMMENTS_ENABLED->value => false,
            AuthorSetting::SIGNATURE->value => 'My Signature',
            AuthorSetting::ALLOWED_TYPES->value => [
                ArticleCategoryType::NEWS->value,
                ArticleCategoryType::BLOG->value,
            ],
        ]);
});

it('should retrieve update values', function (string|BackedEnum $name, mixed $expected) {
    $author = AuthorFactory::new()->create();

    expect($author->settings)->toBeNull();

    $decorator = $author->getSettingsDecorator();

    expect($decorator)->toBeInstanceOf(AuthorSettingsDecorator::class)
        ->and($decorator->getSettings())->toBe(AuthorSetting::getDefaults())
        ->and($author->getSettingsDecorator()->getValue($name))->not->toBe($expected);

    $decorator->update([
        AuthorSetting::IS_EDITORIAL->value => true,
        AuthorSetting::IS_ADMIN->value => true,
        AuthorSetting::COMMENTS_ENABLED->value => false,
        AuthorSetting::SIGNATURE->value => 'My Signature',
        AuthorSetting::ALLOWED_TYPES->value => [
            ArticleCategoryType::NEWS->value,
            ArticleCategoryType::BLOG->value,
        ],
    ]);

    expect($author->getSettingsDecorator()->getValue($name))->toBe($expected);
})->with([
    [AuthorSetting::IS_EDITORIAL, true],
    [AuthorSetting::IS_ADMIN->value, true],
    [AuthorSetting::COMMENTS_ENABLED->value, false],
    [AuthorSetting::SIGNATURE, 'My Signature'],
    [AuthorSetting::ALLOWED_TYPES, [ArticleCategoryType::NEWS, ArticleCategoryType::BLOG]],
]);

it('should access updated values from decorated methods', function () {
    $author = AuthorFactory::new()->create();

    expect($author->settings)->toBeNull();

    $decorator = $author->getSettingsDecorator();

    expect($decorator)->toBeInstanceOf(AuthorSettingsDecorator::class)
        ->and($decorator->getSettings())->toBe(AuthorSetting::getDefaults())
        ->and($decorator->isEditorial())->toBeFalse()
        ->and($decorator->isAdmin())->toBeFalse()
        ->and($decorator->areCommentsEnabled())->toBeTrue()
        ->and($decorator->getAllowedTypes())->toBe([]);

    $decorator->update([
        AuthorSetting::IS_EDITORIAL->value => true,
        AuthorSetting::IS_ADMIN->value => true,
        AuthorSetting::COMMENTS_ENABLED->value => false,
        AuthorSetting::SIGNATURE->value => 'My Signature',
        AuthorSetting::ALLOWED_TYPES->value => [
            ArticleCategoryType::NEWS->value,
            ArticleCategoryType::BLOG->value,
        ],
    ]);

    expect($decorator->isEditorial())->toBeTrue()
        ->and($decorator->isAdmin())->toBeTrue()
        ->and($decorator->areCommentsEnabled())->toBeFalse()
        ->and($decorator->getAllowedTypes())->toBe([
            ArticleCategoryType::NEWS,
            ArticleCategoryType::BLOG,
        ]);
});
