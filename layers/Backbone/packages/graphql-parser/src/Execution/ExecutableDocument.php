<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Execution;

use PoPBackbone\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoPBackbone\GraphQLParser\Parser\Ast\Document;
use PoPBackbone\GraphQLParser\Parser\Ast\OperationInterface;
use PoPBackbone\GraphQLParser\Parser\Location;

class ExecutableDocument implements ExecutableDocumentInterface
{
    private ?array $requestedOperations = null;

    public function __construct(
        private Document $document,
        private Context $context,
    ) {
    }

    public function getDocument(): Document
    {
        return $this->document;
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * @throws InvalidRequestException
     */
    public function validateAndInitialize(): void
    {
        $this->requestedOperations = null;

        $this->document->validate();
        $this->assertAllVariablesHaveValue();

        // Obtain the operations that must be executed
        $this->requestedOperations = $this->assertAndGetRequestedOperations(
            $this->context->getOperationName()
        );

        // Inject the variable values into the objects
        foreach ($this->requestedOperations as $operation) {
            $this->propagateContext($operation, $this->context);
        }
    }

    /**
     * @throws InvalidRequestException
     */
    public function reset(): void
    {
        foreach ($this->requestedOperations as $operation) {
            $this->propagateContext($operation, null);
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
    protected function assertAndGetRequestedOperations(): array
    {
        if ($this->context->getOperationName() === '') {
            // It can't be 0, or validation already fails in Document
            if (count($this->document->getOperations()) > 1) {
                throw new InvalidRequestException(
                    $this->getNoOperationNameProvidedErrorMessage(),
                    $this->getNonSpecificLocation()
                );
            }
            // There is exactly 1 operation
            return $this->document->getOperations();
        }

        $requestedOperations = $this->extractRequestedOperations();
        if ($requestedOperations === []) {
            throw new InvalidRequestException(
                $this->getNoOperationMatchesNameErrorMessage($this->context->getOperationName()),
                $this->getNonSpecificLocation()
            );
        }

        // There can be many operations
        return $requestedOperations;
    }

    protected function getNonSpecificLocation(): Location
    {
        return new Location(1, 1);
    }

    /**
     * @return OperationInterface[]
     */
    protected function extractRequestedOperations(): array
    {
        $operationName = $this->context->getOperationName();
        return array_values(array_filter(
            $this->document->getOperations(),
            fn (OperationInterface $operation) => $operation->getName() === $operationName
        ));
    }

    protected function getNoOperationMatchesNameErrorMessage(string $operationName): string
    {
        return \sprintf('Operation with name \'%s\' does not exist', $operationName);
    }

    protected function getNoOperationNameProvidedErrorMessage(): string
    {
        return 'The operation name must be provided';
    }

    /**
     * Validate that all referenced variable are provided a value,
     * or they have a default value. Otherwise, throw an exception.
     *
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesHaveValue(): void
    {
        foreach ($this->document->getOperations() as $operation) {
            foreach ($operation->getVariableReferences($this->document->getFragments()) as $variableReference) {
                /** @var Variable */
                $variable = $variableReference->getVariable();
                if (array_key_exists($variable->getName(), $this->context->getVariableValues())
                    || $variable->hasDefaultValue()
                ) {
                    continue;
                }
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

    protected function propagateContext(OperationInterface $operation, ?Context $context): void
    {
        foreach ($operation->getVariables() as $variable) {
            $variable->setContext($context);
        }
    }

    /**
     * @return OperationInterface[]
     * @throws InvalidRequestException
     */
    public function getRequestedOperations(): array
    {
        if ($this->requestedOperations === null) {
            throw new InvalidRequestException(
                $this->getExecuteValidationErrorMessage(__FUNCTION__),
                $this->getNonSpecificLocation()
            );
        }
        return $this->requestedOperations;
    }

    protected function getExecuteValidationErrorMessage(string $methodName): string
    {
        return \sprintf(
            'Before executing `%s`, must call `%s`',
            $methodName,
            'validateAndInitialize'
        );
    }
}
