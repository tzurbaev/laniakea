<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Resources;

use Laniakea\Resources\Interfaces\ResourceInterface;
use Laniakea\Resources\Sorters\ColumnSorter;
use Laniakea\Resources\Sorters\VirtualColumnSorter;
use Laniakea\Tests\Workbench\Resources\Filters\ArticleCategoryFilter;
use Laniakea\Tests\Workbench\Resources\Filters\ArticleMinViewsFilter;

class ArticlesResource implements ResourceInterface
{
    public function getFilters(): array
    {
        return [
            'min_views' => ArticleMinViewsFilter::class,
            'category_id' => ArticleCategoryFilter::class,
        ];
    }

    public function getInclusions(): array
    {
        return [
            'category' => ['category'],
            'category.tags' => ['category.tags'],
        ];
    }

    public function getSorters(): array
    {
        return [
            'title' => new ColumnSorter(),
            'user_views' => new VirtualColumnSorter('views'),
        ];
    }
}
