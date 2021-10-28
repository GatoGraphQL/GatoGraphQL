<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\Schema;

use GraphQLByPoP\GraphQLServer\ObjectModels\Field;
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
     * @return Field[]
     */
    public function createFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array
    {
        $fieldSchemaDefinitionPointer = SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $fields = [];
        foreach (array_keys($fieldSchemaDefinitionPointer) as $fieldName) {
            $fields[] = new Field(
                $fullSchemaDefinition,
                array_merge(
                    $fieldSchemaDefinitionPath,
                    [
                        $fieldName
                    ]
                )
            );
        }
        return $fields;
    }

    /**
     * @return Field[]
     */
    public function getFieldsFromPath(array &$fullSchemaDefinition, array $fieldSchemaDefinitionPath): array
    {
        $fieldSchemaDefinitionPointer = SchemaDefinitionHelpers::advancePointerToPath($fullSchemaDefinition, $fieldSchemaDefinitionPath);
        $schemaDefinitionReferenceObjectIDs = [];
        foreach (array_keys($fieldSchemaDefinitionPointer) as $fieldName) {
            $schemaDefinitionReferenceObjectIDs[] = SchemaDefinitionHelpers::getSchemaDefinitionReferenceObjectID(array_merge(
                $fieldSchemaDefinitionPath,
                [
                    $fieldName
                ]
            ));
        }
        /** @var Field[] */
        return $this->schemaDefinitionReferenceRegistry->getSchemaDefinitionReferenceObjects($schemaDefinitionReferenceObjectIDs);
    }    
}
