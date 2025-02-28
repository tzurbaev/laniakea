<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laniakea\Tests\Workbench\Factories\ArticleFactory;
use Laniakea\Tests\Workbench\Models\Article;
use Laniakea\Tests\Workbench\Models\ArticleCategory;

uses(RefreshDatabase::class);

it('should use v1 transformer', function () {
    ArticleFactory::new()->count(10)->create();
    expect(Article::count())->toBe(10);

    $this->getJson(route('testing.api.v1.articles.index'))
        ->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'content',
                    'created_at',
                ],
            ],
        ]);
});

it('should use v2 transformer', function () {
    ArticleFactory::new()->count(10)->create();
    expect(Article::count())->toBe(10);

    $this->getJson(route('testing.api.v2.articles.index'))
        ->assertStatus(200)
        ->assertJsonCount(10, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'body',
                    'created_at',
                ],
            ],
        ]);
});

it('should paginate', function () {
    ArticleFactory::new()->count(10)->create();
    expect(Article::count())->toBe(10);

    $this->getJson(route('testing.api.v1.articles.index', ['count' => 7]))
        ->assertStatus(200)
        ->assertJsonCount(7, 'data');

    $this->getJson(route('testing.api.v1.articles.index', ['count' => 7, 'page' => 2]))
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');
});

it('should filter articles', function () {
    /** @var ArticleCategory $newsCategory */
    $newsCategory = ArticleCategory::create(['name' => 'News']);

    /** @var ArticleCategory $sportsCategory */
    $sportsCategory = ArticleCategory::create(['name' => 'Sports']);

    ArticleFactory::new()->createMany([
        ['article_category_id' => $newsCategory->id, 'views' => 0],
        ['article_category_id' => $newsCategory->id, 'views' => 3],
        ['article_category_id' => $newsCategory->id, 'views' => 10],

        ['article_category_id' => $sportsCategory->id, 'views' => 0],
        ['article_category_id' => $sportsCategory->id, 'views' => 5],
        ['article_category_id' => $sportsCategory->id, 'views' => 7],
        ['article_category_id' => $sportsCategory->id, 'views' => 12],
    ]);
    expect(Article::count())->toBe(7);

    $this->getJson(route('testing.api.v1.articles.index', ['category_id' => $newsCategory->id]))
        ->assertStatus(200)
        ->assertJsonCount(3, 'data');

    $this->getJson(route('testing.api.v1.articles.index', ['category_id' => $newsCategory->id, 'min_views' => 3]))
        ->assertStatus(200)
        ->assertJsonCount(2, 'data');

    $this->getJson(route('testing.api.v1.articles.index', ['category_id' => $newsCategory->id, 'min_views' => 12]))
        ->assertStatus(200)
        ->assertJsonCount(0, 'data');

    $this->getJson(route('testing.api.v1.articles.index', ['category_id' => $sportsCategory->id]))
        ->assertStatus(200)
        ->assertJsonCount(4, 'data');

    $this->getJson(route('testing.api.v1.articles.index', ['category_id' => $sportsCategory->id, 'min_views' => 0]))
        ->assertStatus(200)
        ->assertJsonCount(4, 'data');

    $this->getJson(route('testing.api.v1.articles.index', ['category_id' => $sportsCategory->id, 'min_views' => 6]))
        ->assertStatus(200)
        ->assertJsonCount(2, 'data');

    $this->getJson(route('testing.api.v1.articles.index', ['category_id' => -1]))
        ->assertStatus(200)
        ->assertJsonCount(0, 'data');
});

it('should include categories', function () {
    /** @var ArticleCategory $newsCategory */
    $newsCategory = ArticleCategory::create(['name' => 'News']);

    /** @var ArticleCategory $sportsCategory */
    $sportsCategory = ArticleCategory::create(['name' => 'Sports']);

    ArticleFactory::new()->createMany([
        ['article_category_id' => $newsCategory->id, 'views' => 0],
        ['article_category_id' => $sportsCategory->id, 'views' => 0],
    ]);
    expect(Article::count())->toBe(2);

    $this->getJson(route('testing.api.v1.articles.index', ['with' => 'category']))
        ->assertStatus(200)
        ->assertJsonCount(2, 'data')
        ->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'title',
                    'content',
                    'created_at',
                    'category' => [
                        'id',
                        'name',
                    ],
                ],
            ],
        ]);
});

test('it should load single article', function () {
    /** @var ArticleCategory $newsCategory */
    $newsCategory = ArticleCategory::create(['name' => 'News']);

    /** @var Article $article */
    $article = ArticleFactory::new()->create(['article_category_id' => $newsCategory->id]);

    $this->getJson(route('testing.api.v1.articles.show', ['article' => $article->id]))
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'content',
                'created_at',
            ],
        ]);
});

test('it should load category within single article', function () {
    /** @var ArticleCategory $newsCategory */
    $newsCategory = ArticleCategory::create(['name' => 'News']);

    /** @var Article $article */
    $article = ArticleFactory::new()->create(['article_category_id' => $newsCategory->id]);

    $this->getJson(route('testing.api.v1.articles.show', ['article' => $article->id, 'with' => 'category']))
        ->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'content',
                'created_at',
                'category' => [
                    'id',
                    'name',
                ],
            ],
        ]);
});
