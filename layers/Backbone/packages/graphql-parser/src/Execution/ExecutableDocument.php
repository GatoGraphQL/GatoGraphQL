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
        private array $variableValues = [],
        ?string $operationName = null,
    ) {
        $this->operationName = $operationName ?? '';
    }

    /**
     * @throws InvalidRequestException
     */
    public function validateAndMerge(): void
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

        if ($operationsToExecute === []) {
            throw new InvalidRequestException('saranga', new Location(0, 0));
        }

        // Validate all variables are satisfied
        $this->validateOperationVariables($operationsToExecute);
        $this->assertFragmentReferencesValid($operationsToExecute);
        $this->assertFragmentsUsed($operationsToExecute);
        $this->assertAllVariablesExists($operationsToExecute);
        $this->assertAllVariablesUsed($operationsToExecute);

        // Inject the variable values into the objects
        foreach ($operationsToExecute as $operation) {
            $this->mergeOperationVariables($operation);
        }
    }

    /**
     * Validate that all referenced variable are provided a value,
     * or they have a default value. Otherwise, throw an exception.
     *
     * @param OperationInterface[] $operations
     * @throws InvalidRequestException
     */
    protected function validateOperationVariables(array $operations): void
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
    protected function assertFragmentsUsed(array $operations): void
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
    protected function assertFragmentReferencesValid(array $operations): void
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
    protected function assertAllVariablesExists(array $operations): void
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
    protected function assertAllVariablesUsed(array $operations): void
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
