<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int|null $article_category_id
 * @property string $title
 * @property string $content
 * @property int $views
 * @property-read ArticleCategory|null $category
 *
 * @method static int count()
 */
class Article extends Model
{
    protected $fillable = ['article_category_id', 'title', 'content', 'views'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ArticleCategory::class, 'article_category_id');
    }
}
