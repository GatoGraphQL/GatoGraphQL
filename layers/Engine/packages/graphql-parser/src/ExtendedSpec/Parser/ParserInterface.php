<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\ParserInterface as UpstreamParserInterface;

interface ParserInterface extends UpstreamParserInterface
{
    /**
     * This function must be invoked after running `->parse()`.
     *
     * It produces the list of all the Fields in the query
     * which are referenced via an ObjectResolvedFieldValueReference.
     *
     * Eg: field `id` in:
     *
     *   ```
     *   {
     *     id
     *     echo(value: $__id)
     *   }
     *   ```
     *
     * @return FieldInterface[]
     */
    public function getObjectResolvedFieldValueReferencedFields(): array;
}
