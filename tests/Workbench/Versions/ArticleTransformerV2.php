<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Versions;

use Laniakea\Tests\Workbench\Interfaces\ArticleTransformerInterface;
use Laniakea\Tests\Workbench\Models\Article;

class ArticleTransformerV2 extends AbstractTransformer implements ArticleTransformerInterface
{
    public function transform(Article $article): array
    {
        return $this->getTransformedObject([
            'id' => $article->id,
            'title' => $article->title,
            'body' => $article->content,
            'created_at' => $article->created_at->toISO8601String(),
        ], [
            'category' => $article->relationLoaded('category') && !is_null($article->category) ? [
                'id' => $article->category->id,
                'title' => $article->category->name,
            ] : null,
        ]);
    }
}
