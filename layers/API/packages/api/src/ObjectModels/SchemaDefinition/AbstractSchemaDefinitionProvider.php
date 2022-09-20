<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

abstract class AbstractSchemaDefinitionProvider implements SchemaDefinitionProviderInterface
{
    /**
     * @var array<string,TypeResolverInterface|FieldDirectiveResolverInterface> Key: class, Value: Accessed Type and Directive Resolver
     */
    protected array $accessedTypeAndFieldDirectiveResolvers = [];

    final public function getAccessedTypeAndFieldDirectiveResolvers(): array
    {
        return array_values($this->accessedTypeAndFieldDirectiveResolvers);
    }
}
