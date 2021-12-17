<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser;

use PoP\BasicService\BasicServiceTrait;
use PoPBackbone\GraphQLParser\Parser\Parser as UpstreamParser;

class Parser extends UpstreamParser
{
    use BasicServiceTrait;
    
    protected function getIncorrectRequestSyntaxErrorMessage(): string
    {
        return $this->getTranslationAPI()->__('Incorrect request syntax', 'graphql-parser');
    }
}
