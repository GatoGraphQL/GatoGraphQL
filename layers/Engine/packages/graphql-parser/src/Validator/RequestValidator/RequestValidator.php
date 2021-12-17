<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Validator\RequestValidator;

use PoP\BasicService\BasicServiceTrait;
use PoPBackbone\GraphQLParser\Validator\RequestValidator\RequestValidator as UpstreamRequestValidator;

class RequestValidator extends UpstreamRequestValidator implements RequestValidatorInterface
{
    use BasicServiceTrait;
    
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
