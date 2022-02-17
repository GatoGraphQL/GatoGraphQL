<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\FeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorMessageProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class ExecutableDocument implements ExecutableDocumentInterface
{
    use StandaloneServiceTrait;

    private ?GraphQLSpecErrorMessageProvider $graphQLSpecErrorMessageProvider = null;
    private ?FeedbackItemProvider $feedbackItemProvider = null;

    final public function setGraphQLSpecErrorMessageProvider(GraphQLSpecErrorMessageProvider $graphQLSpecErrorMessageProvider): void
    {
        $this->graphQLSpecErrorMessageProvider = $graphQLSpecErrorMessageProvider;
    }
    final protected function getGraphQLSpecErrorMessageProvider(): GraphQLSpecErrorMessageProvider
    {
        return $this->graphQLSpecErrorMessageProvider ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLSpecErrorMessageProvider::class);
    }
    final public function setFeedbackItemProvider(FeedbackItemProvider $feedbackItemProvider): void
    {
        $this->feedbackItemProvider = $feedbackItemProvider;
    }
    final protected function getFeedbackItemProvider(): FeedbackItemProvider
    {
        return $this->feedbackItemProvider ??= InstanceManagerFacade::getInstance()->getInstance(FeedbackItemProvider::class);
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
                    $this->getGraphQLSpecErrorMessageProvider()->getMessage(GraphQLSpecErrorMessageProvider::E_6_1_B),
                    $this->getGraphQLSpecErrorMessageProvider()->getNamespacedCode(GraphQLSpecErrorMessageProvider::E_6_1_B),
                    LocationHelper::getNonSpecificLocation()
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
                $this->getGraphQLSpecErrorMessageProvider()->getMessage(GraphQLSpecErrorMessageProvider::E_6_1_A, $this->context->getOperationName()),
                $this->getGraphQLSpecErrorMessageProvider()->getNamespacedCode(GraphQLSpecErrorMessageProvider::E_6_1_A),
                LocationHelper::getNonSpecificLocation()
            );
        }
        return $requestedOperations;
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
                    $this->getGraphQLSpecErrorMessageProvider()->getNamespacedCode(GraphQLSpecErrorMessageProvider::E_5_8_5),
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
                $this->getFeedbackItemProvider()->getMessage(FeedbackItemProvider::E1, __FUNCTION__),
                $this->getFeedbackItemProvider()->getNamespacedCode(FeedbackItemProvider::E1),
                LocationHelper::getNonSpecificLocation()
            );
        }
        return $this->requestedOperations;
    }
}
