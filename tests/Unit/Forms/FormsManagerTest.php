<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laniakea\Forms\Enums\FormButtonType;
use Laniakea\Forms\FormsManager;
use Laniakea\Forms\Interfaces\FormsManagerInterface;
use Laniakea\Tests\Workbench\Factories\ArticleFactory;
use Laniakea\Tests\Workbench\Forms\CreateArticleForm;
use Laniakea\Tests\Workbench\Forms\CreateArticleFormWithSections;
use Laniakea\Tests\Workbench\Forms\EditArticleForm;
use Laniakea\Tests\Workbench\Forms\ExampleSectionForm;
use Laniakea\Tests\Workbench\Models\Article;
use Laniakea\Tests\Workbench\Models\ArticleCategory;

uses(RefreshDatabase::class);

it('should generate form data', function () {
    /** @var FormsManagerInterface $manager */
    $manager = app(FormsManager::class);
    $form = $manager->getFormData(new CreateArticleForm());

    expect($form['form']['method'])->toBe('POST')
        ->and($form['form']['url'])->toBe('/articles')
        ->and($form['form']['redirect_url'])->toBe('/articles')
        ->and($form['form']['buttons'])->toBe([
            ['type' => FormButtonType::SUBMIT->value, 'label' => 'Submit', 'url' => null, 'settings' => []],
            ['type' => FormButtonType::CANCEL->value, 'label' => 'Cancel', 'url' => '/articles', 'settings' => []],
        ])
        ->and($form['sections'])->toHaveCount(1)
        ->and($form['sections'][0]['fields'])->toHaveCount(2)
        ->and($form['sections'][0]['fields'][0]['id'])->toBe('Field-title-article-title')
        ->and($form['sections'][0]['fields'][0]['name'])->toBe('title')
        ->and($form['sections'][0]['fields'][0]['type'])->toBe('TextField')
        ->and($form['sections'][0]['fields'][0]['label'])->toBe('Article Title')
        ->and($form['sections'][0]['fields'][0]['hint'])->toBe('Write the title of your article')
        ->and($form['sections'][0]['fields'][1]['id'])->toBe('Field-content-article-content')
        ->and($form['sections'][0]['fields'][1]['name'])->toBe('content')
        ->and($form['sections'][0]['fields'][1]['type'])->toBe('TextareaField')
        ->and($form['sections'][0]['fields'][1]['label'])->toBe('Article Content')
        ->and($form['sections'][0]['fields'][1]['hint'])->toBe('Write your article here');
});

it('should generate sections', function () {
    /** @var FormsManagerInterface $manager */
    $manager = app(FormsManagerInterface::class);
    $form = $manager->getFormData(new CreateArticleFormWithSections());

    expect($form['sections'])->toHaveCount(2)
        ->and($form['sections'][0]['fields'])->toHaveCount(1)
        ->and($form['sections'][0]['label'])->toBe('Category')
        ->and($form['sections'][0]['description'])->toBe('Category-related settings.')
        ->and($form['sections'][1]['fields'])->toHaveCount(2)
        ->and($form['sections'][1]['label'])->toBe('Article')
        ->and($form['sections'][1]['description'])->toBe('General settings.');
});

it('should use section fields in section order', function () {
    /** @var FormsManagerInterface $manager */
    $manager = app(FormsManager::class);
    $form = $manager->getFormData(new ExampleSectionForm());

    // Default fields order: first, second, third
    // Sections: 1 (second), 2 (third, first)

    expect($form['sections'])->toHaveCount(2)
        ->and($form['sections'][0]['fields'][0]['name'])->toBe('second')
        ->and($form['sections'][1]['fields'][0]['name'])->toBe('third')
        ->and($form['sections'][1]['fields'][1]['name'])->toBe('first');
});

it('should include form values', function () {
    /** @var ArticleCategory $categor */
    $category = ArticleCategory::create(['name' => 'Sports']);

    /** @var Article $article */
    $article = ArticleFactory::new()->create(['article_category_id' => $category->id]);

    /** @var FormsManagerInterface $manager */
    $manager = app(FormsManager::class);
    $form = $manager->getFormData(new EditArticleForm($article));

    expect($form['form']['values'])->toBe([
        'category_id' => $article->article_category_id,
        'title' => $article->title,
        'content' => $article->content,
    ]);
});
