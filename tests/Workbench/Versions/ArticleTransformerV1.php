<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Versions;

use Laniakea\Tests\Workbench\Interfaces\ArticleTransformerInterface;
use Laniakea\Tests\Workbench\Models\Article;

class ArticleTransformerV1 extends AbstractTransformer implements ArticleTransformerInterface
{
    public function transform(Article $article): array
    {
        return $this->getTransformedObject([
            'id' => $article->id,
            'title' => $article->title,
            'content' => $article->content,
            'created_at' => $article->created_at->format('d.m.Y H:i:s'),
        ], [
            'category' => $article->relationLoaded('category') && !is_null($article->category) ? [
                'id' => $article->category->id,
                'name' => $article->category->name,
            ] : null,
        ]);
    }
}
