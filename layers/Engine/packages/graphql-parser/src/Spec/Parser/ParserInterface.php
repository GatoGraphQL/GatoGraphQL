<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Literal;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;

interface ParserInterface
{
    public function parse(string $source): Document;

    public function createLiteral(
        string|int|float|bool|null $value,
        Location $location
    ): Literal;
}
