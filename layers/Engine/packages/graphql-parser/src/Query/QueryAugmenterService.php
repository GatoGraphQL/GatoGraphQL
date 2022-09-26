<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Query;

use PoP\GraphQLParser\ExtendedSpec\Constants\QuerySymbols;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\App;

class QueryAugmenterService implements QueryAugmenterServiceInterface
{
    /**
     * If passing operationName=__ALL inside the document in the body,
     * or if passing no operationName but __ALL is defined in the document,
     * then return all operations in the document (except __ALL).
     * Otherwise return null.
     *
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    public function getMultipleQueryExecutionOperations(
        OperationInterface $requestedOperation,
        array $operations,
    ): array {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableMultipleQueryExecution()) {
            return [
                $requestedOperation,
            ];
        }

        /**
         * Starting from the requested Operation,
         * retrieve and accumulate all extra required
         * operations defined via @depends(on: ...)
         */
        return $this->retrieveAndAccumulateMultipleQueryExecutionOperations(
            [
                $requestedOperation,
            ],
            $requestedOperation,
            $operations,
        );
    }

    /**
     * Accumulate all operations defined via @depends(on: ...)
     *
     * @param OperationInterface[] $multipleQueryExecutionOperations
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    protected function retrieveAndAccumulateMultipleQueryExecutionOperations(
        array $multipleQueryExecutionOperations,
        OperationInterface $operation,
        array $operations,
    ): array {
        foreach ($operation->getDirectives() as $directive) {
            /**
             * Check if this Directive is a "OperationDependencyDefiner"
             */
            if (!$this->isOperationDependencyDefinerDirective($directive)) {
                continue;
            }

            /**
             * Get the Argument under which the Depended-upon Operation is defined
             */
            $provideDependedUponOperationNamesArgument = $this->getProvideDependedUponOperationNamesArgument($directive);
            if ($provideDependedUponOperationNamesArgument === null) {
                continue;
            }

            /** @var string|string[] */
            $dependedUponOperationNameOrNames = $provideDependedUponOperationNamesArgument->getValue();
            if (!is_array($dependedUponOperationNameOrNames)) {
                $dependedUponOperationNames = [$dependedUponOperationNameOrNames];
            } else {
                $dependedUponOperationNames = $dependedUponOperationNameOrNames;
            }

            foreach ($dependedUponOperationNames as $dependedUponOperationName) {
                /**
                 * It can't be null, or it will already fail in ->validate
                 *
                 * @var OperationInterface
                 */
                $dependedUponOperation = $this->getOperationByName(
                    $dependedUponOperationName,
                    $operations,
                );
                
                /**
                 * If 2 operations have the same dependencies,
                 * avoid processing it twice
                 */
                if (in_array($dependedUponOperation, $multipleQueryExecutionOperations)) {
                    continue;
                }

                /**
                 * Add the operation to the list, and recursively add
                 * its own operation dependencies
                 */
                $multipleQueryExecutionOperations[] = $dependedUponOperation;
                $multipleQueryExecutionOperations = $this->retrieveAndAccumulateMultipleQueryExecutionOperations(
                    $multipleQueryExecutionOperations,
                    $dependedUponOperation,
                    $operations
                );
            }
        }

        return $multipleQueryExecutionOperations;
    }

    // abstract protected function isOperationDependencyDefinerDirective(Directive $directive): bool;
    // abstract protected function getProvideDependedUponOperationNamesArgument(Directive $directive): ?Argument;

    protected function isOperationDependencyDefinerDirective(Directive $directive): bool
    {
        // @todo Temporary code for testing
        return $directive->getName() === "depends";
    }

    protected function getProvideDependedUponOperationNamesArgument(Directive $directive): ?Argument
    {
        // @todo Temporary code for testing
        return $directive->getArgument('on');
    }

    /**
     * @param OperationInterface[] $operations
     */
    protected function getOperationByName(
        string $operationName,
        array $operations,
    ): ?OperationInterface {
        foreach ($operations as $operation) {
            if ($operation->getName() !== $operationName) {
                continue;
            }
            return $operation;
        }
        return null;
    }
}
