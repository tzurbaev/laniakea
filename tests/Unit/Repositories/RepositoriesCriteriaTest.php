<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laniakea\Repositories\RepositoryQueryBuilder;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Repositories\Criteria\MinViewsCriterion;
use Laniakea\Tests\Workbench\Repositories\Criteria\SearchCriterion;

uses(RefreshDatabase::class);

it('should apply criteria to models', function (string $query, array $expected) {
    $repository = new ArticlesRepository();
    $repository->create(['title' => 'Hello world', 'content' => 'Body']);
    $repository->create(['title' => 'Good morning world', 'content' => 'Body']);
    $repository->create(['title' => 'Good evening world', 'content' => 'Body']);

    $result = $repository->list(
        fn (RepositoryQueryBuilder $queryBuilder) => $queryBuilder->setCriteria([new SearchCriterion(['title'], $query)])
    );

    expect($result->pluck('title')->toArray())->toBe($expected);
})->with([
    ['world', ['Hello world', 'Good morning world', 'Good evening world']],
    ['Good', ['Good morning world', 'Good evening world']],
    ['morning', ['Good morning world']],
    ['evening', ['Good evening world']],
    ['non-existent', []],
]);

it('should apply multiple criteria to models', function (string $query, int $minViews, array $expected) {
    $repository = new ArticlesRepository();
    $repository->create(['title' => 'Hello world', 'content' => 'Body', 'views' => 0]);
    $repository->create(['title' => 'Good morning world', 'content' => 'Body', 'views' => 3]);
    $repository->create(['title' => 'Good evening world', 'content' => 'Body', 'views' => 10]);
    $repository->create(['title' => 'Test Article', 'content' => 'Body', 'views' => 5]);

    $result = $repository->list(
        fn (RepositoryQueryBuilder $queryBuilder) => $queryBuilder->setCriteria([
            new SearchCriterion(['title'], $query),
            new MinViewsCriterion($minViews),
        ])
    );

    expect($result->pluck('title')->toArray())->toBe($expected);
})->with([
    ['world', 0, ['Hello world', 'Good morning world', 'Good evening world']],
    ['world', 3, ['Good morning world', 'Good evening world']],
    ['world', 5, ['Good evening world']],
    ['l', 5, ['Good evening world', 'Test Article']],
    ['Test', 15, []],
]);

it('should apply multiple criteria to models via addCriteria', function (string $query, int $minViews, array $expected) {
    $repository = new ArticlesRepository();
    $repository->create(['title' => 'Hello world', 'content' => 'Body', 'views' => 0]);
    $repository->create(['title' => 'Good morning world', 'content' => 'Body', 'views' => 3]);
    $repository->create(['title' => 'Good evening world', 'content' => 'Body', 'views' => 10]);
    $repository->create(['title' => 'Test Article', 'content' => 'Body', 'views' => 5]);

    $result = $repository->list(function (RepositoryQueryBuilder $queryBuilder) use ($query, $minViews) {
        $queryBuilder->setCriteria([new SearchCriterion(['title'], $query)]);
        expect($queryBuilder->getCriteria())->toHaveCount(1);

        $queryBuilder->addCriteria([new MinViewsCriterion($minViews)]);
        expect($queryBuilder->getCriteria())->toHaveCount(2);
    });

    expect($result->pluck('title')->toArray())->toBe($expected);
})->with([
    ['world', 0, ['Hello world', 'Good morning world', 'Good evening world']],
    ['world', 3, ['Good morning world', 'Good evening world']],
    ['world', 5, ['Good evening world']],
    ['l', 5, ['Good evening world', 'Test Article']],
    ['Test', 15, []],
]);
