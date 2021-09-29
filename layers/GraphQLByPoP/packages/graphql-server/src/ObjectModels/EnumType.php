<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\ComponentModel\Schema\SchemaDefinition;

class EnumType extends AbstractDynamicType
{
    use NonDocumentableTypeTrait;

    /**
     * @var EnumValue[]
     */
    protected array $enumValues;

    public function __construct(array &$fullSchemaDefinition, array $schemaDefinitionPath, array $customDefinition = [])
    {
        parent::__construct($fullSchemaDefinition, $schemaDefinitionPath, $customDefinition);

        $this->initEnumValues($fullSchemaDefinition, $schemaDefinitionPath);
    }
    protected function initEnumValues(array &$fullSchemaDefinition, array $schemaDefinitionPath): void
    {
        $this->enumValues = [];
        if ($enumValues = $this->schemaDefinition[SchemaDefinition::ARGNAME_ENUM_VALUES] ?? null) {
            foreach (array_keys($enumValues) as $enumValueName) {
                $enumValueSchemaDefinitionPath = array_merge(
                    $schemaDefinitionPath,
                    [
                        SchemaDefinition::ARGNAME_ENUM_VALUES,
                        $enumValueName,
                    ]
                );
                $this->enumValues[] = new EnumValue(
                    $fullSchemaDefinition,
                    $enumValueSchemaDefinitionPath
                );
            }
        }
    }

    protected function getDynamicTypeNamePropertyInSchema(): string
    {
        return SchemaDefinition::ARGNAME_ENUM_NAME;
    }
    public function getKind(): string
    {
        return TypeKinds::ENUM;
    }
    public function getEnumValues(bool $includeDeprecated = false): array
    {
        return $includeDeprecated ?
            $this->enumValues :
            array_filter(
                $this->enumValues,
                function (EnumValue $enumValue) {
                    return !$enumValue->isDeprecated();
                }
            );
    }
    public function getEnumValueIDs(bool $includeDeprecated = false): array
    {
        return array_map(
            function (EnumValue $enumValue) {
                return $enumValue->getID();
            },
            $this->getEnumValues($includeDeprecated)
        );
    }
}
