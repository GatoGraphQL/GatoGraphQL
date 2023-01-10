<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Hooks;

use GraphQLByPoP\GraphQLServer\TypeResolvers\ObjectType\MutationRootObjectTypeResolver;
use PoP\ComponentModel\Hooks\AbstractRemoveIdentifiableObjectFieldsFromObjectTypeHookSet;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * This service is disabled because it's not really needed:
 * The fields are never added to MutationRoot
 * in first place, so nothing to remove.
 *
 * @see layers/GraphQLByPoP/packages/graphql-server/src/Helpers/TypeResolverHelper.php
 * @see function `getObjectTypeResolverMandatoryFields`
 */
class RemoveIdentifiableObjectFieldsFromMutationRootObjectTypeHookSet extends AbstractRemoveIdentifiableObjectFieldsFromObjectTypeHookSet
{
    /**
     * @phpstan-return class-string<ObjectTypeResolverInterface|InterfaceTypeResolverInterface>
     */
    protected function getObjectTypeOrInterfaceTypeResolverClass(): string
    {
        return MutationRootObjectTypeResolver::class;
    }

    public function isServiceEnabled(): bool
    {
        return false;
    }
}
