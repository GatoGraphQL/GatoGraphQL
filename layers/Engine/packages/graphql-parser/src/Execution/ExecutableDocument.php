<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Execution;

use PoP\BasicService\BasicServiceTrait;
use PoP\GraphQLParser\Facades\Query\QueryAugmenterServiceFacade;
use PoPBackbone\GraphQLParser\Execution\ExecutableDocument as UpstreamExecutableDocument;

class ExecutableDocument extends UpstreamExecutableDocument
{
    use BasicServiceTrait;
    
    /**
     * Override to support the "multiple query execution" feature:
     * If passing operation name `__ALL`, then execute all operations (hack)
     *
     * @return OperationInterface[]
     */
    protected function getSelectedOperationsToExecute(): array
    {
        $queryAugmenterService = QueryAugmenterServiceFacade::getInstance();
        if ($queryAugmenterService->isExecutingAllOperations($this->operationName)) {
            return $this->document->getOperations();
        }
        
        return parent::getSelectedOperationsToExecute();
    }

    protected function getFragmentNotUsedErrorMessage(string $fragmentName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Fragment \'%s\' not used', 'graphql-parser'),
            $fragmentName
        );
    }

    protected function getFragmentNotDefinedInQueryErrorMessage(string $fragmentName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Fragment \'%s\' not defined in query', 'graphql-parser'),
            $fragmentName
        );
    }

    protected function getVariableDoesNotExistErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Variable \'%s\' does not exist', 'graphql-parser'),
            $variableName
        );
    }

    protected function getVariableNotUsedErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Variable \'%s\' not used', 'graphql-parser'),
            $variableName
        );
    }
}
