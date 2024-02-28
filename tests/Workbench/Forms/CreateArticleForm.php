<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Forms;

use Laniakea\Forms\AbstractForm;
use Laniakea\Forms\Enums\FormButtonType;
use Laniakea\Forms\Fields\TextareaField;
use Laniakea\Forms\Fields\TextField;
use Laniakea\Forms\FormButton;

class CreateArticleForm extends AbstractForm
{
    public function getMethod(): string
    {
        return 'POST';
    }

    public function getUrl(): string
    {
        return '/articles';
    }

    public function getRedirectUrl(): ?string
    {
        return '/articles';
    }

    public function getFields(): array
    {
        return [
            'title' => (new TextField('Article Title'))->setHint('Write the title of your article'),
            'content' => (new TextareaField('Article Content'))->setHint('Write your article here'),
        ];
    }

    public function getValues(): array
    {
        return [];
    }

    public function getSections(): array
    {
        return [];
    }

    public function getButtons(): array
    {
        return [
            new FormButton(FormButtonType::SUBMIT, 'Submit'),
            new FormButton(FormButtonType::CANCEL, 'Cancel', '/articles'),
        ];
    }
}
