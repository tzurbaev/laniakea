<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;
use Laniakea\Resources\ResourceRouteBinder;
use Laniakea\Tests\Workbench\Factories\ArticleFactory;
use Laniakea\Tests\Workbench\Models\Article;
use Laniakea\Tests\Workbench\Resources\Requests\FakeResourceRequest;
use Laniakea\Tests\Workbench\Versions\FakeArticlesRegistrar;

uses(RefreshDatabase::class);

it('should register route model binding', function () {
    $binder = new ResourceRouteBinder();
    $registrar = new FakeArticlesRegistrar();

    expect(Route::getBindingCallback('testArticle'))->toBeNull();

    $registrar->bindRoute($binder);

    expect(Route::getBindingCallback('testArticle'))->toBeInstanceOf(Closure::class);
});

it('should resolve route model binding', function () {
    $binder = new ResourceRouteBinder();
    $registrar = new FakeArticlesRegistrar();

    expect(Route::getBindingCallback('testArticle'))->toBeNull();

    $registrar->bindRoute($binder);

    expect(Route::getBindingCallback('testArticle'))->toBeInstanceOf(Closure::class);

    /** @var Article $article */
    $article = ArticleFactory::new()->create();

    app()->instance(ResourceRequestInterface::class, new FakeResourceRequest());

    /** @var Article $resolved */
    $resolved = call_user_func_array(Route::getBindingCallback('testArticle'), [$article->id]);
    expect($resolved)->toBeInstanceOf(Article::class)
        ->and($resolved->id)->toBe($article->id);
});
