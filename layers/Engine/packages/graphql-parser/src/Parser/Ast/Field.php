<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\Directive;
use PoPBackbone\GraphQLParser\Parser\Ast\Field as UpstreamField;
use PoPBackbone\GraphQLParser\Parser\Location;

class Field extends UpstreamField
{
    use MaybeNonLocatableAstTrait;

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        ?string $alias,
        array $arguments,
        array $directives,
        ?Location $location = null,
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            $this->getLocation($location),
        );
    }

    public function isSkipOutputIfNull(): bool
    {
        return false;
    }
}
