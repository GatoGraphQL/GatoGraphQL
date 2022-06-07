<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec;

use PoP\ComponentModel\Component\Component;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\Spec\Parser\Location;

class RelationalComponentField extends AbstractComponentField
{
    /**
     * @param Component[] $nestedComponents
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        protected array $nestedComponents,
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
     * Retrieve a new instance with all the properties from the RelationalField
     *
     * @param Component[] $nestedComponents
     */
    public static function fromRelationalField(
        RelationalField $relationalField,
        array $nestedComponents,
    ): self {
        return new self(
            $relationalField->getName(),
            $nestedComponents,
            $relationalField->getAlias(),
            $relationalField->getArguments(),
            $relationalField->getDirectives(),
            $relationalField->getLocation(),
        );
    }

    /**
     * @return Component[]
     */
    public function getNestedComponents(): array
    {
        return $this->nestedComponents;
    }
}
