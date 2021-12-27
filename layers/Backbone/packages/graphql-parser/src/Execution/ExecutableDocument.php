<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use GraphQLByPoP\GraphQLQuery\Schema\ClientSymbols;
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
        $operations = $this->getOperationsToExecute();

        $this->assertOperationToExecuteExists($operations);
        $this->assertFragmentReferencesAreValid($operations);
        $this->assertFragmentsAreUsed($operations);
        $this->assertAllVariablesExist($operations);
        $this->assertAllVariablesAreUsed($operations);
        $this->assertAllVariablesHaveValue($operations);

        // Inject the variable values into the objects
        foreach ($operations as $operation) {
            $this->mergeOperationVariables($operation);
        }
    }

    /**
     * Even though the GraphQL spec allows to execute only 1 Operation,
     * retrieve a list of Operations, allowing to override it
     * for the "multiple query execution" feature.
     *
     * @return OperationInterface[]
     * @throws InvalidRequestException
     *
     * @see https://spec.graphql.org/draft/#sec-Executing-Requests
     */
    protected function getOperationsToExecute(): array
    {
        $operationsToExecute = [];
        // Executing `__ALL`?
        $executeAllOperations = $this->operationName === ClientSymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME;
        foreach ($this->document->getOperations() as $operation) {
            if (!($executeAllOperations || $operation->getName() === $this->operationName)) {
                continue;
            }
            $operationsToExecute[] = $operation;
        }

        return $operationsToExecute;
    }

    /**
     * If there is more than one operation, check that the operationName
     * corresponds to one of them, or is `__ALL`
     *
     * @param OperationInterface[] $operations
     * @throws InvalidRequestException
     */
    protected function assertOperationToExecuteExists(array $operations): void
    {
        if (count($operations) === 1) {
            return;
        }

        if ($operations === []) {
            $errorMessage = $this->operationName !== null ?
                $this->getNoOperationsMatchNameErrorMessage($this->operationName)
                : $this->getNoOperationsProvidedErrorMessage();
            throw new InvalidRequestException(
                throw $errorMessage,
                new Location(1, 1)
            );
        }
    }

    protected function getNoOperationsMatchNameErrorMessage(string $operationName): string
    {
        return \sprintf('Operation with name \'%s\' does not exist', $operationName);
    }

    protected function getNoOperationsProvidedErrorMessage(): string
    {
        return \sprintf('No operations were provided in the query');
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
