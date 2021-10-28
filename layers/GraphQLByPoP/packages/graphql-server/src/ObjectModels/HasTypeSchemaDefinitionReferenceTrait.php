<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use GraphQLByPoP\GraphQLServer\Facades\Registries\SchemaDefinitionReferenceRegistryFacade;
use GraphQLByPoP\GraphQLServer\Schema\GraphQLSchemaHelpers;
use GraphQLByPoP\GraphQLServer\Schema\SchemaDefinitionHelpers;
use PoP\API\Schema\SchemaDefinition;

trait HasTypeSchemaDefinitionReferenceTrait
{
    protected TypeInterface $type;
    
    public function getType(): TypeInterface
    {
        return $this->type;
    }

    protected function initType(): void
    {
        $typeID = SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID([
            SchemaDefinition::TYPES,
            $this->schemaDefinition[SchemaDefinition::TYPE_KIND],
            $this->schemaDefinition[SchemaDefinition::TYPE_NAME],
        ]);
        $schemaDefinitionReferenceRegistry = SchemaDefinitionReferenceRegistryFacade::getInstance();
        /** @var TypeInterface */
        $type = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($typeID);

        /**
         * Either retrieve the object from the registry if it exists,
         * or create it.
         */
        if ($this->schemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false) {
			if ($this->schemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false) {
                $typeID = GraphQLSchemaHelpers::getNonNullableOrMandatoryTypeName($typeID);
                $maybeRegisteredType = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($typeID);
                $type = $maybeRegisteredType !== null ? $maybeRegisteredType : new NonNullType($type);
			}
            $typeID = GraphQLSchemaHelpers::getListTypeName($typeID);
            $maybeRegisteredType = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($typeID);
            $type = $maybeRegisteredType !== null ? $maybeRegisteredType : new ListType($type);
		}
		if ($this->schemaDefinition[SchemaDefinition::IS_ARRAY] ?? false) {
			if ($this->schemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false) {
				$typeID = GraphQLSchemaHelpers::getNonNullableOrMandatoryTypeName($typeID);
                $maybeRegisteredType = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($typeID);
                $type = $maybeRegisteredType !== null ? $maybeRegisteredType : new NonNullType($type);
			}
			$typeID = GraphQLSchemaHelpers::getListTypeName($typeID);
            $maybeRegisteredType = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($typeID);
            $type = $maybeRegisteredType !== null ? $maybeRegisteredType : new ListType($type);
		}
		if ($this->schemaDefinition[SchemaDefinition::NON_NULLABLE] ?? false) {
			$typeID = GraphQLSchemaHelpers::getNonNullableOrMandatoryTypeName($typeID);
            $maybeRegisteredType = $schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObject($typeID);
            $type = $maybeRegisteredType !== null ? $maybeRegisteredType : new NonNullType($type);
		}
        $this->type = $type;
    }

    // /**
    //  * Append the GraphQL wrappers to the ID, to select any entity
    //  * of type NamedType or WrappingType
    //  */
    // public function getTypeID(): string
    // {
    //     $typeID = SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID([
    //         SchemaDefinition::TYPES,
    //         $this->schemaDefinition[SchemaDefinition::TYPE_KIND],
    //         $this->schemaDefinition[SchemaDefinition::TYPE_NAME],
    //     ]);
    //     return GraphQLSchemaHelpers::getTypeNameForGraphQLSchema(
    //         $typeID,
    //         $this->schemaDefinition[SchemaDefinition::NON_NULLABLE] ?? null,
    //         $this->schemaDefinition[SchemaDefinition::IS_ARRAY] ?? false,
    //         $this->schemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY] ?? false,
    //         $this->schemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false,
    //         $this->schemaDefinition[SchemaDefinition::IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] ?? false,
    //     );
    // }
}
