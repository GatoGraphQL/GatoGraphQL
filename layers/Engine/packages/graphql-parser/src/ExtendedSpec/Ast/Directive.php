<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive as UpstreamDirective;
use PoP\GraphQLParser\Spec\Parser\Location;

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
