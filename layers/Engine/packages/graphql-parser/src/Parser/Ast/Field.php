<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Ast\Field as UpstreamField;
use PoPBackbone\GraphQLParser\Parser\Location;

class Field extends UpstreamField
{
    /**
     * For the SiteBuilder, the generated GraphQL query will have no Location
     *
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        private string $name,
        private ?string $alias,
        array $arguments,
        array $directives,
        ?Location $location = null,
    ) {
        $location ??= new Location(0, 0);
        parent::__construct($name, $alias, $arguments, $directives, $location);
    }
}
