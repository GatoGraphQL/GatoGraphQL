<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\Exception\FeatureNotSupportedException;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\App;
use PoP\Root\Exception\ShouldNotHappenException;

abstract class AbstractExecutableDocument extends ExecutableDocument implements ExecutableDocumentInterface
{
    /**
     * For Multiple Query Execution: the requested
     * operation can load and execute previous Operations.
     * This is the list of all the operations to execute.
     *
     * @var OperationInterface[]|null
     */
    protected ?array $multipleOperationsToExecute = null;

    /**
     * @throws InvalidRequestException
     * @throws FeatureNotSupportedException
     */
    public function validateAndInitialize(): void
    {
        parent::validateAndInitialize();

        $requestedOperation = $this->getRequestedOperation();
        if ($requestedOperation === null) {
            return;
        }

        // Obtain the multiple operations that must be executed
        $this->multipleOperationsToExecute = $this->assertAndGetMultipleRequestedOperations();

        // Inject the variable values into the objects
        foreach ($this->multipleOperationsToExecute as $operation) {
            /**
             * This has already been set in parent method
             */
            if ($operation === $requestedOperation) {
                continue;
            }
            $this->propagateContext($operation, $this->context);
        }
    }

    /**
     * Support the "Multiple Query Execution" feature,
     * providing multiple operations to execute, in the
     * order to be executed (calculated from the dependencies
     * across operations).
     *
     * @return OperationInterface[]
     */
    protected function assertAndGetMultipleRequestedOperations(): array
    {
        /** @var OperationInterface */
        $requestedOperation = $this->getRequestedOperation();

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (
            !$moduleConfiguration->enableMultipleQueryExecution()
            || count($this->document->getOperations()) === 1
        ) {
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
            [],
            $requestedOperation,
            $this->document->getOperations(),
        );
    }

    /**
     * @throws InvalidRequestException
     */
    public function reset(): void
    {
        parent::reset();

        if ($this->multipleOperationsToExecute === null) {
            return;
        }

        /** @var OperationInterface */
        $requestedOperation = $this->getRequestedOperation();
        foreach ($this->multipleOperationsToExecute as $operation) {
            /**
             * This has already been set in parent method
             */
            if ($operation === $requestedOperation) {
                continue;
            }
            $this->propagateContext($operation, null);
        }
    }

    /**
     * @return OperationInterface[]|null
     * @throws InvalidRequestException
     * @throws ShouldNotHappenException When this function is not executed with the expected sequence
     */
    public function getMultipleOperationsToExecute(): ?array
    {
        if (!$this->isValidatedAndInitialized) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Before executing `%s`, must call `validateAndInitialize`', 'graphql-server'),
                    __FUNCTION__,
                )
            );
        }
        return $this->multipleOperationsToExecute;
    }

    /**
     * Accumulate all operations defined via @depends(on: ...) in
     * dependency order (depended-upon operations appear before the
     * operations that depend on them).
     *
     * Implemented as a post-order DFS topological sort: each operation
     * is appended to the result only after all of its depended-upon
     * operations have been visited. A visited-set keyed by object
     * identity ensures each operation is visited at most once, so the
     * total work is O(V + E) over the dependency graph (N operations,
     * E `@depends(on:...)` edges).
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
        $visitedOperationIDs = [];
        foreach ($multipleQueryExecutionOperations as $alreadyAccumulatedOperation) {
            $visitedOperationIDs[spl_object_id($alreadyAccumulatedOperation)] = true;
        }
        $this->collectMultipleQueryExecutionOperationsInTopologicalOrder(
            $operation,
            $operations,
            $visitedOperationIDs,
            $multipleQueryExecutionOperations,
        );
        return $multipleQueryExecutionOperations;
    }

    /**
     * Post-order DFS visitor: recurse into the operation's dependencies
     * first, then append the operation itself. This guarantees the
     * resulting list is in topological order.
     *
     * @param OperationInterface[] $operations
     * @param array<int,bool> $visitedOperationIDs spl_object_id-keyed map of already-visited operations
     * @param OperationInterface[] $multipleQueryExecutionOperations
     */
    protected function collectMultipleQueryExecutionOperationsInTopologicalOrder(
        OperationInterface $operation,
        array $operations,
        array &$visitedOperationIDs,
        array &$multipleQueryExecutionOperations,
    ): void {
        $operationID = spl_object_id($operation);
        if (isset($visitedOperationIDs[$operationID])) {
            return;
        }
        $visitedOperationIDs[$operationID] = true;

        foreach ($this->getDependedUponOperations($operation, $operations) as $dependedUponOperation) {
            $this->collectMultipleQueryExecutionOperationsInTopologicalOrder(
                $dependedUponOperation,
                $operations,
                $visitedOperationIDs,
                $multipleQueryExecutionOperations,
            );
        }

        $multipleQueryExecutionOperations[] = $operation;
    }

    /**
     * Get all the Operations that the passed Operation
     * depends on
     *
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    protected function getDependedUponOperations(
        OperationInterface $operation,
        array $operations,
    ): array {
        $dependedUponOperations = [];
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

            /**
             * Make sure the same Operation is executed just once,
             * even if provided more than once
             */
            $dependedUponOperationNames = array_values(array_unique($dependedUponOperationNames));

            $dependedUponOperations = array_merge(
                $dependedUponOperations,
                array_map(
                    function (string $dependedUponOperationName) use ($operations): OperationInterface {
                        /**
                         * It can't be null, or it will already fail in ->validate
                         *
                         * @var OperationInterface
                         */
                        return $this->getOperationByName(
                            $dependedUponOperationName,
                            $operations,
                        );
                    },
                    $dependedUponOperationNames
                )
            );
        }

        return $dependedUponOperations;
    }

    abstract protected function isOperationDependencyDefinerDirective(Directive $directive): bool;
    abstract protected function getProvideDependedUponOperationNamesArgument(Directive $directive): ?Argument;

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
