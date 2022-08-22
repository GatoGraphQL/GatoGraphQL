<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;

class ExecutableDocument implements ExecutableDocumentInterface
{
    use StandaloneServiceTrait;

    /**
     * @var OperationInterface[]|null
     */
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
    public function validateAndInitialize(): void
    {
        $this->requestedOperations = null;

        $this->validate();

        // Obtain the operations that must be executed
        $this->requestedOperations = $this->assertAndGetRequestedOperations();

        // Inject the variable values into the objects
        foreach ($this->requestedOperations as $operation) {
            $this->propagateContext($operation, $this->context);
        }
    }

    /**
     * @throws InvalidRequestException
     */
    protected function validate(): void
    {
        $this->document->validate();
        $this->assertAllMandatoryVariablesHaveValue();
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
        $operations = $this->document->getOperations();
        if ($this->context->getOperationName() === '') {
            // It can't be 0, or validation already fails in Document
            $operationCount = count($operations);
            if ($operationCount > 1) {
                $lastOperation = $operations[$operationCount - 1];
                throw new InvalidRequestException(
                    new FeedbackItemResolution(
                        GraphQLSpecErrorFeedbackItemProvider::class,
                        GraphQLSpecErrorFeedbackItemProvider::E_6_1_B,
                    ),
                    $lastOperation
                );
            }
            // There is exactly 1 operation
            return $operations;
        }

        $requestedOperations = array_values(array_filter(
            $this->document->getOperations(),
            fn (OperationInterface $operation) => $operation->getName() === $this->context->getOperationName()
        ));
        if ($requestedOperations === []) {
            $firstOperation = $operations[0];
            throw new InvalidRequestException(
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_6_1_A,
                    [
                         $this->context->getOperationName(),
                    ]
                ),
                $firstOperation
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
                /**
                 * Extended Spec: dynamic variable references will contain
                 * no variable. No need to return error here, since for
                 * static variables it will already fail in `assertAllVariablesExist`
                 */
                if ($variable === null) {
                    continue;
                }
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
                    $variableReference
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
     * @throws ShouldNotHappenException When this function is not executed with the expected sequence
     */
    public function getRequestedOperations(): array
    {
        if ($this->requestedOperations === null) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Before executing `%s`, must call `validateAndInitialize`', 'graphql-server'),
                    __FUNCTION__,
                )
            );
        }
        return $this->requestedOperations;
    }

    /**
     * The actual requested operation. Even though with Multiple Query Execution
     * the document can contain multiple operations, there is only one that
     * can be requested via ?operationName=...
     *
     * @throws InvalidRequestException
     */
    public function getRequestedOperation(): ?OperationInterface
    {
        $requestedOperations = $this->getRequestedOperations();
        if (count($requestedOperations) === 1) {
            return $requestedOperations[0];
        }

        /**
         * Exactly one operation must have the requested name, or otherwise
         * parsing the query would've thrown an error
         */
        return $this->getMatchingRequestedOperation(
            $this->getRequestedOperations(),
            $this->context->getOperationName(),
        );
    }

    /**
     * @param OperationInterface[] $operations
     */
    protected function getMatchingRequestedOperation(array $operations, string $operationName): ?OperationInterface
    {
        $matchingOperations = array_values(array_filter(
            $operations,
            fn (OperationInterface $operation) => $operation->getName() === $operationName
        ));
        if ($matchingOperations === []) {
            return null;
        }
        return $matchingOperations[0];
    }
}
