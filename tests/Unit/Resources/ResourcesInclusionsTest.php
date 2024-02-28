<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laniakea\Tests\CreatesResourceManager;
use Laniakea\Tests\Workbench\Factories\ArticleFactory;
use Laniakea\Tests\Workbench\Models\Article;
use Laniakea\Tests\Workbench\Models\ArticleCategory;
use Laniakea\Tests\Workbench\Models\ArticleCategoryTag;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Resources\ArticlesResource;
use Laniakea\Tests\Workbench\Resources\Requests\FakeResourceRequest;

uses(RefreshDatabase::class, CreatesResourceManager::class);

it('should load inclusions in paginator', function () {
    /** @var ArticleCategory $category */
    $category = ArticleCategory::create(['name' => 'News']);

    /** @var Article $article */
    $article = ArticleFactory::new()->create(['article_category_id' => $category->id]);
    expect($article->article_category_id)->toBe($category->id);

    $manager = $this->getResourceManager();
    $paginator = $manager->getPaginator(
        new FakeResourceRequest(inclusions: ['category']),
        new ArticlesResource(),
        new ArticlesRepository(),
    );
    expect($paginator->count())->toBe(1);

    /** @var Article $paginatedArticle */
    $paginatedArticle = $paginator->items()[0];
    expect($paginatedArticle)->toBeInstanceOf(Article::class)
        ->and($paginatedArticle->article_category_id)->toBe($category->id)
        ->and($paginatedArticle->relationLoaded('category'))->toBeTrue()
        ->and($paginatedArticle->category->id)->toBe($category->id);
});

it('should load inclusions in lists', function () {
    /** @var ArticleCategory $category */
    $category = ArticleCategory::create(['name' => 'News']);

    /** @var Article $article */
    $article = ArticleFactory::new()->create(['article_category_id' => $category->id]);
    expect($article->article_category_id)->toBe($category->id);

    $manager = $this->getResourceManager();
    $collection = $manager->getList(
        new FakeResourceRequest(inclusions: ['category']),
        new ArticlesResource(),
        new ArticlesRepository(),
    );
    expect($collection->count())->toBe(1);

    /** @var Article $loadedArticle */
    $loadedArticle = $collection->get(0);
    expect($loadedArticle)->toBeInstanceOf(Article::class)
        ->and($loadedArticle->article_category_id)->toBe($category->id)
        ->and($loadedArticle->relationLoaded('category'))->toBeTrue()
        ->and($loadedArticle->category->id)->toBe($category->id);
});

it('should load inclusions in items', function () {
    /** @var ArticleCategory $category */
    $category = ArticleCategory::create(['name' => 'News']);

    /** @var Article $article */
    $article = ArticleFactory::new()->create(['article_category_id' => $category->id]);
    expect($article->article_category_id)->toBe($category->id);

    $manager = $this->getResourceManager();
    /** @var Article $loadedArticle */
    $loadedArticle = $manager->getItem(
        $article->id,
        new FakeResourceRequest(inclusions: ['category']),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    expect($loadedArticle)->toBeInstanceOf(Article::class)
        ->and($loadedArticle->article_category_id)->toBe($category->id)
        ->and($loadedArticle->relationLoaded('category'))->toBeTrue()
        ->and($loadedArticle->category->id)->toBe($category->id);
});

it('should load nested inclusions', function () {
    /** @var ArticleCategory $category */
    $category = ArticleCategory::create(['name' => 'Entertainment']);
    /** @var ArticleCategoryTag $moviesTag */
    $moviesTag = $category->tags()->create(['name' => 'Movies']);
    /** @var ArticleCategoryTag $gamesTag */
    $gamesTag = $category->tags()->create(['name' => 'Games']);

    expect($category->tags()->count())->toBe(2);
    /** @var Article $article */
    $article = ArticleFactory::new()->create(['article_category_id' => $category->id]);

    $manager = $this->getResourceManager();
    /** @var Article $loadedArticle */
    $loadedArticle = $manager->getItem(
        $article->id,
        new FakeResourceRequest(inclusions: ['category.tags']),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    expect($loadedArticle)->toBeInstanceOf(Article::class)
        ->and($loadedArticle->article_category_id)->toBe($category->id)
        ->and($loadedArticle->relationLoaded('category'))->toBeTrue()
        ->and($loadedArticle->category->id)->toBe($category->id)
        ->and($loadedArticle->category->relationLoaded('tags'))->toBeTrue()
        ->and($loadedArticle->category->tags->pluck('id')->toArray())->toBe([$moviesTag->id, $gamesTag->id]);
});
