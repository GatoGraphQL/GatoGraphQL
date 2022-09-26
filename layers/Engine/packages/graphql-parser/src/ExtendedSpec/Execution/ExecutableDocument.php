<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Exception\InvalidRequestException;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Query\QueryAugmenterServiceInterface;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocument as UpstreamExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\App;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class ExecutableDocument extends UpstreamExecutableDocument implements ExecutableDocumentInterface
{
    /**
     * For Multiple Query Execution: the requested
     * operation can load and execute previous Operations.
     * This is the list of all the operations to execute.
     *
     * @var OperationInterface[]|null
     */
    protected ?array $multipleOperationsToExecute = null;

    private ?QueryAugmenterServiceInterface $queryAugmenterService = null;

    final public function setQueryAugmenterService(QueryAugmenterServiceInterface $queryAugmenterService): void
    {
        $this->queryAugmenterService = $queryAugmenterService;
    }
    final protected function getQueryAugmenterService(): QueryAugmenterServiceInterface
    {
        /** @var QueryAugmenterServiceInterface */
        return $this->queryAugmenterService ??= InstanceManagerFacade::getInstance()->getInstance(QueryAugmenterServiceInterface::class);
    }

    /**
     * @throws InvalidRequestException
     */
    public function validateAndInitialize(): void
    {
        parent::validateAndInitialize();

        // Obtain the multiple operations that must be executed
        $this->multipleOperationsToExecute = $this->assertAndGetMultipleRequestedOperations();

        // Inject the variable values into the objects
        foreach ($this->multipleOperationsToExecute as $operation) {
            /**
             * This has already been set in parent method
             */
            if ($operation === $this->requestedOperation) {
                continue;
            }
            $this->propagateContext($operation, $this->context);
        }
    }

    /**
     * Override to support the "multiple query execution" feature:
     * If passing operationName `__ALL`, or passing no operationName
     * but there is an operation `__ALL` in the document,
     * then execute all operations (hack).
     *
     * @return OperationInterface[]
     */
    protected function assertAndGetMultipleRequestedOperations(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableMultipleQueryExecution()
            || count($this->document->getOperations()) === 1
        ) {
            return [
                $this->requestedOperation,
            ];
        }

        return $this->getQueryAugmenterService()->getMultipleQueryExecutionOperations(
            $this->requestedOperation,
            $this->document->getOperations(),
        );
    }

    /**
     * @throws InvalidRequestException
     */
    public function reset(): void
    {
        if ($this->multipleOperationsToExecute === null) {
            return;
        }
        foreach ($this->multipleOperationsToExecute as $operation) {
            /**
             * This has already been set in parent method
             */
            if ($operation === $this->requestedOperation) {
                continue;
            }
            $this->propagateContext($operation, null);
        }
    }

    /**
     * @return OperationInterface[]
     * @throws InvalidRequestException
     * @throws ShouldNotHappenException When this function is not executed with the expected sequence
     */
    public function getMultipleOperationsToExecute(): array
    {
        if ($this->multipleOperationsToExecute === null) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Before executing `%s`, must call `validateAndInitialize`', 'graphql-server'),
                    __FUNCTION__,
                )
            );
        }
        return $this->multipleOperationsToExecute;
    }
}
