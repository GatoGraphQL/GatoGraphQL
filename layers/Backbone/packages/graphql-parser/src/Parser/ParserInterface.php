<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;

interface ParserInterface
{
    /**
     * @return array<string,mixed>
     */
    public function parse(string $source): array;

    /**
     * @param string|int|float|bool|null $value
     */
    public function createLiteral(
        string|int|float|bool|null $value,
        Location $location
    ): Literal;
}
