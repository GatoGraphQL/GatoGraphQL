<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\NodeInterfaceTypeFieldResolver;

/**
 * To be used together with:
 *
 * - AbstractRemoveIDAndSelfFieldsFromObjectTypeHookSet
 * - AbstractTransientObject
 */
trait RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait
{
    abstract protected function getNodeInterfaceTypeFieldResolver(): NodeInterfaceTypeFieldResolver;

    /**
     * Remove the IdentifiableObject interface
     *
     * @param InterfaceTypeFieldResolverInterface[] $interfaceTypeFieldResolvers
     * @return InterfaceTypeFieldResolverInterface[]
     */
    protected function removeNodeInterfaceTypeFieldResolver(
        array $interfaceTypeFieldResolvers,
    ): array {
        $nodeInterfaceTypeFieldResolver = $this->getNodeInterfaceTypeFieldResolver();
        return array_values(array_filter(
            $interfaceTypeFieldResolvers,
            fn (InterfaceTypeFieldResolverInterface $interfaceTypeFieldResolver) => $interfaceTypeFieldResolver !== $nodeInterfaceTypeFieldResolver
        ));
    }
}
