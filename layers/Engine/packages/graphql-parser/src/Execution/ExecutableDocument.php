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
    protected function extractRequestedOperations(): array
    {
        $queryAugmenterService = QueryAugmenterServiceFacade::getInstance();
        if ($queryAugmenterService->isExecutingAllOperations($this->operationName)) {
            return $this->document->getOperations();
        }
        
        return parent::extractRequestedOperations();
    }

    protected function getNoOperationMatchesNameErrorMessage(string $operationName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Operation with name \'%s\' does not exist', 'graphql-parser'),
            $operationName
        );
    }

    protected function getNoOperationNameProvidedErrorMessage(): string
    {
        return $this->getTranslationAPI()->__('The operation name must be provided', 'graphql-parser');
    }

    protected function getVariableHasntBeenDeclaredErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Variable \'%s\' hasn\'t been declared', 'graphql-parser'),
            $variableName
        );
    }

    protected function getVariableHasntBeenSubmittedErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Variable \'%s\' hasn\'t been submitted', 'graphql-parser'),
            $variableName
        );
    }

    protected function getExecuteValidationErrorMessage(string $methodName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Before executing `%s`, must call `%s`', 'graphql-parser'),
            $methodName,
            'validateAndInitialize'
        );
    }
}
