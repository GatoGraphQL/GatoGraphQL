<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast;

use PoP\ComponentModel\GraphQLModel\ExtendedSpec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField as QueryRelationalField;

class RelationalModuleField extends LeafField implements ModuleFieldInterface
{
    /**
     * If passing QueryRelationalField, then retrieve all the properties from there.
     * Otherwise, use the ones passed to the constructor.
     *
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
        bool $skipOutputIfNull = false,
    ) {
        if ($relationalField !== null) {
            parent::__construct(
                $relationalField->getName(),
                $relationalField->getAlias(),
                $relationalField->getArguments(),
                $relationalField->getDirectives(),
                $relationalField->getLocation(),
                false,
            );
            return;
        }

        parent::__construct(
            $name,
            $alias,
            $arguments,
            $directives,
            LocationHelper::getNonSpecificLocation(),
            $skipOutputIfNull,
        );
    }

    public function getNestedModules(): array
    {
        return $this->nestedModules;
    }
}
