<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ExtendedSpec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField as UpstreamLeafField;
use PoP\GraphQLParser\Spec\Parser\Location;

class LeafField extends UpstreamLeafField implements FieldInterface
{
    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        ?string $alias,
        array $arguments,
        array $directives,
        Location $location,
        protected bool $skipOutputIfNull,
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            $location,
        );
    }

    public function isSkipOutputIfNull(): bool
    {
        return $this->skipOutputIfNull;
    }
}
