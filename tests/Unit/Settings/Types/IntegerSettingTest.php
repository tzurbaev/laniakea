<?php

declare(strict_types=1);

use Laniakea\Settings\Types\IntegerSetting;
use Laniakea\Settings\Types\NullableIntegerSetting;

it('should have default value', function () {
    $setting = new IntegerSetting(1);

    expect($setting->getDefaultValue())->toBe(1);
});

it('should validate possible value', function (mixed $value, bool $isValid) {
    $setting = new IntegerSetting(1);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [0, true],
    [1, true],
    [0.0, false],
    [1.0, false],
    [null, false],
    ['1.0', false],
    ['1', false],
    [[], false],
    [new stdClass(), false],
]);

it('should validate possible value from strings', function (mixed $value, bool $isValid) {
    $setting = new IntegerSetting(1, validateString: true);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [0, true],
    [1, true],
    ['1', true],
    [0.0, false],
    [1.0, false],
    [null, false],
    ['1.0', false],
    [[], false],
    [new stdClass(), false],
]);

it('should generate value for persistance', function (bool $validateString, string|int $value, int $expected) {
    $setting = new IntegerSetting(1, validateString: $validateString);

    expect($setting->toPersisted('test', $value, []))->toBe(['test' => $expected]);
})->with([
    ['validateString' => false, 'value' => 2, 'expected' => 2],
    ['validateString' => true, 'value' => '2', 'expected' => 2],
]);

it('should generate value from persisted', function () {
    $setting = new IntegerSetting(1);

    expect($setting->fromPersisted('test', 2, []))->toBe(['test' => 2]);
});

it('should have default nullable value', function (mixed $value) {
    $setting = new NullableIntegerSetting($value);

    expect($setting->getDefaultValue())->toBe($value);
})->with([1, null]);

it('should validate possible nullable value', function (mixed $value, bool $isValid) {
    $setting = new NullableIntegerSetting(1);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [0, true],
    [1, true],
    [null, true],
    [0.0, false],
    [1.0, false],
    ['1.0', false],
    [[], false],
    [new stdClass(), false],
]);
