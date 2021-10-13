<?php

declare(strict_types=1);

namespace PoP\API\ObjectModels\SchemaDefinition;

use PoP\API\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;

abstract class AbstractTypeSchemaDefinitionProvider extends AbstractSchemaDefinitionProvider implements TypeSchemaDefinitionProviderInterface
{
    /**
     * @var array<string, TypeResolverInterface|DirectiveResolverInterface> Key: class, Value: Accessed Type and Directive Resolver
     */
    protected array $accessedTypeAndDirectiveResolvers = [];

    public function __construct(
        protected TypeResolverInterface $typeResolver,
    ) {  
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
