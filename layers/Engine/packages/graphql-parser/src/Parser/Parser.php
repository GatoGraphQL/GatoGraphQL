<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser;

use PoP\BasicService\BasicServiceTrait;
use PoP\GraphQLParser\Parser\Ast\ArgumentValue\Variable;
use PoPBackbone\GraphQLParser\Parser\Location;
use PoPBackbone\GraphQLParser\Parser\Parser as UpstreamParser;
use PoPBackbone\GraphQLParser\Parser\Token;

class Parser extends UpstreamParser
{
    use BasicServiceTrait;
    
    protected function getIncorrectRequestSyntaxErrorMessage(): string
    {
        return $this->getTranslationAPI()->__('Incorrect request syntax', 'graphql-parser');
    }

    protected function getCantParseArgumentErrorMessage(): string
    {
        return $this->getTranslationAPI()->__('Can\'t parse argument', 'graphql-parser');
    }

    protected function createVariable(
        Token $nameToken,
        string $type,
        bool $required,
        bool $isArray,
        bool $arrayElementNullable,
        Location $location,
    ): Variable {
        return new Variable(
            $nameToken->getData(),
            $type,
            $required,
            $isArray,
            $arrayElementNullable,
            $location,
        );
    }
}
