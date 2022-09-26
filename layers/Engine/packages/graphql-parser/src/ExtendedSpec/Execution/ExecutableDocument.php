<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\ExtendedSpec\Constants\QuerySymbols;
use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\Query\QueryAugmenterServiceInterface;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocument as UpstreamExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class ExecutableDocument extends UpstreamExecutableDocument
{
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
     * Override to support the "multiple query execution" feature:
     * If passing operationName `__ALL`, or passing no operationName
     * but there is an operation `__ALL` in the document,
     * then execute all operations (hack).
     *
     * @return OperationInterface[]
     */
    protected function assertAndGetRequestedOperations(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableMultipleQueryExecution()) {
            return parent::assertAndGetRequestedOperations();
        }

        if (count($this->document->getOperations()) === 1) {
            return parent::assertAndGetRequestedOperations();
        }

        $multipleQueryExecutionOperations = $this->getQueryAugmenterService()->getMultipleQueryExecutionOperations(
            $this->context->getOperationName(),
            $this->document->getOperations(),
        );
        if ($multipleQueryExecutionOperations !== null) {
            return $multipleQueryExecutionOperations;
        }

        return parent::assertAndGetRequestedOperations();
    }

    /**
     * Override: If no operationName was provided, then it's assigned to __ALL
     */
    public function getRequestedOperation(): ?OperationInterface
    {
        $requestedOperations = $this->getRequestedOperations();
        if (count($requestedOperations) === 1) {
            return parent::getRequestedOperation();
        }

        $operationName = $this->context->getOperationName();
        if (!empty($operationName) && $operationName !== QuerySymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME) {
            return parent::getRequestedOperation();
        }

        /**
         * Multiple operations, and no operationName requested => use __ALL
         */
        return $this->getMatchingRequestedOperation(
            $this->document->getOperations(),
            QuerySymbols::GRAPHIQL_QUERY_BATCHING_OPERATION_NAME,
        );
    }
}
