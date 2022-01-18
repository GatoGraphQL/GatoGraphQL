<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoPAPI\API\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

abstract class AbstractTypeSchemaDefinitionProvider extends AbstractSchemaDefinitionProvider implements TypeSchemaDefinitionProviderInterface
{
    /**
     * @var array<string, RelationalTypeResolverInterface> Key: directive resolver class, Value: The Type Resolver Class which loads the directive
     */
    protected array $accessedDirectiveResolverClassRelationalTypeResolvers = [];


    public function __construct(
        protected TypeResolverInterface $typeResolver,
    ) {
    }

    /**
     * @return array<string, RelationalTypeResolverInterface> Key: directive resolver class, Value: The Type Resolver Class which loads the directive
     */
    final public function getAccessedDirectiveResolverClassRelationalTypeResolvers(): array
    {
        return $this->accessedDirectiveResolverClassRelationalTypeResolvers;
    }

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = [
            SchemaDefinition::NAME => $this->typeResolver->getMaybeNamespacedTypeName(),
        ];
        if ($description = $this->typeResolver->getTypeDescription()) {
            $schemaDefinition[SchemaDefinition::DESCRIPTION] = $description;
        }
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
