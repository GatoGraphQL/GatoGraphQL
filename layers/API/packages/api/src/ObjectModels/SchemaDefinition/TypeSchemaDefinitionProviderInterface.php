<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface TypeSchemaDefinitionProviderInterface extends SchemaDefinitionProviderInterface
{
    public function getType(): string;
    /**
     * @return array<TypeResolverInterface|DirectiveResolverInterface>
     */
    public function getAccessedTypeAndDirectiveResolvers(): array;
}
