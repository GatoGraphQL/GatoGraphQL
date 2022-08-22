<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels;

use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class GraphQLQueryParsingPayload
{
    /**
     * @param FieldInterface[] $objectResolvedFieldValueReferencedFields List of all the Fields in the query which are referenced via an ObjectResolvedFieldValueReference.
     */
    public function __construct(
        public readonly ExecutableDocument $executableDocument,
        public readonly array $objectResolvedFieldValueReferencedFields,
    ) {
    }
}
