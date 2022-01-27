<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Execution;

use PoP\Root\Services\StandaloneServiceTrait;
use PoP\GraphQLParser\Facades\Query\QueryAugmenterServiceFacade;
use PoP\GraphQLParser\Spec\Execution\ExecutableDocument as UpstreamExecutableDocument;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;

class ExecutableDocument extends UpstreamExecutableDocument
{
    use StandaloneServiceTrait;

    /**
     * Override to support the "multiple query execution" feature:
     * If passing operation name `__ALL`, then execute all operations (hack)
     *
     * @return OperationInterface[]
     */
    protected function extractRequestedOperations(): array
    {
        $queryAugmenterService = QueryAugmenterServiceFacade::getInstance();
        if ($queryAugmenterService->isExecutingAllOperations($this->context->getOperationName())) {
            return $this->document->getOperations();
        }

        return parent::extractRequestedOperations();
    }

    protected function getNoOperationMatchesNameErrorMessage(string $operationName): string
    {
        return \sprintf(
            $this->__('Operation with name \'%s\' does not exist', 'graphql-parser'),
            $operationName
        );
    }

    protected function getNoOperationNameProvidedErrorMessage(): string
    {
        return $this->__('The operation name must be provided', 'graphql-parser');
    }

    protected function getVariableHasntBeenDeclaredErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->__('Variable \'%s\' hasn\'t been declared', 'graphql-parser'),
            $variableName
        );
    }

    protected function getVariableHasntBeenSubmittedErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->__('Variable \'%s\' hasn\'t been submitted', 'graphql-parser'),
            $variableName
        );
    }

    protected function getExecuteValidationErrorMessage(string $methodName): string
    {
        return \sprintf(
            $this->__('Before executing `%s`, must call `%s`', 'graphql-parser'),
            $methodName,
            'validateAndInitialize'
        );
    }
}
