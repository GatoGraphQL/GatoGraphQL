<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;

interface ParserInterface
{
    public function parse(string $source): ParsedData;

    /**
     * @param string|int|float|bool|null $value
     */
    public function createLiteral(
        string|int|float|bool|null $value,
        Location $location
    ): Literal;
}
