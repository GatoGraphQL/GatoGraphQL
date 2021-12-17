<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\Directive as UpstreamDirective;
use PoPBackbone\GraphQLParser\Parser\Location;

class Directive extends UpstreamDirective
{
    /**
     * For the SiteBuilder, the generated GraphQL query will have no Location
     *
     * @param Argument[] $arguments
     */
    public function __construct(
        private string $name,
        array $arguments,
        ?Location $location = null,
    ) {
        $location ??= new Location(0, 0);
        parent::__construct($name, $arguments, $location);
    }
}
