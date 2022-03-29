<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class RelationalModuleField extends LeafField implements ModuleFieldInterface
{
    /**
     * @param array<array> $nestedModules
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        protected array $nestedModules,
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
     * Retrieve a new instance with all the properties from the RelationalField
     *
     * @param array<array> $nestedModules
     */
    public static function fromRelationalField(
        RelationalField $relationalField,
        array $nestedModules,
    ): self {
        return new self(
            $relationalField->getName(),
            $nestedModules,
            $relationalField->getAlias(),
            $relationalField->getArguments(),
            $relationalField->getDirectives(),
            $relationalField->getLocation(),
        );
    }

    public function getNestedModules(): array
    {
        return $this->nestedModules;
    }
}
