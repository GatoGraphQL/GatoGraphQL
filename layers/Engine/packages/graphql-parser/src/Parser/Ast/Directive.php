<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive as UpstreamDirective;
use PoPBackbone\GraphQLParser\Parser\Location;

class Directive extends UpstreamDirective
{
    use MaybeNonLocatableAstTrait;

    /**
     * @param Argument[] $arguments
     */
    public function __construct(
        string $name,
        array $arguments = [],
        ?Location $location = null,
    ) {
        parent::__construct(
            $name,
            $arguments,
            $this->getLocationOrPseudoLocation($location),
        );
    }
}
