<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class ConditionalLeafComponentField extends LeafField implements ComponentFieldInterface
{
    use ComponentFieldTrait;

    /**
     * The condition must be satisfied on the implicit field.
     * When the value of the field is `true`, load the conditional
     * extra modules under the current dataloading position.
     *
     * @param Component[] $conditionalNestedComponents
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        protected array $conditionalNestedComponents,
        ?string $alias = null,
        array $arguments = [],
        array $directives = [],
        ?Location $location = null,
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            $location ?? LocationHelper::getNonSpecificLocation(),
        );
    }

    /**
     * @return Component[]
     */
    public function getConditionalNestedComponents(): array
    {
        return $this->conditionalNestedComponents;
    }
}
