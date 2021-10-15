<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
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
            SchemaDefinition::NAMESPACED_NAME => $this->typeResolver->getNamespacedTypeName(),
            SchemaDefinition::ELEMENT_NAME => $this->typeResolver->getTypeName(),
        ];
        if ($description = $this->typeResolver->getTypeDescription()) {
            $schemaDefinition[SchemaDefinition::DESCRIPTION] = $description;
        }
        return $schemaDefinition;
    }
}
