<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Transformers;

use Laniakea\Tests\Workbench\Entities\Book;
use Laniakea\Transformers\Attributes\Inclusion;
use Laniakea\Transformers\Interfaces\TransformerResourceInterface;
use Laniakea\Transformers\Resources\ItemResource;

class BookTransformer
{
    public function transform(Book $book): array
    {
        return [
            'title' => $book->title,
            'isbn' => $book->isbn,
        ];
    }

    #[Inclusion('author')]
    public function getAuthor(Book $book): TransformerResourceInterface
    {
        return new ItemResource($book->author, new AuthorTransformer());
    }
}
