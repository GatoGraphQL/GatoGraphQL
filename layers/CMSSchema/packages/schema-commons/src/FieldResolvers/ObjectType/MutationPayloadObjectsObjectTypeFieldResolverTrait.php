<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoP\ComponentModel\Facades\Dictionaries\ObjectDictionaryFacade;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use stdClass;

trait MutationPayloadObjectsObjectTypeFieldResolverTrait
{
    /**
     * Simply return the same IDs from the filter
     *
     * @return array<string|int>
     */
    protected function resolveMutationPayloadObjectsValue(
        string $objectClass,
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        /** @var stdClass */
        $mutationPayloadObjectsInput = $fieldDataAccessor->getValue('input');
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
