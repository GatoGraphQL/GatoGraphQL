<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Execution;

use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\FeatureNotSupportedException;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\StandaloneServiceTrait;

class ExecutableDocument implements ExecutableDocumentInterface
{
    use StandaloneServiceTrait;

    protected bool $isValidatedAndInitialized = false;

    /**
     * The operation to execute:
     *
     * - If the Document has only 1 operation, then that one
     * - If it has more than 1 operation, the one indicated via ?operationName=...
     *
     * @var OperationInterface|null
     */
    private ?OperationInterface $requestedOperation = null;

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
     * @throws FeatureNotSupportedException
     */
    public function validateAndInitialize(): void
    {
        $this->isValidatedAndInitialized = true;

        $this->validate();

        // Obtain the operations that must be executed
        $this->requestedOperation = $this->assertAndGetRequestedOperation();

        // Inject the variable values into the objects
        $this->propagateContext($this->requestedOperation, $this->context);
    }

    /**
     * @throws InvalidRequestException
     * @throws FeatureNotSupportedException
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
        $this->isValidatedAndInitialized = false;

        if ($this->requestedOperation === null) {
            return;
        }
        $this->propagateContext($this->requestedOperation, null);
    }

    /**
     * The operation to execute:
     *
     * - If the Document has only 1 operation, then that one
     * - If it has more than 1 operation, the one indicated via ?operationName=...
     *
     * @return OperationInterface
     * @throws InvalidRequestException
     *
     * @see https://spec.graphql.org/draft/#sec-Executing-Requests
     */
    protected function assertAndGetRequestedOperation(): OperationInterface
    {
        $operations = $this->document->getOperations();
        if ($this->context->getOperationName() === '') {
            /**
             * The count can't be 0, or the validation
             * will already fail in the Document
             */
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
            return $operations[0];
        }

        /**
         * Find the operation with the requested name
         */
        foreach ($this->document->getOperations() as $operation) {
            if ($operation->getName() !== $this->context->getOperationName()) {
                continue;
            }
            return $operation;
        }

        /**
         * None found, it's an error!
         */
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
     * The requested operation via ?operationName=...
     *
     * @throws InvalidRequestException
     */
    public function getRequestedOperation(): ?OperationInterface
    {
        if (!$this->isValidatedAndInitialized) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Before executing `%s`, must call `validateAndInitialize`', 'graphql-server'),
                    __FUNCTION__,
                )
            );
        }
        return $this->requestedOperation;
    }
}
