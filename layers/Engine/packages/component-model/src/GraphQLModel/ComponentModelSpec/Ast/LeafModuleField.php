<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class LeafModuleField extends LeafField implements ModuleFieldInterface
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
    ) {
        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            LocationHelper::getNonSpecificLocation(),
        );
    }

    /**
     * Retrieve a new instance with all the properties from the LeafField
     */
    public static function fromLeafField(
        LeafField $leafField,
    ): self {
        return new self(
            $leafField->getName(),
            $leafField->getAlias(),
            $leafField->getArguments(),
            $leafField->getDirectives(),
            $leafField->getLocation(),
        );
    }
}
