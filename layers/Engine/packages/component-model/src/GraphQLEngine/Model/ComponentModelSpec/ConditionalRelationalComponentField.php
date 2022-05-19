<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class ConditionalRelationalComponentField extends LeafField implements ComponentFieldInterface
{
    use ComponentFieldTrait;

    /**
     * The condition must be satisfied on the implicit field.
     * When the value of the field is `true`, load the conditional
     * domain switching fields.
     *
     * @param RelationalComponentField[] $conditionalRelationalComponentFields
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        protected array $conditionalRelationalComponentFields,
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
     * @return RelationalComponentField[]
     */
    public function getConditionalRelationalComponentFields(): array
    {
        return $this->conditionalRelationalComponentFields;
    }
}
