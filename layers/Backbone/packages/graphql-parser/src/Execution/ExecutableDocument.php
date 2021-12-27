<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Document;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class ExecutableDocument implements ExecutableDocumentInterface
{
    private string $operationName;

    public function __construct(
        private Document $document,
        /** @var array<string, mixed> */
        private array $variableValues = [],
        ?string $operationName = null,
    ) {
        $this->operationName = $operationName ?? '';
    }

    /**
     * @return array<string, mixed>
     */
    public function getVariableValues(): array
    {
        return $this->variableValues;
    }

    /**
     * @throws InvalidRequestException
     */
    public function validateAndMerge(): void
    {
        $documentOperations = $this->document->getOperations();

        $this->assertFragmentReferencesAreValid($documentOperations);
        $this->assertFragmentsAreUsed($documentOperations);
        $this->assertAllVariablesExist($documentOperations);
        $this->assertAllVariablesAreUsed($documentOperations);
        $this->assertAllVariablesHaveValue($documentOperations);

        // Obtain the operations that must be executed
        $requestedOperations = $this->getRequestedOperations(
            $documentOperations,
            $this->operationName
        );

        // Inject the variable values into the objects
        foreach ($requestedOperations as $operation) {
            $this->mergeOperationVariables($operation);
        }
    }

    /**
     * Even though the GraphQL spec allows to execute only 1 Operation,
     * retrieve a list of Operations, allowing to override it
     * for the "multiple query execution" feature.
     *
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     * @throws InvalidRequestException
     *
     * @see https://spec.graphql.org/draft/#sec-Executing-Requests
     */
    protected function getRequestedOperations(array $operations, ?string $operationName): array
    {
        $operationCount = count($operations);
        if ($operationCount === 0) {
            throw new InvalidRequestException(
                $this->getNoOperationsProvidedErrorMessage(),
                new Location(1, 1)
            );
        }

        if (empty($operationName)) {
            if ($operationCount > 1) {
                throw new InvalidRequestException(
                    $this->getNoOperationNameProvidedErrorMessage(),
                    new Location(1, 1)
                );
            }
            // There is exactly 1 operation
            return $operations;
        }

        $selectedOperations = $this->getSelectedOperationsToExecute($operations, $operationName);
        if ($selectedOperations === []) {
            throw new InvalidRequestException(
                $this->getNoOperationMatchesNameErrorMessage($operationName),
                new Location(1, 1)
            );
        }

        // There can be many operations
        return $selectedOperations;
    }

    /**
     * @param OperationInterface[] $operations
     * @return OperationInterface[]
     */
    protected function getSelectedOperationsToExecute(array $operations, string $operationName): array
    {
        return array_values(array_filter(
            $operations,
            fn (OperationInterface $operation) => $operation->getName() === $operationName
        ));
    }

    protected function getNoOperationMatchesNameErrorMessage(string $operationName): string
    {
        return \sprintf('Operation with name \'%s\' does not exist', $operationName);
    }

    protected function getNoOperationsProvidedErrorMessage(): string
    {
        return \sprintf('No operations were provided in the query');
    }

    protected function getNoOperationNameProvidedErrorMessage(): string
    {
        return \sprintf('The operation name must be provided');
    }

    /**
     * Validate that all referenced variable are provided a value,
     * or they have a default value. Otherwise, throw an exception.
     *
     * @param OperationInterface[] $operations
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesHaveValue(array $operations): void
    {
        foreach ($operations as $operation) {
            foreach ($operation->getVariableReferences() as $variableReference) {
                $variable = $variableReference->getVariable();
                /**
                 * If $variable is null, then it was not declared in the operation arguments
                 * @see https://graphql.org/learn/queries/#variables
                 */
                if ($variable === null) {
                    throw new InvalidRequestException(
                        $this->getVariableHasntBeenDeclaredErrorMessage($variableReference->getName()),
                        $variableReference->getLocation()
                    );
                }
                if (array_key_exists($variableReference->getName(), $this->variableValues)) {
                    continue;
                }
                if (!$variable->hasDefaultValue()) {
                    throw new InvalidRequestException(
                        $this->getVariableHasntBeenSubmittedErrorMessage($variableReference->getName()),
                        $variableReference->getLocation()
                    );
                }
            }
        }
    }

    protected function getVariableHasntBeenDeclaredErrorMessage(string $variableName): string
    {
        return \sprintf('Variable \'%s\' hasn\'t been declared', $variableName);
    }

    protected function getVariableHasntBeenSubmittedErrorMessage(string $variableName): string
    {
        return \sprintf('Variable \'%s\' hasn\'t been submitted', $variableName);
    }

    protected function mergeOperationVariables(OperationInterface $operation): void
    {
        foreach ($operation->getVariableReferences() as $variableReference) {
            $variable = $variableReference->getVariable();
            $variableName = $variable->getName();
            $variableValue = array_key_exists($variableName, $this->variableValues) ?
                $this->variableValues[$variableName]
                : $variable->getDefaultValue()->getValue();

            $variableReference->getVariable()->setValue($variableValue);
            $variableReference->setValue($variableValue);
        }
    }

    /**
     * @param OperationInterface[] $operations
     * @throws InvalidRequestException
     */
    protected function assertFragmentsAreUsed(array $operations): void
    {
        foreach ($operations as $operation) {
            foreach ($operation->getFragmentReferences() as $fragmentReference) {
                $this->document->getFragment($fragmentReference->getName())?->setUsed(true);
            }
        }

        foreach ($this->document->getFragments() as $fragment) {
            if ($fragment->isUsed()) {
                continue;
            }
            throw new InvalidRequestException(
                $this->getFragmentNotUsedErrorMessage($fragment->getName()),
                $fragment->getLocation()
            );
        }
    }

    protected function getFragmentNotUsedErrorMessage(string $fragmentName): string
    {
        return sprintf('Fragment \'%s\' not used', $fragmentName);
    }

    /**
     * @param OperationInterface[] $operations
     * @throws InvalidRequestException
     */
    protected function assertFragmentReferencesAreValid(array $operations): void
    {
        foreach ($operations as $operation) {
            foreach ($operation->getFragmentReferences() as $fragmentReference) {
                if ($this->document->getFragment($fragmentReference->getName()) !== null) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getFragmentNotDefinedInQueryErrorMessage($fragmentReference->getName()),
                    $fragmentReference->getLocation()
                );
            }
        }
    }

    protected function getFragmentNotDefinedInQueryErrorMessage(string $fragmentName): string
    {
        return sprintf('Fragment \'%s\' not defined in query', $fragmentName);
    }

    /**
     * @param OperationInterface[] $operations
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesExist(array $operations): void
    {
        foreach ($operations as $operation) {
            foreach ($operation->getVariableReferences() as $variableReference) {
                if ($variableReference->getVariable() !== null) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getVariableDoesNotExistErrorMessage($variableReference->getName()),
                    $variableReference->getLocation()
                );
            }
        }
    }

    protected function getVariableDoesNotExistErrorMessage(string $variableName): string
    {
        return sprintf('Variable \'%s\' does not exist', $variableName);
    }

    /**
     * @param OperationInterface[] $operations
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesAreUsed(array $operations): void
    {
        foreach ($operations as $operation) {
            foreach ($operation->getVariables() as $variable) {
                if ($variable->isUsed()) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getVariableNotUsedErrorMessage($variable->getName()),
                    $variable->getLocation()
                );
            }
        }
    }

    protected function getVariableNotUsedErrorMessage(string $variableName): string
    {
        return sprintf('Variable \'%s\' not used', $variableName);
    }
}
