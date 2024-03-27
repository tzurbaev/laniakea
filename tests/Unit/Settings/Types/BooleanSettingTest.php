<?php

declare(strict_types=1);

use Laniakea\Settings\Types\BooleanSetting;
use Laniakea\Settings\Types\NullableBooleanSetting;

it('should have default value', function (bool $default) {
    $setting = new BooleanSetting($default);

    expect($setting->getDefaultValue())->toBe($default);
})->with([true, false]);

it('should validate possible value', function (mixed $value, bool $isValid) {
    $setting = new BooleanSetting(true);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [true, true],
    [false, true],
    [10, false],
    ['10', false],
    [10.5, false],
    [[], false],
    [new stdClass(), false],
    [null, false],
]);

it('should generate value for persistance', function () {
    $setting = new BooleanSetting(true);

    expect($setting->toPersisted('test', true, []))->toBe(['test' => true]);
});

it('should generate value from persisted', function () {
    $setting = new BooleanSetting(true);

    expect($setting->fromPersisted('test', true, []))->toBe(['test' => true]);
});

it('should have default nullable value', function (?bool $default) {
    $setting = new NullableBooleanSetting($default);

    expect($setting->getDefaultValue())->toBe($default);
})->with([true, false, null]);


it('should validate possible nullable value', function (mixed $value, bool $isValid) {
    $setting = new NullableBooleanSetting(true);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [true, true],
    [false, true],
    [null, true],
    [10, false],
    ['10', false],
    [10.5, false],
    [[], false],
    [new stdClass(), false],
]);
