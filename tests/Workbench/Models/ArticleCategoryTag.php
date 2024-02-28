<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $article_category_id
 * @property string $name
 */
class ArticleCategoryTag extends Model
{
    protected $fillable = ['article_category_id', 'name'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }
}
