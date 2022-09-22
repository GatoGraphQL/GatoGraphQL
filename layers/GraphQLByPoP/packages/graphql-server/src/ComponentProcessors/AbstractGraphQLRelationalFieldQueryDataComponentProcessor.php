<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ComponentProcessors;

use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldQueryDataComponentProcessor;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use SplObjectStorage;

abstract class AbstractGraphQLRelationalFieldQueryDataComponentProcessor extends AbstractRelationalFieldQueryDataComponentProcessor
{
    /**
     * Extract and re-generate (if needed) the Fields and
     * (Inline) Fragment References from the Document.
     *
     * Regeneration of the AST includes:
     *
     * - Addition of the SuperRoot fields for GraphQL
     * - Wrapping operatins in `self` for Multiple Query Execution
     *
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    protected function getOperationFieldOrFragmentBonds(
        ExecutableDocument $executableDocument,
    ): SplObjectStorage {
        /**
         * Multiple Query Execution: In order to have the fields
         * of the subsequent operations be resolved in the same
         * order as the operations (which is necessary for `@export`
         * to work), then wrap them on a "self" field.
         */
        return $this->getQueryASTTransformationService()->prepareOperationFieldAndFragmentBondsForMultipleQueryExecution(
            $executableDocument->getDocument(),
            $executableDocument->getRequestedOperations(),
            $executableDocument->getDocument()->getFragments(),
        );
    }
}
