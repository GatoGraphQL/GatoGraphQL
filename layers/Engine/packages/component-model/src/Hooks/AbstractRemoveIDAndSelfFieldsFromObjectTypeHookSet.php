<?php

declare(strict_types=1);

namespace PoP\ComponentModel\Hooks;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\ObjectType\ObjectTypeFieldResolverInterface;
use PoP\ComponentModel\TypeResolvers\InterfaceType\InterfaceTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

/**
 * To be used together with:
 *
 * - RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait
 * - AbstractTransientObject
 */
abstract class AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet extends AbstractRemoveFieldsFromObjectTypeHookSet
{
    protected function matchesCondition(
        ObjectTypeResolverInterface | InterfaceTypeResolverInterface $objectTypeOrInterfaceTypeResolver,
        ObjectTypeFieldResolverInterface | InterfaceTypeFieldResolverInterface $objectTypeOrInterfaceTypeFieldResolver,
        string $fieldName
    ): bool {
        return $fieldName === 'id'
            || $fieldName === 'globalID'
            || ($fieldName === 'self' && $this->removeSelfField());
    }

    protected function removeSelfField(): bool
    {
        return true;
    }
}
