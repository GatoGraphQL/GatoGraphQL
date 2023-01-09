<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\IdentifiableObjectInterfaceTypeFieldResolver;
use PoP\ComponentModel\ObjectModels\TransientObjectInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;

abstract class AbstractTransientObjectObjectTypeResolver extends AbstractObjectTypeResolver
{
    use RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;

    private ?IdentifiableObjectInterfaceTypeFieldResolver $identifiableObjectInterfaceTypeFieldResolver = null;

    final public function setIdentifiableObjectInterfaceTypeFieldResolver(IdentifiableObjectInterfaceTypeFieldResolver $identifiableObjectInterfaceTypeFieldResolver): void
    {
        $this->identifiableObjectInterfaceTypeFieldResolver = $identifiableObjectInterfaceTypeFieldResolver;
    }
    final protected function getIdentifiableObjectInterfaceTypeFieldResolver(): IdentifiableObjectInterfaceTypeFieldResolver
    {
        /** @var IdentifiableObjectInterfaceTypeFieldResolver */
        return $this->identifiableObjectInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(IdentifiableObjectInterfaceTypeFieldResolver::class);
    }

    final public function getID(object $object): string|int|null
    {
        /** @var TransientObjectInterface */
        $transientObject = $object;
        return $transientObject->getID();
    }

    /**
     * Remove the IdentifiableObject interface
     *
     * @param InterfaceTypeFieldResolverInterface[] $interfaceTypeFieldResolvers
     * @return InterfaceTypeFieldResolverInterface[]
     */
    final protected function consolidateAllImplementedInterfaceTypeFieldResolvers(
        array $interfaceTypeFieldResolvers,
    ): array {
        return $this->removeIdentifiableObjectInterfaceTypeFieldResolver(
            parent::consolidateAllImplementedInterfaceTypeFieldResolvers($interfaceTypeFieldResolvers),
        );
    }
}
