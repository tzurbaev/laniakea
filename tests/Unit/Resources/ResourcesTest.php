<?php

declare(strict_types=1);

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laniakea\Tests\CreatesResourceManager;
use Laniakea\Tests\Workbench\Factories\ArticleFactory;
use Laniakea\Tests\Workbench\Models\Article;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Resources\ArticlesResource;
use Laniakea\Tests\Workbench\Resources\ArticlesResourceWithCustomItemCriterion;
use Laniakea\Tests\Workbench\Resources\Requests\FakeResourceRequest;

uses(RefreshDatabase::class, CreatesResourceManager::class);

it('should return paginator', function () {
    ArticleFactory::new()->createMany(10);
    expect(Article::count())->toBe(10);

    $manager = $this->getResourceManager();
    $paginator = $manager->getPaginator(
        new FakeResourceRequest(count: 5),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator->currentPage())->toBe(1)
        ->and($paginator->perPage())->toBe(5)
        ->and($paginator->count())->toBe(5)
        ->and($paginator->total())->toBe(10);
});

it('should return list', function () {
    ArticleFactory::new()->createMany(10);
    expect(Article::count())->toBe(10);

    $manager = $this->getResourceManager();
    $collection = $manager->getList(
        new FakeResourceRequest(),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    expect($collection)->toBeInstanceOf(Collection::class)
        ->and($collection)->toHaveCount(10);
});

it('should return item', function () {
    /** @var Article $article */
    $article = ArticleFactory::new()->create();

    $manager = $this->getResourceManager();

    /** @var Article $model */
    $model = $manager->getItem(
        $article->id,
        new FakeResourceRequest(),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    expect($model)->toBeInstanceOf(Article::class)
        ->and($model->id)->toBe($article->id);
});

it('should return item by custom criterion', function () {
    /** @var Article $article */
    $article = ArticleFactory::new()->create(['title' => 'Article Title']);

    $manager = $this->getResourceManager();

    /** @var Article $model */
    $model = $manager->getItem(
        'Article Title',
        new FakeResourceRequest(),
        new ArticlesResourceWithCustomItemCriterion(),
        new ArticlesRepository(),
    );

    expect($model)->toBeInstanceOf(Article::class)
        ->and($model->id)->toBe($article->id);
});

it('should throw ModelNotFound if item was not found', function () {
    $this->getResourceManager()->getItem(
        -1,
        new FakeResourceRequest(),
        new ArticlesResource(),
        new ArticlesRepository(),
    );
})->throws(ModelNotFoundException::class);
