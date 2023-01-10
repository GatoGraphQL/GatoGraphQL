<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\AbstractSchemaElementExtensionsObjectTypeResolver;
use PoP\ComponentModel\Hooks\AbstractRemoveIdentifiableObjectFieldsFromObjectTypeHookSet;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RemoveIdentifiableObjectFieldsFromSchemaElementExtensionsObjectTypeHookSet extends AbstractRemoveIdentifiableObjectFieldsFromObjectTypeHookSet
{
    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected function getObjectTypeOrInterfaceTypeResolverClass(): string
    {
        return AbstractSchemaElementExtensionsObjectTypeResolver::class;
    }
}
