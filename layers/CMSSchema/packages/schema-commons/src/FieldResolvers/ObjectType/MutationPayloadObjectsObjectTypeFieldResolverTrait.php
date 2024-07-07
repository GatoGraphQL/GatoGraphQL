<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties;
use PoP\ComponentModel\Facades\Dictionaries\ObjectDictionaryFacade;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use stdClass;

trait MutationPayloadObjectsObjectTypeFieldResolverTrait
{
    protected function resolveMutationPayloadObjectsValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): mixed {
        $fieldName = $fieldDataAccessor->getFieldName();

        /** @var AbstractObjectMutationPayloadObjectTypeResolver */
        $objectMutationPayloadObjectTypeResolver = $this->getFieldTypeResolver($objectTypeResolver, $fieldName);
        /** @var DictionaryObjectTypeDataLoaderInterface */
        $dictionaryObjectTypeDataLoader = $objectMutationPayloadObjectTypeResolver->getRelationalTypeDataLoader();
        
        return $this->retrieveInputIDsFromObjectDictionary(
            $dictionaryObjectTypeDataLoader->getObjectClass(),
            $fieldDataAccessor,
        );
    }

    /**
     * @return AbstractObjectMutationPayloadObjectTypeResolver
     */
    abstract public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface;

    /**
     * Simply return the same IDs from the filter
     * (as long as they exist in the dictionary)
     *
     * @return array<string|int>
     */
    protected function retrieveInputIDsFromObjectDictionary(
        string $objectClass,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        /** @var stdClass */
        $mutationPayloadObjectsInput = $fieldDataAccessor->getValue(MutationInputProperties::INPUT);
        $ids = $mutationPayloadObjectsInput->ids;
        
        /**
         * Make sure the IDs really exist (i.e. they are IDs from transient
         * payload objects, just created)
         */
        $objectDictionary = ObjectDictionaryFacade::getInstance();
        $ids = array_filter(
            $ids,
            fn (string|int $id) => $objectDictionary->has($objectClass, $id)
        );
        
        return $ids;
    }
}
