<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\IdentifiableObjectInterfaceTypeFieldResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

/**
 * To be used together with:
 *
 * - AbstractRemoveIdentifiableObjectFieldsFromObjectTypeHookSet
 * - AbstractTransientObject
 */
trait RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait
{
    protected function getIdentifiableObjectInterfaceTypeFieldResolver(): IdentifiableObjectInterfaceTypeFieldResolver
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var IdentifiableObjectInterfaceTypeFieldResolver */
        return $instanceManager->getInstance(IdentifiableObjectInterfaceTypeFieldResolver::class);
    }

    /**
     * Remove the IdentifiableObject interface
     *
     * @param InterfaceTypeFieldResolverInterface[] $interfaceTypeFieldResolvers
     * @return InterfaceTypeFieldResolverInterface[]
     */
    protected function removeIdentifiableObjectInterfaceTypeFieldResolver(
        array $interfaceTypeFieldResolvers,
    ): array {
        $identifiableObjectInterfaceTypeFieldResolver = $this->getIdentifiableObjectInterfaceTypeFieldResolver();
        return array_values(array_filter(
            $interfaceTypeFieldResolvers,
            fn (InterfaceTypeFieldResolverInterface $interfaceTypeFieldResolver) => $interfaceTypeFieldResolver !== $identifiableObjectInterfaceTypeFieldResolver
        ));
    }
}
