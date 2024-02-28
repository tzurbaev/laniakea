<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laniakea\Tests\CreatesResourceManager;
use Laniakea\Tests\Workbench\Factories\ArticleFactory;
use Laniakea\Tests\Workbench\Models\Article;
use Laniakea\Tests\Workbench\Models\ArticleCategory;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Resources\ArticlesResource;
use Laniakea\Tests\Workbench\Resources\Requests\FakeResourceRequest;

uses(RefreshDatabase::class, CreatesResourceManager::class);

it('should filter paginators', function (int $minViews, int $expectedCount) {
    ArticleFactory::new()->createMany([
        ['views' => 5],
        ['views' => 6],
        ['views' => 10],
        ['views' => 12],
    ]);

    expect(Article::count())->toBe(4);

    $manager = $this->getResourceManager();

    $paginator = $manager->getPaginator(
        new FakeResourceRequest(filters: ['min_views' => $minViews]),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    expect($paginator->count())->toBe($expectedCount);
})->with([
    [5, 4],
    [6, 3],
    [10, 2],
    [12, 1],
    [13, 0],
]);

it('should filter paginators with multiple filters', function (?string $category, int $minViews, int $expectedCount) {
    /** @var ArticleCategory $newsCatetory */
    $newsCatetory = ArticleCategory::create(['name' => 'News']);
    /** @var ArticleCategory $sportsCategory */
    $sportsCategory = ArticleCategory::create(['name' => 'Sports']);

    $categories = [
        $newsCatetory->name => $newsCatetory->id,
        $sportsCategory->name => $sportsCategory->id,
    ];

    ArticleFactory::new()->createMany([
        ['article_category_id' => $newsCatetory->id, 'views' => 5],
        ['article_category_id' => $newsCatetory->id, 'views' => 6],
        ['article_category_id' => $newsCatetory->id, 'views' => 10],
        ['article_category_id' => $newsCatetory->id, 'views' => 12],

        ['article_category_id' => $sportsCategory->id, 'views' => 5],
        ['article_category_id' => $sportsCategory->id, 'views' => 6],
        ['article_category_id' => $sportsCategory->id, 'views' => 10],
        ['article_category_id' => $sportsCategory->id, 'views' => 12],
    ]);

    expect(Article::count())->toBe(8);

    $manager = $this->getResourceManager();

    $filters = ['min_views' => $minViews];

    if (!is_null($category)) {
        $filters['category_id'] = $categories[$category] ?? -1; // -1 for non-existed categories
    }

    $paginator = $manager->getPaginator(
        new FakeResourceRequest(filters: $filters),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    expect($paginator->count())->toBe($expectedCount);
})->with([
    [null, 5, 8],
    ['News', 5, 4],
    ['Sports', 10, 2],
    ['Non-existed', 1, 0],
]);

it('should filter lists', function (int $minViews, int $expectedCount) {
    ArticleFactory::new()->createMany([
        ['views' => 5],
        ['views' => 6],
        ['views' => 10],
        ['views' => 12],
    ]);

    expect(Article::count())->toBe(4);

    $manager = $this->getResourceManager();

    $collection = $manager->getList(
        new FakeResourceRequest(filters: ['min_views' => $minViews]),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    expect($collection)->toHaveCount($expectedCount);
})->with([
    [5, 4],
    [6, 3],
    [10, 2],
    [12, 1],
    [13, 0],
]);

it('should filter lists with multiple filters', function (?string $category, int $minViews, int $expectedCount) {
    /** @var ArticleCategory $newsCatetory */
    $newsCatetory = ArticleCategory::create(['name' => 'News']);
    /** @var ArticleCategory $sportsCategory */
    $sportsCategory = ArticleCategory::create(['name' => 'Sports']);

    $categories = [
        $newsCatetory->name => $newsCatetory->id,
        $sportsCategory->name => $sportsCategory->id,
    ];

    ArticleFactory::new()->createMany([
        ['article_category_id' => $newsCatetory->id, 'views' => 5],
        ['article_category_id' => $newsCatetory->id, 'views' => 6],
        ['article_category_id' => $newsCatetory->id, 'views' => 10],
        ['article_category_id' => $newsCatetory->id, 'views' => 12],

        ['article_category_id' => $sportsCategory->id, 'views' => 5],
        ['article_category_id' => $sportsCategory->id, 'views' => 6],
        ['article_category_id' => $sportsCategory->id, 'views' => 10],
        ['article_category_id' => $sportsCategory->id, 'views' => 12],
    ]);

    expect(Article::count())->toBe(8);

    $manager = $this->getResourceManager();

    $filters = ['min_views' => $minViews];

    if (!is_null($category)) {
        $filters['category_id'] = $categories[$category] ?? -1; // -1 for non-existed categories
    }

    $collection = $manager->getList(
        new FakeResourceRequest(filters: $filters),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    expect($collection)->toHaveCount($expectedCount);
})->with([
    [null, 5, 8],
    ['News', 5, 4],
    ['Sports', 10, 2],
    ['Non-existed', 1, 0],
]);
