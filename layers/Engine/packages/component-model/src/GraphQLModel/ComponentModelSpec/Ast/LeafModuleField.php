<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast;

use PoP\ComponentModel\GraphQLModel\ExtendedSpec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class LeafModuleField extends LeafField
{
    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        ?string $alias = null,
        array $arguments = [],
        array $directives = [],
        bool $skipOutputIfNull = false,
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            LocationHelper::getNonSpecificLocation(),
            $skipOutputIfNull,
        );
    }
}
