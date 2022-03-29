<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField as QueryRelationalField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

class RelationalModuleField extends LeafField implements ModuleFieldInterface
{
    /**
     * If passing QueryRelationalField, then retrieve all the properties from there.
     * Otherwise, use the ones passed to the constructor.
     *
     * @param array<array> $nestedModules
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        protected array $nestedModules,
        ?QueryRelationalField $relationalField = null,
        ?string $alias = null,
        array $arguments = [],
        array $directives = [],
    ) {
        if ($relationalField !== null) {
            parent::__construct(
                $relationalField->getName(),
                $relationalField->getAlias(),
                $relationalField->getArguments(),
                $relationalField->getDirectives(),
                $relationalField->getLocation(),
            );
            return;
        }

        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            LocationHelper::getNonSpecificLocation(),
        );
    }

    public function getNestedModules(): array
    {
        return $this->nestedModules;
    }
}
