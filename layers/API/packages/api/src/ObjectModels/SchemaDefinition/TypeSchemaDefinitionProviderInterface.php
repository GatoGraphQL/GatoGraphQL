<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface TypeSchemaDefinitionProviderInterface extends SchemaDefinitionProviderInterface
{
    public function getType(): string;
    /**
     * An array of typeName => typeResolver
     * 
     * @return array<string, TypeResolverInterface>
     */
    public function getAccessedTypeResolvers(): array;
    /**
     * An array of directiveName => directiveResolver
     * 
     * @return array<string, DirectiveResolverInterface>
     */
    public function getAccessedDirectiveResolvers(): array;
}
