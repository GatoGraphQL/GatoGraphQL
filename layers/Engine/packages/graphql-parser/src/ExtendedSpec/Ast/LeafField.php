<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField as UpstreamLeafField;
use PoP\GraphQLParser\Spec\Parser\Location;

class LeafField extends UpstreamLeafField implements FieldInterface
{
    use MaybeNonLocatableAstTrait;

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        ?string $alias = null,
        array $arguments = [],
        array $directives = [],
        ?Location $location = null,
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            $this->getLocationOrPseudoLocation($location),
        );
    }

    public function isSkipOutputIfNull(): bool
    {
        return false;
    }
}
