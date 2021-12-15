<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser;

class Location
{
    public function __construct(private int $line, private int $column)
    {
    }

    public function getLine(): int
    {
        return $this->line;
    }

    public function getColumn(): int
    {
        return $this->column;
    }


    /**
     * @return array<string, int>
     */
    public function toArray(): array
    {
        return [
            'line'   => $this->getLine(),
            'column' => $this->getColumn(),
        ];
    }
}
