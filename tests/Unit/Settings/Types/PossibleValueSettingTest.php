<?php

declare(strict_types=1);

use Laniakea\Settings\Types\NullablePossibleValueSetting;
use Laniakea\Settings\Types\PossibleValueSetting;

it('should have default value', function () {
    $setting = new PossibleValueSetting('a', ['a', 'b', 'c']);

    expect($setting->getDefaultValue())->toBe('a');
});

it('should validate possible value', function (mixed $value, bool $isValid) {
    $setting = new PossibleValueSetting('a', ['a', 'b', 'c']);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    ['a', true],
    ['b', true],
    ['c', true],
    ['d', false],
    [null, false],
    [0, false],
    [1, false],
    [0.0, false],
    [1.0, false],
    ['1.0', false],
    ['1', false],
    [[], false],
    [new stdClass(), false],
]);

it('should generate value for persistance', function () {
    $setting = new PossibleValueSetting('a', ['a', 'b', 'c']);

    expect($setting->toPersisted('test', 'b', []))->toBe(['test' => 'b']);
});

it('should generate value from persisted', function () {
    $setting = new PossibleValueSetting('a', ['a', 'b', 'c']);

    expect($setting->fromPersisted('test', 'b', []))->toBe(['test' => 'b']);
});

it('should have default nullable value', function (mixed $value) {
    $setting = new NullablePossibleValueSetting($value, ['a', 'b', 'c']);

    expect($setting->getDefaultValue())->toBe($value);
})->with(['a', null]);

it('should validate possible nullable value', function (mixed $value, bool $isValid) {
    $setting = new NullablePossibleValueSetting('a', ['a', 'b', 'c']);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    ['a', true],
    ['b', true],
    ['c', true],
    [null, true],
    ['d', false],
    [0, false],
    [1, false],
    [0.0, false],
    [1.0, false],
    ['1.0', false],
    ['1', false],
    [[], false],
    [new stdClass(), false],
]);
