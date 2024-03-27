<?php

declare(strict_types=1);

use Laniakea\Settings\Types\EnumArraySetting;
use Laniakea\Tests\Workbench\Settings\Enums\ArticleCategoryType;
use Laniakea\Tests\Workbench\Settings\Enums\ArticleType;

it('should have default value', function (array $default) {
    $setting = new EnumArraySetting($default, ArticleCategoryType::class);

    expect($setting->getDefaultValue())->toBe($default);
})->with([
    [[]],
    [[ArticleCategoryType::NEWS, ArticleCategoryType::BLOG, ArticleCategoryType::VIDEO]],
]);

it('should validate possible value', function (mixed $value) {
    $setting = new EnumArraySetting([], ArticleCategoryType::class);

    expect($setting->isValid($value))->toBeFalse();
})->with([
    [true],
    [false],
    [10],
    ['10'],
    [10.5],
    [new stdClass()],
    [null],
]);

it('should not validate empty arrays', function () {
    $setting = new EnumArraySetting([], ArticleCategoryType::class, validateEmpty: false);

    expect($setting->isValid([]))->toBeFalse();
});

it('should validate empty arrays', function () {
    $setting = new EnumArraySetting([], ArticleCategoryType::class, validateEmpty: true);

    expect($setting->isValid([]))->toBeTrue();
});

it('should validate enum cases', function (array $value) {
    $setting = new EnumArraySetting([], ArticleCategoryType::class);

    expect($setting->isValid($value))->toBeTrue();
})->with([
    [[ArticleCategoryType::NEWS, ArticleCategoryType::BLOG]], // \BackedEnum items
    [[ArticleCategoryType::NEWS->value, ArticleCategoryType::BLOG->value]], // \BackedEnum values
    [[ArticleCategoryType::NEWS, ArticleCategoryType::BLOG->value]], // mixed
]);

it('should not validate invalid cases', function (array $value) {
    $setting = new EnumArraySetting([], ArticleCategoryType::class);

    expect($setting->isValid($value))->toBeFalse();
})->with([
    [['invalid']],
    [[ArticleType::EDITORIAL]],
    [[ArticleType::EDITORIAL->value]],
]);

it('should generate value for persistance', function (array $value, array $expected) {
    $setting = new EnumArraySetting([], ArticleCategoryType::class);

    expect($setting->toPersisted('test', $value, []))->toBe(['test' => $expected]);
})->with([
    [
        'value' => [ArticleCategoryType::NEWS, ArticleCategoryType::BLOG],
        'expected' => [ArticleCategoryType::NEWS->value, ArticleCategoryType::BLOG->value],
    ], // \BackedEnum items
    [
        'value' => [ArticleCategoryType::NEWS->value, ArticleCategoryType::BLOG->value],
        'expected' => [ArticleCategoryType::NEWS->value, ArticleCategoryType::BLOG->value],
    ], // \BackedEnum values
    [
        'value' => [ArticleCategoryType::NEWS, ArticleCategoryType::BLOG->value],
        'expected' => [ArticleCategoryType::NEWS->value, ArticleCategoryType::BLOG->value],
    ], // mixed
]);

it('should generate value from persisted', function () {
    $setting = new EnumArraySetting([], ArticleCategoryType::class);

    expect($setting->fromPersisted('test', [ArticleCategoryType::NEWS->value, ArticleCategoryType::BLOG->value], []))
        ->toBe(['test' => [ArticleCategoryType::NEWS, ArticleCategoryType::BLOG]]);
});
