<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\NodeInterfaceTypeFieldResolver;
use PoP\ComponentModel\ObjectModels\TransientObjectInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;

abstract class AbstractTransientObjectObjectTypeResolver extends AbstractObjectTypeResolver
{
    use RemoveIdentifiableObjectInterfaceObjectTypeResolverTrait;

    private ?NodeInterfaceTypeFieldResolver $nodeInterfaceTypeFieldResolver = null;

    final public function setNodeInterfaceTypeFieldResolver(NodeInterfaceTypeFieldResolver $nodeInterfaceTypeFieldResolver): void
    {
        $this->nodeInterfaceTypeFieldResolver = $nodeInterfaceTypeFieldResolver;
    }
    final protected function getNodeInterfaceTypeFieldResolver(): NodeInterfaceTypeFieldResolver
    {
        /** @var NodeInterfaceTypeFieldResolver */
        return $this->nodeInterfaceTypeFieldResolver ??= $this->instanceManager->getInstance(NodeInterfaceTypeFieldResolver::class);
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
        return $this->removeNodeInterfaceTypeFieldResolver(
            parent::consolidateAllImplementedInterfaceTypeFieldResolvers($interfaceTypeFieldResolvers),
        );
    }
}
