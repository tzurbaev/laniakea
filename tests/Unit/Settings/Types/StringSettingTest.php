<?php

declare(strict_types=1);

use Laniakea\Settings\Types\NullableStringSetting;
use Laniakea\Settings\Types\StringSetting;

it('should have default value', function () {
    $setting = new StringSetting('testing');

    expect($setting->getDefaultValue())->toBe('testing');
});

it('should validate possible value', function (mixed $value, bool $isValid) {
    $setting = new StringSetting('testing');

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    ['hello world', true],
    [1, false],
    [1.0, false],
    [null, false],
    [[], false],
    [new stdClass(), false],
]);

it('should not validate empty strings', function () {
    $setting = new StringSetting('testing', validateEmpty: false);

    expect($setting->isValid(''))->toBeFalse();
});

it('should validate empty strings', function () {
    $setting = new StringSetting('testing', validateEmpty: true);

    expect($setting->isValid(''))->toBeTrue();
});

it('should generate value for persistance', function () {
    $setting = new StringSetting('testing');

    expect($setting->toPersisted('test', 'hello world', []))->toBe(['test' => 'hello world']);
});

it('should generate value from persisted', function () {
    $setting = new StringSetting('testing');

    expect($setting->fromPersisted('test', 'hello world', []))->toBe(['test' => 'hello world']);
});

it('should have default nullable value', function () {
    $setting = new NullableStringSetting(null);

    expect($setting->getDefaultValue())->toBeNull();
});

it('should validate possible nullable value', function (mixed $value, bool $isValid) {
    $setting = new NullableStringSetting('testing');

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    ['hello world', true],
    [null, true],
    [1, false],
    [1.0, false],
    [[], false],
    [new stdClass(), false],
]);

it('should not validate empty nullable strings', function () {
    $setting = new NullableStringSetting('testing', validateEmpty: false);

    expect($setting->isValid(''))->toBeFalse();
});

it('should validate empty nullable strings', function () {
    $setting = new NullableStringSetting('testing', validateEmpty: true);

    expect($setting->isValid(''))->toBeTrue();
});
