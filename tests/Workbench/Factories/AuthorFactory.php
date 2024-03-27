<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Laniakea\Tests\Workbench\Models\Author;

/**
 * @method Author create(array $extra = [])
 */
class AuthorFactory extends Factory
{
    protected $model = Author::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name,
            'settings' => null,
        ];
    }
}
