<?php

declare(strict_types=1);

namespace PoP\ComponentModel\FieldResolvers\ObjectType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FieldResolvers\InterfaceType\IdentifiableObjectInterfaceTypeFieldResolver;
use PoP\ComponentModel\FieldResolvers\InterfaceType\InterfaceTypeFieldResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\AbstractObjectTypeResolver;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;

class IdentifiableObjectObjectTypeFieldResolver extends AbstractObjectTypeFieldResolver
{
    private ?IdentifiableObjectInterfaceTypeFieldResolver $identifiableObjectInterfaceTypeFieldResolver = null;

    final protected function getIdentifiableObjectInterfaceTypeFieldResolver(): IdentifiableObjectInterfaceTypeFieldResolver
    {
        if ($this->identifiableObjectInterfaceTypeFieldResolver === null) {
            /** @var IdentifiableObjectInterfaceTypeFieldResolver */
            $identifiableObjectInterfaceTypeFieldResolver = $this->instanceManager->getInstance(IdentifiableObjectInterfaceTypeFieldResolver::class);
            $this->identifiableObjectInterfaceTypeFieldResolver = $identifiableObjectInterfaceTypeFieldResolver;
        }
        return $this->identifiableObjectInterfaceTypeFieldResolver;
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
            'globalID',
        ];
    }

    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        switch ($fieldDataAccessor->getFieldName()) {
            case 'id':
                return $objectTypeResolver->getID($object);
            case 'globalID':
                return base64_encode(sprintf(
                    '%s:%s',
                    $objectTypeResolver->getNamespacedTypeName(),
                    $objectTypeResolver->getID($object)
                ));
        }
        return parent::resolveValue($objectTypeResolver, $object, $fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
    }

    /**
     * Since the return type is known for all the fields in this
     * FieldResolver, there's no need to validate them
     */
    public function validateResolvedFieldType(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
    ): bool {
        return false;
    }
}
