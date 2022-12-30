<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoPAPI\API\Schema\SchemaDefinition;
use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\EnumStringScalarTypeResolverInterface;

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
         * Enum-like "possible values" for EnumString type resolvers, `null` otherwise
         */
        if ($this->typeResolver instanceof EnumStringScalarTypeResolverInterface) {
            /** @var EnumStringScalarTypeResolverInterface */
            $enumStringScalarTypeResolver = $this->typeResolver;
            $namedTypeExtensions[SchemaDefinition::POSSIBLE_VALUES] = $enumStringScalarTypeResolver->getConsolidatedPossibleValues();
        }

        return $namedTypeExtensions;
    }
}
