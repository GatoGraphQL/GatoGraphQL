<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

use PoP\GraphQLParser\Error\GraphQLErrorMessageProviderInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Facades\Error\GraphQLErrorMessageProviderFacade;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Services\StandaloneServiceTrait;

class ExecutableDocument implements ExecutableDocumentInterface
{
    use StandaloneServiceTrait;

    private ?GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider = null;

    final public function setGraphQLErrorMessageProvider(GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider): void
    {
        $this->graphQLErrorMessageProvider = $graphQLErrorMessageProvider;
    }
    final protected function getGraphQLErrorMessageProvider(): GraphQLErrorMessageProviderInterface
    {
        return $this->graphQLErrorMessageProvider ??= GraphQLErrorMessageProviderFacade::getInstance();
    }

    private ?array $requestedOperations = null;

    public function __construct(
        protected Document $document,
        protected Context $context,
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
    public function validateAndInitialize(): self
    {
        $this->requestedOperations = null;

        $this->document->validate();
        $this->assertAllVariablesHaveValue();

        // Obtain the operations that must be executed
        $this->requestedOperations = $this->assertAndGetRequestedOperations();

        // Inject the variable values into the objects
        foreach ($this->requestedOperations as $operation) {
            $this->propagateContext($operation, $this->context);
        }

        return $this;
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
            return $this->getNonRequestedOperation();
        }

        $requestedOperations = $this->extractRequestedOperations();
        if ($requestedOperations === []) {
            throw new InvalidRequestException(
                $this->getGraphQLErrorMessageProvider()->getNoOperationMatchesNameErrorMessage($this->context->getOperationName()),
                $this->getNonSpecificLocation()
            );
        }

        // There can be many operations
        return $requestedOperations;
    }

    /**
     * @return OperationInterface[]
     * @throws InvalidRequestException
     */
    protected function getNonRequestedOperation(): array
    {
        // It can't be 0, or validation already fails in Document
        if (count($this->document->getOperations()) > 1) {
            throw new InvalidRequestException(
                $this->getGraphQLErrorMessageProvider()->getNoOperationNameProvidedErrorMessage(),
                $this->getNonSpecificLocation()
            );
        }
        // There is exactly 1 operation
        return $this->document->getOperations();
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

    /**
     * Validate that all referenced variable are provided a value,
     * or they have a default value. Otherwise, throw an exception.
     *
     * @throws InvalidRequestException
     */
    protected function assertAllVariablesHaveValue(): void
    {
        foreach ($this->document->getOperations() as $operation) {
            foreach ($this->document->getVariableReferencesInOperation($operation) as $variableReference) {
                $variable = $variableReference->getVariable();
                if (
                    array_key_exists($variable->getName(), $this->context->getVariableValues())
                    || $variable->hasDefaultValue()
                ) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getGraphQLErrorMessageProvider()->getVariableNotSubmittedErrorMessage($variableReference->getName()),
                    $variableReference->getLocation()
                );
            }
        }
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
                $this->getGraphQLErrorMessageProvider()->getExecuteValidationErrorMessage(__FUNCTION__),
                $this->getNonSpecificLocation()
            );
        }
        return $this->requestedOperations;
    }
}
