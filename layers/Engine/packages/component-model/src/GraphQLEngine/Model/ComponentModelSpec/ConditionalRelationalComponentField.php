<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Location;

class ConditionalRelationalComponentField extends AbstractComponentField
{
    /**
     * The condition must be satisfied on the implicit field.
     * When the value of the field is `true`, load the conditional
     * domain switching fields.
     *
     * @param RelationalComponentField[] $relationalComponentFields
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        protected array $relationalComponentFields,
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
            $location,
        );
    }

    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(): array
    {
        return $this->relationalComponentFields;
    }
}
