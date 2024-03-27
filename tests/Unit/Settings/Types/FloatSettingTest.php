<?php

declare(strict_types=1);

use Laniakea\Settings\Types\FloatSetting;
use Laniakea\Settings\Types\NullableFloatSetting;

it('should have default value', function () {
    $setting = new FloatSetting(1.0);

    expect($setting->getDefaultValue())->toBe(1.0);
});

it('should validate possible value', function (mixed $value, bool $isValid) {
    $setting = new FloatSetting(1.0);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [0.0, true],
    [1.0, true],
    [1, false],
    [null, false],
    ['1.0', false],
    ['1', false],
    [[], false],
    [new stdClass(), false],
]);

it('should validate possible value from strings', function (mixed $value, bool $isValid) {
    $setting = new FloatSetting(1.0, validateString: true);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [0.0, true],
    [1.0, true],
    ['1.0', true],
    [1, false],
    [null, false],
    ['1', false],
    [[], false],
    [new stdClass(), false],
]);

it('should generate value for persistance', function (bool $validateString, string|float $value, float $expected) {
    $setting = new FloatSetting(1.0, validateString: $validateString);

    expect($setting->toPersisted('test', $value, []))->toBe(['test' => $expected]);
})->with([
    ['validateString' => false, 'value' => 1.2, 'expected' => 1.2],
    ['validateString' => true, 'value' => '1.2', 'expected' => 1.2],
]);

it('should generate value from persisted', function () {
    $setting = new FloatSetting(1.0);

    expect($setting->fromPersisted('test', 1.2, []))->toBe(['test' => 1.2]);
});

it('should have default nullable value', function (mixed $value) {
    $setting = new NullableFloatSetting($value);

    expect($setting->getDefaultValue())->toBe($value);
})->with([1.0, null]);


it('should validate possible nullable value', function (mixed $value, bool $isValid) {
    $setting = new NullableFloatSetting(1.0);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [0.0, true],
    [1.0, true],
    [1, false],
    [null, true],
    ['1.0', false],
    ['1', false],
    [[], false],
    [new stdClass(), false],
]);
