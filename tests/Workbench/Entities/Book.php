<?php

declare(strict_types=1);

namespace Laniakea\Tests\Workbench\Entities;

class Book
{
    public function __construct(
        public readonly string $title,
        public readonly string $isbn,
        public ?BookAuthor $author = null,
    ) {
        //
    }

    public function setAuthor(BookAuthor $author): static
    {
        $this->author = $author;

        return $this;
    }
}
