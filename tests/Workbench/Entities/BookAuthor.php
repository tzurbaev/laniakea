<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Entities;

class BookAuthor
{
    public function __construct(public readonly string $name, public array $books = [])
    {
        //
    }

    public function addBook(Book $book): static
    {
        $this->books[] = $book;

        return $this;
    }
}
