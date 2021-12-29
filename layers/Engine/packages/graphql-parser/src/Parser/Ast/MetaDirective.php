<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Ast\Argument;
use PoPBackbone\GraphQLParser\Parser\Ast\WithDirectivesInterface;
use PoPBackbone\GraphQLParser\Parser\Ast\WithDirectivesTrait;
use PoPBackbone\GraphQLParser\Parser\Location;

class MetaDirective extends Directive implements WithDirectivesInterface
{
    use WithDirectivesTrait;

    /**
     * @param Directive[] $nestedDirectives
     * @param Argument[] $arguments
     */
    public function __construct(
        string $name,
        array $arguments = [],
        array $nestedDirectives = [],
        ?Location $location = null,
    ) {
        parent::__construct(
            $name,
            $arguments,
            $location,
        );
        $this->setDirectives($nestedDirectives);
    }
}
