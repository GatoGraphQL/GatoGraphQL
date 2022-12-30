<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoPAPI\API\Schema\SchemaDefinition;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;

abstract class AbstractNamedTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();
        $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getNamedTypeExtensions();
        return $schemaDefinition;
    }

    /**
     * @return array<string,mixed>
     */
    protected function getNamedTypeExtensions(): array
    {
        $namedTypeExtensions = [
            SchemaDefinition::NAMESPACED_NAME => $this->typeResolver->getNamespacedTypeName(),
            SchemaDefinition::ELEMENT_NAME => $this->typeResolver->getTypeName(),
        ];

        /**
         * Enum-like "possible values": only for EnumString type resolvers
         */
        if ($this->typeResolver instanceof AbstractEnumStringScalarTypeResolver) {
            /** @var AbstractEnumStringScalarTypeResolver */
            $enumStringScalarTypeResolver = $this->typeResolver;
            $namedTypeExtensions[SchemaDefinition::POSSIBLE_VALUES] = $enumStringScalarTypeResolver->getConsolidatedPossibleValues();
        }

        return $namedTypeExtensions;
    }
}
