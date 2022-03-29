<?php

declare(strict_types=1);

namespace PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField as QueryLeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;

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
    ) {
        if ($leafField !== null) {
            parent::__construct(
                $leafField->getName(),
                $leafField->getAlias(),
                $leafField->getArguments(),
                $leafField->getDirectives(),
                $leafField->getLocation(),
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
}
