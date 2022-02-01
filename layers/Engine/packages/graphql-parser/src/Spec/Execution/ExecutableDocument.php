<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

use PoP\GraphQLParser\Error\GraphQLErrorMessageProviderInterface;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Facades\Error\GraphQLErrorMessageProviderFacade;
use PoP\GraphQLParser\FeedbackMessage\GraphQLSpecErrorMessageProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class ExecutableDocument implements ExecutableDocumentInterface
{
    use StandaloneServiceTrait;

    private ?GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider = null;
    private ?GraphQLSpecErrorMessageProvider $graphQLSpecErrorMessageProvider = null;

    final public function setGraphQLErrorMessageProvider(GraphQLErrorMessageProviderInterface $graphQLErrorMessageProvider): void
    {
        $this->graphQLErrorMessageProvider = $graphQLErrorMessageProvider;
    }
    final protected function getGraphQLErrorMessageProvider(): GraphQLErrorMessageProviderInterface
    {
        return $this->graphQLErrorMessageProvider ??= GraphQLErrorMessageProviderFacade::getInstance();
    }
    final public function setGraphQLSpecErrorMessageProvider(GraphQLSpecErrorMessageProvider $graphQLSpecErrorMessageProvider): void
    {
        $this->graphQLSpecErrorMessageProvider = $graphQLSpecErrorMessageProvider;
    }
    final protected function getGraphQLSpecErrorMessageProvider(): GraphQLSpecErrorMessageProvider
    {
        return $this->graphQLSpecErrorMessageProvider ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLSpecErrorMessageProvider::class);
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
        $this->assertAllMandatoryVariablesHaveValue();

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

        $requestedOperations = array_values(array_filter(
            $this->document->getOperations(),
            fn (OperationInterface $operation) => $operation->getName() === $this->context->getOperationName()
        ));
        if ($requestedOperations === []) {
            throw new InvalidRequestException(
                $this->getGraphQLErrorMessageProvider()->getNoOperationMatchesNameErrorMessage($this->context->getOperationName()),
                $this->getNonSpecificLocation()
            );
        }
        return $requestedOperations;
    }

    protected function getNonSpecificLocation(): Location
    {
        return new Location(1, 1);
    }

    /**
     * Validate that all referenced mandatory variable are provided a value,
     * or they have a default value. Otherwise, throw an exception.
     *
     * @throws InvalidRequestException
     */
    protected function assertAllMandatoryVariablesHaveValue(): void
    {
        foreach ($this->document->getOperations() as $operation) {
            foreach ($this->document->getVariableReferencesInOperation($operation) as $variableReference) {
                $variable = $variableReference->getVariable();
                if (
                    !$variable->isRequired()
                    || array_key_exists($variable->getName(), $this->context->getVariableValues())
                    || $variable->hasDefaultValue()
                ) {
                    continue;
                }
                throw new InvalidRequestException(
                    $this->getGraphQLSpecErrorMessageProvider()->getMessage(GraphQLSpecErrorMessageProvider::E_5_8_5, $variableReference->getName()),
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
