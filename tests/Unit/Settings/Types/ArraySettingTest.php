<?php

declare(strict_types=1);

use Laniakea\Settings\Types\ArraySetting;

it('should have default value', function (array $default) {
    $setting = new ArraySetting($default);

    expect($setting->getDefaultValue())->toBe($default);
})->with([
    [[]],
    [['a', 'b', 'c']],
]);

it('should validate possible value', function (mixed $value, bool $isValid) {
    $setting = new ArraySetting([]);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [['a', 'b', 'c'], true],
    [true, false],
    [false, false],
    [10, false],
    ['10', false],
    [10.5, false],
    [new stdClass(), false],
    [null, false],
]);

it('should not validate empty arrays', function () {
    $setting = new ArraySetting([], validateEmpty: false);

    expect($setting->isValid([]))->toBeFalse();
});

it('should validate empty arrays', function () {
    $setting = new ArraySetting([], validateEmpty: true);

    expect($setting->isValid([]))->toBeTrue();
});

it('should validate allowed cases', function (array $cases, array $value, bool $isValid) {
    $setting = new ArraySetting([], cases: $cases);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [
        'cases' => ['a', 'b', 'c'],
        'value' => ['a', 'b'],
        'isValid' => true,
    ],
    [
        'cases' => ['a', 'b', 'c'],
        'value' => ['a', 'b', 'd'],
        'isValid' => false,
    ],
]);

it('should generate value for persistance', function () {
    $setting = new ArraySetting([]);

    expect($setting->toPersisted('test', ['a', 'b', 'c'], []))->toBe(['test' => ['a', 'b', 'c']]);
});

it('should generate value from persisted', function () {
    $setting = new ArraySetting([]);

    expect($setting->fromPersisted('test', ['a', 'b', 'c'], []))->toBe(['test' => ['a', 'b', 'c']]);
});
