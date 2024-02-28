<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Collection;
use Laniakea\Tests\CreatesResourceManager;
use Laniakea\Tests\Workbench\Factories\ArticleFactory;
use Laniakea\Tests\Workbench\Models\Article;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Resources\ArticlesResource;
use Laniakea\Tests\Workbench\Resources\Requests\FakeResourceRequest;

uses(RefreshDatabase::class, CreatesResourceManager::class);

it('should sort resources in paginator', function (string $column, string $direction, array $expected) {
    ArticleFactory::new()->createMany([
        ['title' => 'First Article'],
        ['title' => 'Second Article'],
        ['title' => 'Third Article'],
        ['title' => 'Fourth Article'],
    ]);
    expect(Article::count())->toBe(4);

    $manager = $this->getResourceManager();
    $paginator = $manager->getPaginator(
        new FakeResourceRequest(sortingColumn: $column, sortingDirection: $direction),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    /** @var array<int, Article> $items */
    $items = $paginator->items();

    foreach ($expected as $index => $title) {
        expect($items[$index]->title)->toBe($title);
    }
})->with([
    ['title', 'asc', ['First Article', 'Fourth Article', 'Second Article', 'Third Article']],
    ['title', 'desc', ['Third Article', 'Second Article', 'Fourth Article', 'First Article']],
]);

it('should sort resources in lists', function (string $column, string $direction, array $expected) {
    ArticleFactory::new()->createMany([
        ['title' => 'First Article'],
        ['title' => 'Second Article'],
        ['title' => 'Third Article'],
        ['title' => 'Fourth Article'],
    ]);
    expect(Article::count())->toBe(4);

    $manager = $this->getResourceManager();
    /** @var Collection<int, Article> $items */
    $items = $manager->getList(
        new FakeResourceRequest(sortingColumn: $column, sortingDirection: $direction),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    foreach ($expected as $index => $title) {
        expect($items[$index]->title)->toBe($title);
    }
})->with([
    ['title', 'asc', ['First Article', 'Fourth Article', 'Second Article', 'Third Article']],
    ['title', 'desc', ['Third Article', 'Second Article', 'Fourth Article', 'First Article']],
]);

it('should not sort resources by invalid fields', function () {
    ArticleFactory::new()->createMany([
        ['title' => 'First Article'],
        ['title' => 'Second Article'],
        ['title' => 'Third Article'],
        ['title' => 'Fourth Article'],
    ]);
    expect(Article::count())->toBe(4);

    $manager = $this->getResourceManager();
    /** @var Collection<int, Article> $items */
    $items = $manager->getList(
        new FakeResourceRequest(sortingColumn: 'created_at', sortingDirection: 'desc'),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    $expected = [
        'First Article',
        'Second Article',
        'Third Article',
        'Fourth Article',
    ];

    foreach ($expected as $index => $title) {
        expect($items[$index]->title)->toBe($title);
    }
});

it('should sort resources by virtual columns', function () {
    ArticleFactory::new()->createMany([
        ['title' => 'First Article', 'views' => 5],
        ['title' => 'Second Article', 'views' => 1],
        ['title' => 'Third Article', 'views' => 4],
        ['title' => 'Fourth Article', 'views' => 3],
    ]);
    expect(Article::count())->toBe(4);

    $manager = $this->getResourceManager();
    /** @var Collection<int, Article> $items */
    $items = $manager->getList(
        new FakeResourceRequest(sortingColumn: 'user_views', sortingDirection: 'asc'),
        new ArticlesResource(),
        new ArticlesRepository(),
    );

    $expected = [
        'Second Article',
        'Fourth Article',
        'Third Article',
        'First Article',
    ];

    foreach ($expected as $index => $title) {
        expect($items[$index]->title)->toBe($title);
    }
});
