<?php

declare(strict_types=1);

use Laniakea\Settings\Types\JsonSetting;
use Laniakea\Settings\Types\NullableJsonSetting;
use Laniakea\Tests\Workbench\Resources\ArticlesResource;
use Laniakea\Tests\Workbench\Settings\JsonSerializableValue;

it('should have default value', function (mixed $value) {
    $setting = new JsonSetting($value);

    expect($setting->getDefaultValue())->toBe($value);
})->with([
    [['testing' => true]],
    [(object) ['testing' => true]],
    [new JsonSerializableValue(['testing' => true])],
]);

it('should validate possible value', function (mixed $value, bool $isValid) {
    $setting = new JsonSetting(['testing' => true]);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [['testing' => true], true],
    [(object) ['testing' => true], true],
    [new JsonSerializableValue(['testing' => true]), true],
    [null, false],
    [1, false],
    [1.0, false],
    [false, false],
    ['testing', false],
    ['{"testing": true}', false],
    [new ArticlesResource(), false],
]);

it('should generate value for persistance', function (mixed $value, array $expected) {
    $setting = new JsonSetting([]);

    expect($setting->toPersisted('test', $value, []))->toBe(['test' => json_encode($expected)]);
})->with([
    [['testing' => true], ['testing' => true]],
    [(object) ['testing' => true], ['testing' => true]],
    [new JsonSerializableValue(['testing' => true]), ['testing' => true]],
]);

it('should generate value for persistance with flags and depth', function (mixed $value, int $flags, int $depth = 512, mixed $expected = null) {
    $setting = new JsonSetting([], encodeFlags: $flags, encodeDepth: $depth);

    expect($setting->toPersisted('test', $value, []))
        ->toBe(['test' => $expected ?? json_encode($value, $flags, $depth)]);
})->with([
    ['value' => ['testing' => '<hello>'], 'flags' => JSON_HEX_TAG],
    ['value' => ['testing' => '<hello & world>'], 'flags' => JSON_HEX_TAG | JSON_HEX_AMP],
    ['value' => ['testing' => 'Привет'], 'flags' => JSON_UNESCAPED_UNICODE],
    ['value' => ['first' => ['second' => ['third']]], 'flags' => 0, 'depth' => 1, 'expected' => false], // JSON_ERROR_DEPTH
]);

it('should generate value from persisted as object', function (mixed $value, mixed $expected) {
    $setting = new JsonSetting([]);
    $fromPersisted = $setting->fromPersisted('test', json_encode($value), []);

    expect($fromPersisted['test'])->toBeInstanceOf(stdClass::class)
        ->and((array) $fromPersisted['test'])->toBe($expected);
})->with([
    [['testing' => true], ['testing' => true]],
    [(object) ['testing' => true], ['testing' => true]],
    [new JsonSerializableValue(['testing' => true]), ['testing' => true]],
]);

it('should generate value from persisted as array', function (mixed $value, mixed $expected) {
    $setting = new JsonSetting([], decodeAssociative: true);

    expect($setting->fromPersisted('test', json_encode($value), []))->toBe(['test' => $expected]);
})->with([
    [['testing' => true], ['testing' => true]],
    [(object) ['testing' => true], ['testing' => true]],
    [new JsonSerializableValue(['testing' => true]), ['testing' => true]],
]);

it('should generate value from persisted with flags and depth', function (string $value, int $flags, int $depth = 512, mixed $expected = null) {
    $setting = new JsonSetting([], decodeAssociative: true, decodeFlags: $flags, decodeDepth: $depth);

    expect($setting->fromPersisted('test', $value, []))->toBe(['test' => $expected ?? json_decode($value, true, $depth, $flags)]);
})->with([
    ['value' => '{"testing": "<hello>"}', 'flags' => JSON_HEX_TAG],
    ['value' => '{"testing": "<hello & world>"}', 'flags' => JSON_HEX_TAG | JSON_HEX_AMP],
    ['value' => '{"testing": "Привет"}', 'flags' => JSON_UNESCAPED_UNICODE],
    ['value' => '{"first": {"second": ["third"]}}', 'flags' => 0, 'depth' => 1, 'expected' => null], // JSON_ERROR_DEPTH
]);

it('should have default nullable value', function (mixed $value) {
    $setting = new NullableJsonSetting($value);

    expect($setting->getDefaultValue())->toBe($value);
})->with([
    [['testing' => true]],
    [(object) ['testing' => true]],
    [new JsonSerializableValue(['testing' => true])],
    [null],
]);

it('should validate possible nullable value', function (mixed $value, bool $isValid) {
    $setting = new NullableJsonSetting(null);

    expect($setting->isValid($value))->toBe($isValid);
})->with([
    [['testing' => true], true],
    [(object) ['testing' => true], true],
    [new JsonSerializableValue(['testing' => true]), true],
    [null, true],
    [1, false],
    [1.0, false],
    [false, false],
    ['testing', false],
    ['{"testing": true}', false],
    [new ArticlesResource(), false],
]);

it('should generate nullable value for persistance with flags and depth', function (mixed $value, int $flags, int $depth = 512, mixed $expected = null) {
    $setting = new NullableJsonSetting(null, encodeFlags: $flags, encodeDepth: $depth);

    expect($setting->toPersisted('test', $value, []))
        ->toBe(['test' => is_null($value) ? null : $expected ?? json_encode($value, $flags, $depth)]);
})->with([
    ['value' => ['testing' => '<hello>'], 'flags' => JSON_HEX_TAG],
    ['value' => ['testing' => '<hello & world>'], 'flags' => JSON_HEX_TAG | JSON_HEX_AMP],
    ['value' => ['testing' => 'Привет'], 'flags' => JSON_UNESCAPED_UNICODE],
    ['value' => ['first' => ['second' => ['third']]], 'flags' => 0, 'depth' => 1, 'expected' => false], // JSON_ERROR_DEPTH
    ['value' => null, 'flags' => 0, 'depth' => 512],
]);

it('should generate nullable value from persisted as object', function (mixed $value, mixed $expected) {
    $setting = new NullableJsonSetting(null);
    $fromPersisted = $setting->fromPersisted('test', json_encode($value), []);

    if (is_null($value)) {
        expect($fromPersisted['test'])->toBeNull();
    } else {
        expect($fromPersisted['test'])->toBeInstanceOf(stdClass::class)
            ->and((array) $fromPersisted['test'])->toBe($expected);
    }
})->with([
    [['testing' => true], ['testing' => true]],
    [(object) ['testing' => true], ['testing' => true]],
    [new JsonSerializableValue(['testing' => true]), ['testing' => true]],
    [null, null],
]);

it('should generate nullable value from persisted as array', function (mixed $value, mixed $expected) {
    $setting = new NullableJsonSetting(null, decodeAssociative: true);

    expect($setting->fromPersisted('test', json_encode($value), []))->toBe(['test' => $expected]);
})->with([
    [['testing' => true], ['testing' => true]],
    [(object) ['testing' => true], ['testing' => true]],
    [new JsonSerializableValue(['testing' => true]), ['testing' => true]],
    [null, null],
]);

it('should generate nullable value from persisted with flags and depth', function (mixed $value, int $flags, int $depth = 512, mixed $expected = null) {
    $setting = new NullableJsonSetting(null, decodeAssociative: true, decodeFlags: $flags, decodeDepth: $depth);

    expect($setting->fromPersisted('test', $value, []))->toBe(['test' => is_null($value) ? null : $expected ?? json_decode($value, true, $depth, $flags)]);
})->with([
    ['value' => '{"testing": "<hello>"}', 'flags' => JSON_HEX_TAG],
    ['value' => '{"testing": "<hello & world>"}', 'flags' => JSON_HEX_TAG | JSON_HEX_AMP],
    ['value' => '{"testing": "Привет"}', 'flags' => JSON_UNESCAPED_UNICODE],
    ['value' => '{"first": {"second": ["third"]}}', 'flags' => 0, 'depth' => 1, 'expected' => null], // JSON_ERROR_DEPTH
    ['value' => null, 'flags' => 0],
]);
