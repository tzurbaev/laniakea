<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Transformers;

use Laniakea\Tests\Workbench\Entities\BookAuthor;
use Laniakea\Transformers\Attributes\Inclusion;
use Laniakea\Transformers\Interfaces\TransformerResourceInterface;
use Laniakea\Transformers\Resources\CollectionResource;

class AuthorTransformer
{
    public function transform(BookAuthor $author): array
    {
        return [
            'name' => $author->name,
        ];
    }

    #[Inclusion('books')]
    public function getBooks(BookAuthor $author): TransformerResourceInterface
    {
        return new CollectionResource($author->books, new BookTransformer());
    }
}
