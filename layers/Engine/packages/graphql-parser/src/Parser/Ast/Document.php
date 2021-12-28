<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoP\BasicService\BasicServiceTrait;
use PoPBackbone\GraphQLParser\Parser\Ast\Document as UpstreamDocument;

class Document extends UpstreamDocument
{
    use BasicServiceTrait;

    protected function getNoOperationsDefinedInQueryErrorMessage(): string
    {
        return $this->getTranslationAPI()->__('No operations defined in the query', 'graphql-parser');
    }

    protected function getDuplicateOperationNameErrorMessage(string $operationName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Operation name \'%s\' is duplicated, it must be unique', 'graphql-parser'),
            $operationName
        );
    }

    protected function getFragmentNotDefinedInQueryErrorMessage(string $fragmentName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Fragment \'%s\' not defined in query', 'graphql-parser'),
            $fragmentName
        );
    }

    protected function getFragmentNotUsedErrorMessage(string $fragmentName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Fragment \'%s\' not used', 'graphql-parser'),
            $fragmentName
        );
    }

    protected function getDuplicateVariableNameErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Variable name \'%s\' is duplicated, it must be unique', 'graphql-parser'),
            $variableName
        );
    }

    protected function getVariableDoesNotExistErrorMessage(string $variableName): string
    {
        return \sprintf(
            $this->getTranslationAPI()->__('Variable \'%s\' has not been defined in the operation', 'graphql-parser'),
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
