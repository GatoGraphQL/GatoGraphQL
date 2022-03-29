<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ExtendedSpec\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField as UpstreamRelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;

class RelationalField extends UpstreamRelationalField implements FieldInterface
{
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
        Location $location,
        protected bool $skipOutputIfNull = false,
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $fieldsOrFragmentBonds,
            $directives,
            $location,
        );
    }

    public function isSkipOutputIfNull(): bool
    {
        return $this->skipOutputIfNull;
    }
}
