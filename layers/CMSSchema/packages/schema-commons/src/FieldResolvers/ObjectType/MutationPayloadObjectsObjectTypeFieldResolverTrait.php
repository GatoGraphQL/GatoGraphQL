<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\FieldResolvers\ObjectType;

use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties;
use PoPCMSSchema\SchemaCommons\TypeResolvers\InputObjectType\MutationPayloadObjectsInputObjectTypeResolver;
use PoPSchema\SchemaCommons\TypeResolvers\ObjectType\AbstractObjectMutationPayloadObjectTypeResolver;
use PoP\ComponentModel\Facades\Dictionaries\ObjectDictionaryFacade;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\RelationalTypeDataLoaders\ObjectType\DictionaryObjectTypeDataLoaderInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use stdClass;

trait MutationPayloadObjectsObjectTypeFieldResolverTrait
{
    private ?MutationPayloadObjectsInputObjectTypeResolver $mutationPayloadObjectsInputObjectTypeResolver = null;

    protected function getMutationPayloadObjectsInputObjectTypeResolver(): MutationPayloadObjectsInputObjectTypeResolver
    {
        if ($this->mutationPayloadObjectsInputObjectTypeResolver === null) {
            /** @var MutationPayloadObjectsInputObjectTypeResolver */
            $mutationPayloadObjectsInputObjectTypeResolver = InstanceManagerFacade::getInstance()->getInstance(MutationPayloadObjectsInputObjectTypeResolver::class);
            $this->mutationPayloadObjectsInputObjectTypeResolver = $mutationPayloadObjectsInputObjectTypeResolver;
        }
        return $this->mutationPayloadObjectsInputObjectTypeResolver;
    }

    protected function getMutationPayloadObjectsFieldTypeModifiers(): int
    {
        return SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY | SchemaTypeModifiers::IS_NON_NULLABLE_ITEMS_IN_ARRAY;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getMutationPayloadObjectsFieldArgNameTypeResolvers(): array
    {
        return [
            MutationInputProperties::INPUT => $this->getMutationPayloadObjectsInputObjectTypeResolver(),
        ];
    }

    protected function getMutationPayloadObjectsFieldArgTypeModifiers(string $fieldArgName): ?int
    {
        return match ($fieldArgName) {
            MutationInputProperties::INPUT => SchemaTypeModifiers::MANDATORY,
            default => null,
        };
    }

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
