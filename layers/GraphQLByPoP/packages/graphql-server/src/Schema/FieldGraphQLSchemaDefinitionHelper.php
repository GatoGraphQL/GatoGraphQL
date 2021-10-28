<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
use GraphQLByPoP\GraphQLServer\ObjectModels\WrappingTypeInterface;
use GraphQLByPoP\GraphQLServer\Registries\SchemaDefinitionReferenceRegistryInterface;
use Symfony\Contracts\Service\Attribute\Required;

class FieldGraphQLSchemaDefinitionHelper implements FieldGraphQLSchemaDefinitionHelperInterface
{
    protected SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry;

    #[Required]
    final public function autowireFieldGraphQLSchemaDefinitionHelper(
        SchemaDefinitionReferenceRegistryInterface $schemaDefinitionReferenceRegistry,
    ): void {
        $this->schemaDefinitionReferenceRegistry = $schemaDefinitionReferenceRegistry;
    }

    /**
     * @return array<Field|WrappingTypeInterface>
     */
    public function createFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array
    {
        $fieldSchemaDefinitionPointer = &SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $fields = [];
        foreach ($fieldSchemaDefinitionPointer as $fieldName => $fieldSchemaDefinition) {
            $fieldOrWrappingType = new Field(
                $fullSchemaDefinition,
                array_merge(
                    $fieldSchemaDefinitionPath,
                    [
                        $fieldName
                    ]
                )
            );
            $fields[] = $fieldOrWrappingType;
        }
        return $fields;
    }

    /**
     * @return array<Field|WrappingTypeInterface>
     */
    public function getFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array
    {
        $fieldSchemaDefinitionPointer = &SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $schemaDefinitionReferenceObjectIDs = [];
        foreach (array_keys($fieldSchemaDefinitionPointer) as $fieldName) {
            $schemaDefinitionReferenceObjectIDs[] = SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID(array_merge(
                $fieldSchemaDefinitionPath,
                [
                    $fieldName
                ]
            ));
        }
        /** @var array<Field|WrappingTypeInterface> */
        return $this->schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObjects($schemaDefinitionReferenceObjectIDs);
    }
}
