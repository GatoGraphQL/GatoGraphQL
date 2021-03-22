<?php

declare(strict_types=1);

namespace GraphQLByPoP\GraphQLServer\ObjectModels;

use PoP\API\Schema\SchemaDefinition;
use GraphQLByPoP\GraphQLServer\ObjectModels\AbstractType;
use GraphQLByPoP\GraphQLServer\ObjectModels\ResolveTypeSchemaDefinitionReferenceTrait;

trait HasTypeSchemaDefinitionReferenceTrait
{
    use ResolveTypeSchemaDefinitionReferenceTrait;

    protected AbstractType $type;

    public function getType(): AbstractType
    {
        return $this->type;
    }
    /**
     * Obtain the reference to the type from the registryMap
     */
    protected function initType(): void
    {
        // Create a reference to the type in the referenceMap.
        // Either it has already been created, or will be created later on
        // It is done this way because from the Schema we initialize the Types,
        // each of which initializes their Fields (we are here), which may reference
        // a different Type that doesn't exist yet, and can't be created here
        // or it creates an endless loop
        $typeName = $this->schemaDefinition[SchemaDefinition::ARGNAME_TYPE];
        $this->type = $this->getTypeFromTypeName($typeName);
    }
    public function getTypeID(): string
    {
        return $this->getType()->getID();
    }
}
