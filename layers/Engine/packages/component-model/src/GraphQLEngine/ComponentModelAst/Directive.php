<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\ComponentModelAst;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive as UpstreamDirective;

class Directive extends UpstreamDirective
{
    use MaybeNonLocatableAstTrait;

    /**
     * @param Argument[] $arguments
     */
    public function __construct(
        string $name,
        array $arguments = [],
    ) {
        parent::__construct(
            $name,
            $arguments,
            $this->createPseudoLocation(),
        );
    }
}
