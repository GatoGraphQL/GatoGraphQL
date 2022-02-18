<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\FeedbackItemProvider;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\Root\Services\StandaloneServiceTrait;

class ExecutableDocument implements ExecutableDocumentInterface
{
    use StandaloneServiceTrait;

    private ?GraphQLSpecErrorFeedbackItemProvider $graphQLSpecErrorFeedbackItemProvider = null;
    private ?FeedbackItemProvider $feedbackItemProvider = null;

    final public function setGraphQLSpecErrorFeedbackItemProvider(GraphQLSpecErrorFeedbackItemProvider $graphQLSpecErrorFeedbackItemProvider): void
    {
        $this->graphQLSpecErrorFeedbackItemProvider = $graphQLSpecErrorFeedbackItemProvider;
    }
    final protected function getGraphQLSpecErrorFeedbackItemProvider(): GraphQLSpecErrorFeedbackItemProvider
    {
        return $this->graphQLSpecErrorFeedbackItemProvider ??= InstanceManagerFacade::getInstance()->getInstance(GraphQLSpecErrorFeedbackItemProvider::class);
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
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_6_1_B,
                    ),
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
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_6_1_A,
                    [
                         $this->context->getOperationName(),
                    ]
                ),
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
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_5_8_5,
                        [
                             $variableReference->getName(),
                        ]
                    ),
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
                new FeedbackItemResolution(
                    FeedbackItemProvider::class,
                    FeedbackItemProvider::E1,
                    [
                         __FUNCTION__,
                    ]
                ),
                LocationHelper::getNonSpecificLocation()
            );
        }
        return $this->requestedOperations;
    }
}
