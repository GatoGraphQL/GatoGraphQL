<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser;

use PoPBackbone\GraphQLParser\Parser\Ast\ArgumentValue\Literal;
use PoPBackbone\GraphQLParser\Parser\Ast\Document;

interface ParserInterface
{
    public function parse(string $source): Document;

    /**
     * @param string|int|float|bool|null $value
     */
    public function createLiteral(
        string|int|float|bool|null $value,
        Location $location
    ): Literal;
}
