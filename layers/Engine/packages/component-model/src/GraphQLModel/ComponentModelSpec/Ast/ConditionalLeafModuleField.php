<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class ConditionalLeafModuleField extends LeafField implements ModuleFieldInterface
{
    /**
     * The condition must be satisfied on the implicit field.
     * When the value of the field is `true`, load the conditional
     * extra modules under the current dataloading position.
     *
     * @param array<array> $conditionalNestedModules
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        protected array $conditionalNestedModules,
        ?string $alias = null,
        array $arguments = [],
        array $directives = [],
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            LocationHelper::getNonSpecificLocation(),
        );
    }

    public function getConditionalNestedModules(): array
    {
        return $this->conditionalNestedModules;
    }
}
