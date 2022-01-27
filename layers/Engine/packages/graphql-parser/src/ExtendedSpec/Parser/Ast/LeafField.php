<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Ast\LeafField as UpstreamLeafField;
use PoPBackbone\GraphQLParser\Parser\Location;

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
