<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Ast\FragmentBondInterface;
use PoPBackbone\GraphQLParser\Parser\Ast\RelationalField as UpstreamRelationalField;
use PoPBackbone\GraphQLParser\Parser\Location;

class RelationalField extends UpstreamRelationalField implements FieldInterface
{
    use MaybeNonLocatableAstTrait;

    /**
     * @param Argument[] $arguments
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        ?string $alias,
        array $arguments,
        array $fieldsOrFragmentBonds,
        array $directives,
        ?Location $location = null,
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $fieldsOrFragmentBonds,
            $directives,
            $this->getLocationOrPseudoLocation($location),
        );
    }

    public function isSkipOutputIfNull(): bool
    {
        return false;
    }
}
