<?php

declare(strict_types=1);

use Laniakea\Tests\CreatesSettingsValues;
use Laniakea\Tests\Workbench\Settings\AuthorSetting;
use Laniakea\Tests\Workbench\Settings\AuthorSettingWithRequestPaths;
use Laniakea\Tests\Workbench\Settings\Enums\ArticleCategoryType;

uses(CreatesSettingsValues::class);

it('should generate default values', function () {
    expect($this->createSettingsValues()->getDefaults(AuthorSetting::class))
        ->toBe(AuthorSetting::getDefaults());
});

it('should generate values for persistance', function () {
    $values = [
        AuthorSetting::IS_EDITORIAL->value => true,
        AuthorSetting::IS_ADMIN->value => true,
        AuthorSetting::COMMENTS_ENABLED->value => false,
        AuthorSetting::SIGNATURE->value => 'My Signature',
        AuthorSetting::ALLOWED_TYPES->value => [
            ArticleCategoryType::NEWS,
            ArticleCategoryType::BLOG->value,
        ],
    ];

    expect($this->createSettingsValues()->toPersisted(AuthorSetting::class, $values))
        ->toBe([
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

it('should generate values for persistance with request paths', function () {
    $values = [
        'roles' => [
            'is_editorial' => true, // AuthorSettingWithRequestPaths::IS_EDITORIAL
            'is_admin' => true, // AuthorSettingWithRequestPaths::IS_ADMIN
        ],
        'comments' => [
            'enabled' => false, // AuthorSettingWithRequestPaths::COMMENTS_ENABLED
        ],
        'misc' => [
            'signature' => 'My Signature', // AuthorSettingWithRequestPaths::SIGNATURE
            'category_types' => [
                ArticleCategoryType::NEWS,
                ArticleCategoryType::BLOG->value,
            ], // AuthorSettingWithRequestPaths::ALLOWED_TYPES
        ],
    ];

    expect($this->createSettingsValues()->toPersisted(AuthorSettingWithRequestPaths::class, $values))
        ->toBe([
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

it('should generate values for persistance with request paths ignored', function () {
    $values = [
        AuthorSetting::IS_EDITORIAL->value => true,
        AuthorSetting::IS_ADMIN->value => true,
        AuthorSetting::COMMENTS_ENABLED->value => false,
        AuthorSetting::SIGNATURE->value => 'My Signature',
        AuthorSetting::ALLOWED_TYPES->value => [
            ArticleCategoryType::NEWS->value,
            ArticleCategoryType::BLOG->value,
        ],
    ];

    expect($this->createSettingsValues()->toPersisted(AuthorSettingWithRequestPaths::class, $values, ignoreRequestPaths: true))
        ->toBe([
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

it('should not generate values for persistance without request paths', function () {
    $values = [
        AuthorSetting::IS_EDITORIAL->value => true,
        AuthorSetting::IS_ADMIN->value => true,
        AuthorSetting::COMMENTS_ENABLED->value => false,
        AuthorSetting::SIGNATURE->value => 'My Signature',
        AuthorSetting::ALLOWED_TYPES->value => [
            ArticleCategoryType::NEWS->value,
            ArticleCategoryType::BLOG->value,
        ],
    ];

    expect($this->createSettingsValues()->toPersisted(AuthorSettingWithRequestPaths::class, $values))
        ->toBe([]);
});

it('should generate values from persisted', function () {
    $values = [
        AuthorSetting::IS_EDITORIAL->value => true,
        AuthorSetting::IS_ADMIN->value => true,
        AuthorSetting::COMMENTS_ENABLED->value => false,
        AuthorSetting::SIGNATURE->value => 'My Signature',
        AuthorSetting::ALLOWED_TYPES->value => [
            ArticleCategoryType::NEWS->value,
            ArticleCategoryType::BLOG->value,
        ],
    ];

    expect($this->createSettingsValues()->fromPersisted(AuthorSetting::class, $values))
        ->toBe([
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
