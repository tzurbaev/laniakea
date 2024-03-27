<?php

declare(strict_types=1);

use Laniakea\Settings\Types\EnumSetting;
use Laniakea\Settings\Types\NullableEnumSetting;
use Laniakea\Tests\Workbench\Settings\Enums\ArticleCategoryType;
use Laniakea\Tests\Workbench\Settings\Enums\ArticleType;

it('should have default value', function () {
    $setting = new EnumSetting(ArticleCategoryType::NEWS);

    expect($setting->getDefaultValue())->toBe(ArticleCategoryType::NEWS);
});

it('should validate possible value', function (mixed $value, bool $isValid) {
    $setting = new EnumSetting(ArticleCategoryType::NEWS);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [ArticleCategoryType::NEWS, true],
    [ArticleCategoryType::BLOG, true],
    [ArticleCategoryType::VIDEO, true],
    [ArticleCategoryType::OTHER, true],
    ['news', true],
    ['blog', true],
    ['video', true],
    ['other', true],
    [ArticleType::EDITORIAL, false],
    [10, false],
    ['10', false],
    [10.5, false],
    [[], false],
    [new stdClass(), false],
    [null, false],
]);

it('should generate value for persistance', function (mixed $value, mixed $expected) {
    $setting = new EnumSetting(ArticleCategoryType::NEWS);

    expect($setting->toPersisted('test', $value, []))->toBe(['test' => $expected]);
})->with([
    ['value' => ArticleCategoryType::NEWS, 'expected' => ArticleCategoryType::NEWS->value],
    ['value' => ArticleCategoryType::NEWS->value, 'expected' => ArticleCategoryType::NEWS->value],
]);

it('should generate value from persisted', function () {
    $setting = new EnumSetting(ArticleCategoryType::NEWS);

    expect($setting->fromPersisted('test', ArticleCategoryType::NEWS->value, []))
        ->toBe(['test' => ArticleCategoryType::NEWS]);
});

it('should have default nullable value', function (?ArticleCategoryType $default) {
    $setting = new NullableEnumSetting($default, ArticleCategoryType::class);

    expect($setting->getDefaultValue())->toBe($default);
})->with([ArticleCategoryType::NEWS, null]);

it('should validate possible nullable value', function (mixed $value, bool $isValid) {
    $setting = new NullableEnumSetting(ArticleCategoryType::NEWS, ArticleCategoryType::class);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [null, true],
    [ArticleCategoryType::NEWS, true],
    [ArticleCategoryType::BLOG, true],
    [ArticleCategoryType::VIDEO, true],
    [ArticleCategoryType::OTHER, true],
    ['news', true],
    ['blog', true],
    ['video', true],
    ['other', true],
    [ArticleType::EDITORIAL, false],
    [10, false],
    ['10', false],
    [10.5, false],
    [[], false],
    [new stdClass(), false],
]);

it('should generate nullable value for persistance', function (mixed $value, mixed $expected) {
    $setting = new NullableEnumSetting(ArticleCategoryType::NEWS);

    expect($setting->toPersisted('test', $value, []))->toBe(['test' => $expected]);
})->with([
    ['value' => ArticleCategoryType::NEWS, 'expected' => ArticleCategoryType::NEWS->value],
    ['value' => ArticleCategoryType::NEWS->value, 'expected' => ArticleCategoryType::NEWS->value],
    ['value' => null, 'expected' => null],
]);

it('should generate nullable value from persisted', function (mixed $value, mixed $expected) {
    $setting = new NullableEnumSetting(ArticleCategoryType::NEWS);

    expect($setting->fromPersisted('test', $value, []))->toBe(['test' => $expected]);
})->with([
    ['value' => ArticleCategoryType::NEWS->value, 'expected' => ArticleCategoryType::NEWS],
    ['value' => null, 'expected' => null],
]);
