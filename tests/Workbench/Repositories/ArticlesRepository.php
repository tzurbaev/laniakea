<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Repositories;

use Laniakea\Repositories\AbstractRepository;
use Laniakea\Tests\Workbench\Models\Article;

/**
 * @method Article      create(array $attributes)
 * @method Article      update(mixed $id, array $attributes)
 * @method Article      findOrFail(mixed $id, ?callable $callback = null)
 * @method Article|null find(mixed $id, ?callable $callback = null)
 */
class ArticlesRepository extends AbstractRepository
{
    protected function getModel(): string
    {
        return Article::class;
    }
}
