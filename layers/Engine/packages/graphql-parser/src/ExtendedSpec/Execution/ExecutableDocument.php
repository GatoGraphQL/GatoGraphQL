<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReference;
use PoP\GraphQLParser\Facades\Query\QueryAugmenterServiceFacade;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocument as UpstreamExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\App;

class ExecutableDocument extends UpstreamExecutableDocument
{
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
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableMultipleQueryExecution()) {
            return parent::assertAndGetRequestedOperations();
        }

        if (count($this->document->getOperations()) === 1) {
            return parent::assertAndGetRequestedOperations();
        }

        $queryAugmenterService = QueryAugmenterServiceFacade::getInstance();
        $multipleQueryExecutionOperations = $queryAugmenterService->getMultipleQueryExecutionOperations($this->context->getOperationName(), $this->document->getOperations());
        if ($multipleQueryExecutionOperations !== null) {
            return $multipleQueryExecutionOperations;
        }

        return parent::assertAndGetRequestedOperations();
    }

    protected function propagateContext(OperationInterface $operation, ?Context $context): void
    {
        parent::propagateContext($operation, $context);

        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        if (!$moduleConfiguration->enableDynamicVariables()) {
            return;
        }

        foreach ($this->document->getOperations() as $operation) {
            foreach ($this->document->getVariableReferencesInOperation($operation) as $variableReference) {
                if (!($variableReference instanceof DynamicVariableReference)) {
                    continue;
                }
                $variableReference->setContext($context);
            }
        }
    }
}
