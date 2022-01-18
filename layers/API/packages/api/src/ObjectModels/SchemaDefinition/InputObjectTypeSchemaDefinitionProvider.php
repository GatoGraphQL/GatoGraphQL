<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\SchemaDefinitionHelpers;
use PoPAPI\API\Schema\TypeKinds;
use PoP\ComponentModel\TypeResolvers\InputObjectType\InputObjectTypeResolverInterface;

class InputObjectTypeSchemaDefinitionProvider extends AbstractNamedTypeSchemaDefinitionProvider
{
    public function __construct(
        protected InputObjectTypeResolverInterface $inputObjectTypeResolver,
    ) {
        parent::__construct($inputObjectTypeResolver);
    }

    public function getTypeKind(): string
    {
        return TypeKinds::INPUT_OBJECT;
    }

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = parent::getSchemaDefinition();

        $this->addInputFieldSchemaDefinitions($schemaDefinition);

        return $schemaDefinition;
    }

    final protected function addInputFieldSchemaDefinitions(array &$schemaDefinition): void
    {
        $schemaDefinition[SchemaDefinition::INPUT_FIELDS] = [];
        $schemaInputObjectTypeFieldResolvers = $this->inputObjectTypeResolver->getConsolidatedInputFieldNameTypeResolvers();
        foreach (array_keys($schemaInputObjectTypeFieldResolvers) as $inputFieldName) {
            // Fields may not be directly visible in the schema
            if ($this->inputObjectTypeResolver->skipExposingInputFieldInSchema($inputFieldName)) {
                continue;
            }

            $inputFieldSchemaDefinition = $this->inputObjectTypeResolver->getInputFieldSchemaDefinition($inputFieldName);

            // Extract the typeResolvers
            $inputFieldTypeResolver = $inputFieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
            $this->accessedTypeAndDirectiveResolvers[$inputFieldTypeResolver::class] = $inputFieldTypeResolver;
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($inputFieldSchemaDefinition);

            $schemaDefinition[SchemaDefinition::INPUT_FIELDS][$inputFieldName] = $inputFieldSchemaDefinition;
        }
    }
}
