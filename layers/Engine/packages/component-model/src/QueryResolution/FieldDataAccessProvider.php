<?php

declare(strict_types=1);

namespace PoP\ComponentModel\QueryResolution;

use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\Root\Exception\ShouldNotHappenException;
use PoP\Root\Services\StandaloneServiceTrait;
use SplObjectStorage;

class FieldDataAccessProvider implements FieldDataAccessProviderInterface
{
    use StandaloneServiceTrait;

    /**
     * @param SplObjectStorage<FieldInterface,SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>>> $fieldData
     */
    public function __construct(
        protected SplObjectStorage $fieldObjectTypeResolverObjectFieldData,
    ) {
    }

    /**
     * @return array<string,mixed>
     * @throws ShouldNotHappenException If accessing a non-set Field/ObjectTypeResolver/object
     */
    public function getFieldData(
        FieldInterface $field,
        ?ObjectTypeResolverInterface $objectTypeResolver = null,
        ?object $object = null,
    ): array {
        if (!$this->fieldObjectTypeResolverObjectFieldData->contains($field)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('Field \'%s\' is not contained in the FieldDataAccessProvider'),
                    $field->getName()
                )
            );
        }
        /** @var SplObjectStorage<ObjectTypeResolverInterface,SplObjectStorage<object,array<string,mixed>>> */
        $objectTypeResolverObjectFieldData = $this->fieldObjectTypeResolverObjectFieldData[$field];
        if ($this->objectTypeResolverObjectFieldData->count() === 0) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('No ObjectTypeResolvers were set under Field \'%s\''),
                    $field->getName()
                )
            );
        }
        /**
         * If not passing the ObjectTypeResolver, then it's the only one that's been set
         * (only the UnionTypeResolver will add data for more than one ObjectTypeResolver)
         */
        if ($objectTypeResolver === null) {
            $objectTypeResolvers = \iterator_to_array($objectTypeResolverObjectFieldData);
            $objectTypeResolverCount = count($objectTypeResolvers);
            if ($objectTypeResolverCount > 1) {
                throw new ShouldNotHappenException(
                    sprintf(
                        $this->__('When not specifying the ObjectTypeResolver, the FieldDataAccessProvider can contain only 1, but %s were set'),
                        $objectTypeResolverCount
                    )
                );
            }
            /** @var ObjectTypeResolverInterface */
            $objectTypeResolver = $objectTypeResolvers[0];
        }
        /** @var SplObjectStorage<object,array<string,mixed>> */
        $objectFieldData = $objectTypeResolverObjectFieldData[$objectTypeResolver];
        /**
         * If not passing the object, then the data must be under the '*' object,
         * which contains data for "all objects"
         */
        if ($object === null) {
            $object = FieldDataAccessWildcardObjectFactory::getWildcardObject();
            if (!$objectFieldData->contains($object)) {
                throw new ShouldNotHappenException(
                    sprintf(
                        $this->__('In the FieldDataAccessProvider, no data for "all objects" has been set for field \'%s\' and ObjectTypeResolver \'%s\''),
                        $field->getName(),
                        $objectTypeResolver->getMaybeNamespacedTypeName()
                    )
                );
            }
        } elseif (!$objectFieldData->contains($object)) {
            throw new ShouldNotHappenException(
                sprintf(
                    $this->__('In the FieldDataAccessProvider, no data for object with ID \'%s\' has been set for field \'%s\' and ObjectTypeResolver \'%s\''),
                    $objectTypeResolver->getID($object),
                    $field->getName(),
                    $objectTypeResolver->getMaybeNamespacedTypeName()
                )
            );
        }
        /** @var object $object */
        /** @var array<string,mixed> */
        $fieldData = $objectFieldData[$object];
        return $fieldData;
    }
}
