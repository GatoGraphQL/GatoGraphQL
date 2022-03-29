<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast;

use PoP\ComponentModel\GraphQLModel\ExtendedSpec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField as QueryLeafField;

class LeafModuleField extends LeafField implements ModuleFieldInterface
{
    /**
     * If passing QueryLeafField, then retrieve all the properties from there.
     * Otherwise, use the ones passed to the constructor.
     *
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        ?QueryLeafField $leafField = null,
        ?string $alias = null,
        array $arguments = [],
        array $directives = [],
        bool $skipOutputIfNull = false,
    ) {
        if ($leafField !== null) {
            parent::__construct(
                $leafField->getName(),
                $leafField->getAlias(),
                $leafField->getArguments(),
                $leafField->getDirectives(),
                $leafField->getLocation(),
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
}
