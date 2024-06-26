<?php

declare(strict_types=1);

use Laniakea\Forms\Fields\SelectField;
use Laniakea\Forms\Fields\TextField;

it('should have default input type for text field', function () {
    $field = new TextField('New password');

    expect($field->getSettings())->toBe([
        'attributes' => ['type' => 'text'],
    ]);

    $field->setAttribute('autocomplete', 'new-password')
        ->setAttribute('data-lpignore', 'true')
        ->setSetting('visibility_toggle', true);

    expect($field->getSettings())->toBe([
        'visibility_toggle' => true,
        'attributes' => [
            'type' => 'text',
            'autocomplete' => 'new-password',
            'data-lpignore' => 'true',
        ],
    ]);
});

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

it('should not override existed settings via setSettings method', function () {
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

    $field->setSettings(['optional' => true]);

    expect($field->getSettings()['options'])->toBe([
        ['id' => 'us', 'name' => 'United States'],
        ['id' => 'ca', 'name' => 'Canada'],
        ['id' => 'mx', 'name' => 'Mexico'],
    ])->and($field->getSettings()['optional'])->toBeTrue();
});

it('should not include attributes key if there is no attributes', function () {
    $field = new SelectField('Country', [
        ['id' => 'us', 'name' => 'United States'],
        ['id' => 'ca', 'name' => 'Canada'],
        ['id' => 'mx', 'name' => 'Mexico'],
    ]);

    expect($field->getSettings())->toBe([
        'options' => [
            ['id' => 'us', 'name' => 'United States'],
            ['id' => 'ca', 'name' => 'Canada'],
            ['id' => 'mx', 'name' => 'Mexico'],
        ],
    ]);
});

it('should include attributes key if there is any attributes', function () {
    $field = new SelectField('Country', [
        ['id' => 'us', 'name' => 'United States'],
        ['id' => 'ca', 'name' => 'Canada'],
        ['id' => 'mx', 'name' => 'Mexico'],
    ]);

    $field->setAttribute('class', 'form-control')
        ->setRequired()
        ->setAttributes([
            'data-autocomplete' => 'yes',
            'data-autocomplete-type' => 'country',
        ]);

    expect($field->getSettings())->toBe([
        'options' => [
            ['id' => 'us', 'name' => 'United States'],
            ['id' => 'ca', 'name' => 'Canada'],
            ['id' => 'mx', 'name' => 'Mexico'],
        ],
        'attributes' => [
            'class' => 'form-control',
            'required' => true,
            'data-autocomplete' => 'yes',
            'data-autocomplete-type' => 'country',
        ],
    ]);
});
