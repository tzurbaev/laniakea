<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Interfaces;

use Laniakea\Tests\Workbench\Models\Article;

interface ArticleTransformerInterface
{
    public function transform(Article $article): array;
}
