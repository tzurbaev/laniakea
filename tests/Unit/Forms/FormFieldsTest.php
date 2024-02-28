<?php

declare(strict_types=1);

use Laniakea\Forms\Fields\SelectField;
use Laniakea\Forms\Fields\TextField;

it('should generate text field data', function () {
    $field = new TextField('New password');
    $field->setId('Field-Password')
        ->setHint('Enter new password here.')
        ->setInputType('password')
        ->setAttribute('autocomplete', 'new-password')
        ->setAttribute('data-lpignore', 'true')
        ->setSetting('visibility_toggle', true);

    $actualSettings = $field->getSettings();
    $expectedSettings = [
        'visibility_toggle' => true,
        'attributes' => [
            'type' => 'password',
            'autocomplete' => 'new-password',
            'data-lpignore' => 'true',
        ],
    ];

    ksort($actualSettings);
    ksort($expectedSettings);

    expect($field->getId())->toBe('Field-Password')
        ->and($field->getLabel())->toBe('New password')
        ->and($field->getHint())->toBe('Enter new password here.')
        ->and($actualSettings)->toBe($expectedSettings);
});

it('should generate select field data', function () {
    $field = new SelectField('Country');
    $field->setId('Field-Country')
        ->setHint('Select your country')
        ->setOptions([
            'us' => 'United States',
            'ca' => 'Canada',
            'mx' => 'Mexico',
        ]);

    $actualSettings = $field->getSettings();
    $expectedSettings = [
        'options' => [
            'us' => 'United States',
            'ca' => 'Canada',
            'mx' => 'Mexico',
        ],
    ];

    ksort($actualSettings);
    ksort($expectedSettings);

    expect($field->getId())->toBe('Field-Country')
        ->and($field->getLabel())->toBe('Country')
        ->and($field->getHint())->toBe('Select your country')
        ->and($actualSettings)->toBe($expectedSettings);
});

it('should use any options format for select fields', function () {
    $field = new SelectField('Country');
    $field->setOptions([
        ['id' => 'us', 'name' => 'United States'],
        ['id' => 'ca', 'name' => 'Canada'],
        ['id' => 'mx', 'name' => 'Mexico'],
    ]);

    expect($field->getSettings()['options'])->toBe([
        ['id' => 'us', 'name' => 'United States'],
        ['id' => 'ca', 'name' => 'Canada'],
        ['id' => 'mx', 'name' => 'Mexico'],
    ]);
});

it('should use options list from select field\'s constructor', function () {
    $field = new SelectField('Country', [
        ['id' => 'us', 'name' => 'United States'],
        ['id' => 'ca', 'name' => 'Canada'],
        ['id' => 'mx', 'name' => 'Mexico'],
    ]);

    expect($field->getSettings()['options'])->toBe([
        ['id' => 'us', 'name' => 'United States'],
        ['id' => 'ca', 'name' => 'Canada'],
        ['id' => 'mx', 'name' => 'Mexico'],
    ]);
});
