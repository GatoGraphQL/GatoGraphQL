<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\ComponentModelAst;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField as UpstreamRelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;

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
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $fieldsOrFragmentBonds,
            $directives,
            $this->createPseudoLocation(),
        );
    }

    public function isSkipOutputIfNull(): bool
    {
        return false;
    }
}
