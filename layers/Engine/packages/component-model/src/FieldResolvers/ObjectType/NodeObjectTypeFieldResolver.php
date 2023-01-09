<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\FieldResolvers\InterfaceType\IdentifiableObjectInterfaceTypeFieldResolver;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;

class NodeObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
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

    /**
     * @return array<class-string<ObjectTypeResolverInterface>>
     */
    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            AbstractObjectTypeResolver::class,
        ];
    }

    /**
     * @return array<InterfaceTypeFieldResolverInterface>
     */
    public function getImplementedInterfaceTypeFieldResolvers(): array
    {
        return [
            $this->getIdentifiableObjectInterfaceTypeFieldResolver(),
        ];
    }

    /**
     * @return string[]
     */
    public function getFieldNamesToResolve(): array
    {
        return [
            'id',
        ];
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        return match ($fieldDataAccessor->getFieldName()) {
            'id' => $objectTypeResolver->getID($object),
            default => parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore),
        };
    }
}
