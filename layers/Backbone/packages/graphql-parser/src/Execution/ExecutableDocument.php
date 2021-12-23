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
    /**
     * The variable values to execute each operation
     *
     * @var array<string, array<string, mixed>>
     */
    private array $operationVariableValues;

    public function __construct(
        private Document $document,
        private array $variableValues = [],
        private string $operationName = '',
    ) {
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

        $this->operationVariableValues = [];
        foreach ($operationsToExecute as $operation) {
            // Duplicate the document variable values for the operation,
            // as to override with its own default values
            $this->operationVariableValues[$operation->getName()] = $this->variableValues;
            $this->validateOperation($operation);
        }

        foreach ($operationsToExecute as $operation) {
            $this->mergeOperation($operation);
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function validateOperation(OperationInterface $operation): void
    {
        foreach ($operation->getVariableReferences() as $variableReference) {
            if (array_key_exists($variableReference->getName(), $this->operationVariableValues[$operation->getName()])) {
                continue;
            }
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
            if (!$variable->hasDefaultValue()) {
                throw new InvalidRequestException(
                    $this->getVariableHasntBeenSubmittedErrorMessage($variableReference->getName()),
                    $variableReference->getLocation()
                );
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

    protected function mergeOperation(OperationInterface $operation): void
    {
        foreach ($operation->getVariableReferences() as $variableReference) {
            if (array_key_exists($variableReference->getName(), $this->operationVariableValues[$operation->getName()])) {
                continue;
            }
            $variable = $variableReference->getVariable();
            if (!$variable->hasDefaultValue()) {
                continue;
            }
            $this->operationVariableValues[$operation->getName()][$variable->getName()] = $variable->getDefaultValue()->getValue();
        }
    }
}
