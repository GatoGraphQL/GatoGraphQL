<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\ComponentModelAst;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField as UpstreamLeafField;

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
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            $this->createPseudoLocation(),
        );
    }

    public function isSkipOutputIfNull(): bool
    {
        return false;
    }
}
