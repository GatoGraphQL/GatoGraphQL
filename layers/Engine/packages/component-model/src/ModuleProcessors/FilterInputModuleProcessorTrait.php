<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;

trait FilterInputModuleProcessorTrait
{
    use FieldOrDirectiveSchemaDefinitionResolverTrait;

    abstract public function getFilterInputSchemaDefinitionResolver(array $module): DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;

    /**
     * Documentation for the module
     */
    protected function getFilterInputSchemaDefinition(array $module): array
    {
        $filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module);
        $inputTypeResolver = $filterSchemaDefinitionResolver->getSchemaFilterInputTypeResolver($module);
        $schemaDefinition = $this->getFieldOrDirectiveArgSchemaDefinition(
            $this->getName($module),
            $inputTypeResolver,
            $filterSchemaDefinitionResolver->getSchemaFilterInputDescription($module),
            $filterSchemaDefinitionResolver->getSchemaFilterInputDefaultValue($module),
            $filterSchemaDefinitionResolver->getSchemaFilterInputTypeModifiers($module)
        );
        if ($deprecationDescription = $filterSchemaDefinitionResolver->getSchemaFilterInputDeprecationDescription($module)) {
            $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] = true;
            $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
        }
        
        return $schemaDefinition;
    }
}
