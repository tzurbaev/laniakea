<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Forms;

use Laniakea\Forms\Fields\SelectField;
use Laniakea\Forms\Fields\TextareaField;
use Laniakea\Forms\Fields\TextField;
use Laniakea\Forms\FormSection;

class CreateArticleFormWithSections extends CreateArticleForm
{
    public function getFields(): array
    {
        return [
            'category_id' => (new SelectField('Article Category', [
                1 => 'News',
                2 => 'Sports',
                3 => 'Entertainment',
            ]))->setHint('Choose article category'),
            'title' => (new TextField('Article Title'))->setHint('Write the title of your article'),
            'content' => (new TextareaField('Article Content'))->setHint('Write your article here'),
        ];
    }

    public function getSections(): array
    {
        return [
            new FormSection(['category_id'], 'Category', 'Category-related settings.'),
            new FormSection(['title', 'content'], 'Article', 'General settings.'),
        ];
    }
}
