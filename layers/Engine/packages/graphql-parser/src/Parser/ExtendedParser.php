<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser;

use PoP\GraphQLParser\Parser\Ast\MetaDirective;
use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Location;

class ExtendedParser extends Parser
{
    /**
     * @todo Implement for MetaDirective
     * @return Directive[]
     */
    protected function parseDirectiveList(): array
    {
        $directives = parent::parseDirectiveList();
        return $directives;
    }

    /**
     * @param Argument[] $arguments
     * @param Directive[] $nestedDirectives
     */
    protected function createMetaDirective(
        $name,
        array $arguments,
        array $nestedDirectives,
        Location $location,
    ): MetaDirective {
        return new MetaDirective($name, $arguments, $nestedDirectives, $location);
    }
}
