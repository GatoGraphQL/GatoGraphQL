<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;

abstract class AbstractNamedTypeSchemaDefinitionProvider extends AbstractTypeSchemaDefinitionProvider
{
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();
        $schemaDefinition[SchemaDefinition::EXTENSIONS] = $this->getNamedTypeExtensions();
        return $schemaDefinition;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getNamedTypeExtensions(): array
    {
        return [
            SchemaDefinition::NAMESPACED_NAME => $this->typeResolver->getNamespacedTypeName(),
            SchemaDefinition::ELEMENT_NAME => $this->typeResolver->getTypeName(),
        ];
    }
}
