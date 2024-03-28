<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Forms;

use Laniakea\Forms\AbstractForm;
use Laniakea\Forms\Fields\TextField;
use Laniakea\Forms\FormSection;

class ExampleSectionForm extends AbstractForm
{
    public function getMethod(): string
    {
        return 'POST';
    }

    public function getUrl(): string
    {
        return '/example';
    }

    public function getFields(): array
    {
        return [
            'first' => new TextField('First'),
            'second' => new TextField('Second'),
            'third' => new TextField('Third'),
        ];
    }

    public function getValues(): array
    {
        return [];
    }

    public function getSections(): array
    {
        return [
            new FormSection(['second']),
            new FormSection(['third', 'first']),
        ];
    }

    public function getButtons(): array
    {
        return [];
    }
}
