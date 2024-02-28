<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laniakea\Tests\Workbench\Models\Article;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        return [
            'article_category_id' => null,
            'title' => fake()->title,
            'content' => fake()->paragraph,
            'views' => 0,
        ];
    }
}
