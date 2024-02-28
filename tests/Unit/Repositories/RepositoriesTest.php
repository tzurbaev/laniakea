<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Laniakea\Tests\Workbench\Models\Article;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;

uses(RefreshDatabase::class);

it('should create fresh models', function () {
    expect(Article::count())->toBe(0);

    $repository = new ArticlesRepository();
    $article = $repository->create(['title' => 'Test Article', 'content' => 'Test Article Body']);

    expect($article)->toBeInstanceOf(Article::class)
        ->and($article->title)->toBe('Test Article')
        ->and($article->content)->toBe('Test Article Body')
        ->and(Article::count())->toBe(1);
});

it('should find models by id', function () {
    $repository = new ArticlesRepository();
    $article = $repository->create(['title' => 'Test Article', 'content' => 'Test Article Body']);

    expect($repository->find($article->id)->id)->toBe($article->id)
        ->and($repository->findOrFail($article->id)->id)->toBe($article->id);
});

it('should not find non-existed models by id', function () {
    $repository = new ArticlesRepository();

    expect($repository->find(1))->toBe(null);
});

it('should throw ModelNotFound exception for non-existed models', function () {
    $repository = new ArticlesRepository();
    $repository->findOrFail(1);
})->throws(ModelNotFoundException::class);

it('should update models', function () {
    $repository = new ArticlesRepository();
    $article = $repository->create(['title' => 'Test Article', 'content' => 'Test Article Body']);
    expect($article->title)->toBe('Test Article')
        ->and($article->content)->toBe('Test Article Body');

    $updatedArticle = $repository->update($article->id, ['title' => 'Updated Article', 'content' => 'Updated Article Body']);
    expect($updatedArticle->title)->toBe('Updated Article')
        ->and($updatedArticle->content)->toBe('Updated Article Body');

    $freshArticle = $repository->find($article->id);

    expect($freshArticle->title)->toBe('Updated Article')
        ->and($freshArticle->content)->toBe('Updated Article Body');
});

it('should throw ModelNotFound exception while trying to update non-existed models', function () {
    $repository = new ArticlesRepository();
    $repository->update(-1, ['title' => 'Updated Article', 'content' => 'Updated Article Body']);
})->throws(ModelNotFoundException::class);

it('should delete models', function () {
    $repository = new ArticlesRepository();
    $article = $repository->create(['title' => 'Test Article', 'content' => 'Test Article Body']);

    $repository->delete($article->id);

    expect($repository->find($article->id))->toBe(null);
});

it('should throw ModelNotFound exception while trying to delete non-existed models', function () {
    $repository = new ArticlesRepository();
    $repository->delete(1);
})->throws(ModelNotFoundException::class);

it('should list models', function () {
    $repository = new ArticlesRepository();
    $repository->create(['title' => 'Test Article 1', 'content' => 'Test Article Body 1']);
    $repository->create(['title' => 'Test Article 2', 'content' => 'Test Article Body 2']);

    $articles = $repository->list();
    expect($articles)->toBeInstanceOf(Collection::class)
        ->and($articles->count())->toBe(2)
        ->and($articles->first()->title)->toBe('Test Article 1')
        ->and($articles->last()->title)->toBe('Test Article 2');
});

it('should paginate models', function () {
    $repository = new ArticlesRepository();
    $repository->create(['title' => 'Test Article 1', 'content' => 'Test Article Body 1']);
    $repository->create(['title' => 'Test Article 2', 'content' => 'Test Article Body 2']);

    $paginator = $repository->paginate(1, 1);
    expect($paginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($paginator->count())->toBe(1)
        ->and($paginator->currentPage())->toBe(1)
        ->and($paginator->perPage())->toBe(1)
        ->and($paginator->total())->toBe(2)
        ->and($paginator->items()[0]->title)->toBe('Test Article 1');

    $secondPaginator = $repository->paginate(2, 1);
    expect($secondPaginator)->toBeInstanceOf(LengthAwarePaginator::class)
        ->and($secondPaginator->count())->toBe(1)
        ->and($secondPaginator->currentPage())->toBe(2)
        ->and($secondPaginator->perPage())->toBe(1)
        ->and($secondPaginator->total())->toBe(2)
        ->and($secondPaginator->items()[0]->title)->toBe('Test Article 2');
});
