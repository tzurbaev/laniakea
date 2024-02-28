<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Laniakea\Resources\Interfaces\ResourceManagerInterface;
use Laniakea\Resources\Interfaces\ResourceRequestInterface;
use Laniakea\Tests\Workbench\Interfaces\ArticleTransformerInterface;
use Laniakea\Tests\Workbench\Models\Article;
use Laniakea\Tests\Workbench\Repositories\ArticlesRepository;
use Laniakea\Tests\Workbench\Resources\ArticlesResource;

class ArticlesApiController
{
    public function __construct(private ResourceManagerInterface $manager)
    {
        //
    }

    public function index(ResourceRequestInterface $request, ArticleTransformerInterface $transformer): JsonResponse
    {
        $paginator = $this->manager->getPaginator(
            $request,
            new ArticlesResource(),
            new ArticlesRepository(),
        );

        return response()->json([
            'data' => array_map(fn (Article $article) => $transformer->transform($article), $paginator->items()),
        ]);
    }

    public function show(Article $article, ArticleTransformerInterface $transformer): JsonResponse
    {
        return response()->json([
            'data' => $transformer->transform($article),
        ]);
    }
}
