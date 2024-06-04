<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Forms;

use Laniakea\Forms\AbstractForm;
use Laniakea\Forms\Enums\FormButtonType;
use Laniakea\Forms\Fields\SelectField;
use Laniakea\Forms\Fields\TextareaField;
use Laniakea\Forms\Fields\TextField;
use Laniakea\Forms\FormButton;
use Laniakea\Forms\FormSection;
use Laniakea\Tests\Workbench\Models\Article;

class EditArticleForm extends AbstractForm
{
    public function __construct(private readonly Article $article)
    {
        //
    }

    public function getMethod(): string
    {
        return 'PUT';
    }

    public function getUrl(): string
    {
        return '/articles/'.$this->article->id;
    }

    public function getRedirectUrl(): ?string
    {
        return '/home';
    }

    protected function getDefaultSettings(): array
    {
        return [
            'empty_array' => [],
        ];
    }

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

    public function getValues(): array
    {
        return [
            'category_id' => $this->article->article_category_id,
            'title' => $this->article->title,
            'content' => $this->article->content,
        ];
    }

    public function getSections(): array
    {
        return [
            new FormSection(['category_id'], 'Category', 'Category-related settings.'),
            new FormSection(['title', 'content'], 'Article', 'General settings.'),
        ];
    }

    public function getButtons(): array
    {
        return [
            new FormButton(FormButtonType::SUBMIT, 'Submit'),
            new FormButton(FormButtonType::CANCEL, 'Cancel', '/articles'),
        ];
    }
}
