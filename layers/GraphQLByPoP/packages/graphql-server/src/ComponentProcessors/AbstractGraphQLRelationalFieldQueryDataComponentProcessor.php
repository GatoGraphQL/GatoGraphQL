<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ComponentProcessors;

use GraphQLByPoP\GraphQLServer\QueryResolution\GraphQLQueryASTTransformationServiceInterface;
use PoPAPI\API\ComponentProcessors\AbstractRelationalFieldQueryDataComponentProcessor;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use SplObjectStorage;

abstract class AbstractGraphQLRelationalFieldQueryDataComponentProcessor extends AbstractRelationalFieldQueryDataComponentProcessor
{
    private ?GraphQLQueryASTTransformationServiceInterface $graphQLQueryASTTransformationService = null;

    final protected function getGraphQLQueryASTTransformationService(): GraphQLQueryASTTransformationServiceInterface
    {
        if ($this->graphQLQueryASTTransformationService === null) {
            /** @var GraphQLQueryASTTransformationServiceInterface */
            $graphQLQueryASTTransformationService = $this->instanceManager->getInstance(GraphQLQueryASTTransformationServiceInterface::class);
            $this->graphQLQueryASTTransformationService = $graphQLQueryASTTransformationService;
        }
        return $this->graphQLQueryASTTransformationService;
    }

    /**
     * Convert the operations to include the SuperRoot Fields.
     *
     * Under the "Sequential Pass" Multiple Query Execution strategy, the
     * engine drives one operation at a time via the
     * `multiple-query-execution-current-operation` app-state key; when
     * set, we scope the operation list to that single operation so each
     * engine pass builds and drains the tree for one operation only.
     *
     * Pass `$forAllOperations=true` to bypass that per-op filter — used
     * for cache-population paths that need to visit every operation.
     *
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    protected function getOperationFieldOrFragmentBonds(
        ExecutableDocument $executableDocument,
        bool $forAllOperations = false,
    ): SplObjectStorage {
        $document = $executableDocument->getDocument();

        if ($forAllOperations) {
            /** @var OperationInterface[] */
            $operations = $executableDocument->getMultipleOperationsToExecute();
        } else {
            /** @var OperationInterface|null */
            $currentMQEOperation = App::getState('multiple-query-execution-current-operation');
            if ($currentMQEOperation !== null) {
                $operations = [$currentMQEOperation];
            } else {
                /** @var OperationInterface[] */
                $operations = $executableDocument->getMultipleOperationsToExecute();
            }
        }

        return $this->getGraphQLQueryASTTransformationService()->prepareOperationFieldAndFragmentBondsForExecution(
            $document,
            $operations,
            $document->getFragments(),
        );
    }
}
