<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\InvalidRequestException;
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
        if (!$moduleConfiguration->enableMultipleQueryExecution()
            || count($this->document->getOperations()) === 1
        ) {
            return [
                $requestedOperation,
            ];
        }

        /**
         * Starting from the requested Operation,
         * retrieve and accumulate all extra required
         * operations defined via @dependsOn(operations: ...)
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
     * Accumulate all operations defined via @dependsOn(operations: ...)
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
        /**
         * Add the operation at the beginning of the list,
         * as it must be processed before its depending one
         */
        array_unshift($multipleQueryExecutionOperations, $operation);

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

            /**
             * Because the new operation is added at the beginning of the array,
             * then iterate them from right to left
             */
            $dependedUponOperationNames = array_reverse($dependedUponOperationNames);

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
                 * If some operation is depended-upon by more than
                 * 1 operation, then avoid processing it twice.
                 */
                if (in_array($dependedUponOperation, $multipleQueryExecutionOperations)) {
                    /**
                     * Also place the current operation behind it,
                     * to respect the execution/dependency order
                     * (there are no existing loops, or ->validate
                     * will already have failed).
                     */
                    $multipleQueryExecutionOperations = $this->moveOperationBehindItsDependedUponOperation(
                        $multipleQueryExecutionOperations,
                        $operation,
                        $dependedUponOperation,
                    );
                    continue;
                }

                $multipleQueryExecutionOperations = $this->retrieveAndAccumulateMultipleQueryExecutionOperations(
                    $multipleQueryExecutionOperations,
                    $dependedUponOperation,
                    $operations
                );
            }
        }

        /**
         * 2 Operations could've loaded the same dependency.
         * By doing `array_unique` we will return only 1 instance of each,
         * while already with the right dependency order,
         * so that the depended-upon Operation appears before
         * all of its depending Operations.
         */
        $multipleQueryExecutionOperationsByName = [];
        foreach ($multipleQueryExecutionOperations as $multipleQueryExecutionOperation) {
            $multipleQueryExecutionOperationsByName[$multipleQueryExecutionOperation->getName()] = $multipleQueryExecutionOperation;
        }
        return array_values($multipleQueryExecutionOperationsByName);
    }

    /**
     * Place the current operation behind it, to respect the
     * execution/dependency order (there are no existing loops,
     * or ->validate will already have failed).
     *
     * Don't assume this operation is on the first position,
     * since it could've been moved already by yet another dependency!
     * So search for its position, and place it to the rightmost place.
     *
     * @param OperationInterface[] $multipleQueryExecutionOperations
     * @return OperationInterface[]
     */
    protected function moveOperationBehindItsDependedUponOperation(
        array $multipleQueryExecutionOperations,
        OperationInterface $operation,
        OperationInterface $dependedUponOperation,
    ): array {
        /** @var int */
        $dependedUponOperationPos = array_search(
            $dependedUponOperation,
            $multipleQueryExecutionOperations
        );
        /** @var int */
        $operationPos = array_search(
            $operation,
            $multipleQueryExecutionOperations
        );

        /**
         * If the depended-upon directive is already to the left,
         * then nothing to do.
         */
        if ($dependedUponOperationPos <= $operationPos) {
            return $multipleQueryExecutionOperations;
        }

        /**
         * To reorder:
         * 
         *   1. Place the operation after its depended-upon operation
         *   2. Remove it from the first position
         */
        array_splice($multipleQueryExecutionOperations, $dependedUponOperationPos + 1, 0, [$operation]);
        array_splice($multipleQueryExecutionOperations, $operationPos, 1);

        return $multipleQueryExecutionOperations;
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
