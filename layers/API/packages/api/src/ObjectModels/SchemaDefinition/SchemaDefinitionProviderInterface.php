<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

interface SchemaDefinitionProviderInterface
{
    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition(): array;
    /**
     * @return array<TypeResolverInterface|FieldDirectiveResolverInterface>
     */
    public function getAccessedTypeAndFieldDirectiveResolvers(): array;
}
