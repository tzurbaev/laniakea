<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property-read Collection<ArticleCategoryTag> $tags
 */
class ArticleCategory extends Model
{
    protected $fillable = ['name'];

    public function articles(): HasMany
    {
        return $this->hasMany(Article::class);
    }

    public function tags(): HasMany
    {
        return $this->hasMany(ArticleCategoryTag::class);
    }
}
