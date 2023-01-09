<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\FieldResolvers\ObjectType\RegisterMutationRootRootObjectTypeFieldResolver;
use PoP\ComponentModel\Hooks\AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class RemoveIDAndSelfFieldsFromMutationRootObjectTypeHookSet extends AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet
{
    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected function getObjectTypeOrInterfaceTypeResolverClass(): string
    {
        return RegisterMutationRootRootObjectTypeFieldResolver::class;
    }
}
