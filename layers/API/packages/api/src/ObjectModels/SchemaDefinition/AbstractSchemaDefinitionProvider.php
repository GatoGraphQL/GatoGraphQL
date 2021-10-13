<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

abstract class AbstractSchemaDefinitionProvider implements SchemaDefinitionProviderInterface
{
    /**
     * @var array<string, TypeResolverInterface|DirectiveResolverInterface> Key: class, Value: Accessed Type and Directive Resolver
     */
    protected array $accessedTypeAndDirectiveResolvers = [];
    
    public function getAccessedTypeAndDirectiveResolvers(): array
    {
        return array_values($this->accessedTypeAndDirectiveResolvers);
    }
}
