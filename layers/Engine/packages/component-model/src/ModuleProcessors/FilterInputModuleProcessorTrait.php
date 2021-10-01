<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\ModuleProcessors\DataloadQueryArgsSchemaFilterInputModuleProcessorInterface;
use PoP\ComponentModel\Resolvers\FieldOrDirectiveSchemaDefinitionResolverTrait;
use PoP\ComponentModel\Schema\SchemaDefinition;

trait FilterInputModuleProcessorTrait
{
    use FieldOrDirectiveSchemaDefinitionResolverTrait;

    /**
     * Return an array of elements, instead of a single element, to enable filters with several inputs (such as "date", with inputs "date-from" and "date-to") to document all of them
     */
    public function getFilterInputSchemaDefinitionItems(array $module): array
    {
        $schemaDefinitionItems = [
            $this->getFilterInputSchemaDefinition($module),
        ];
        $this->modifyFilterSchemaDefinitionItems($schemaDefinitionItems, $module);
        return $schemaDefinitionItems;
    }

    /**
     * Function to override
     */
    protected function modifyFilterSchemaDefinitionItems(array &$schemaDefinitionItems, array $module): void
    {
    }

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
        $filterSchemaDefinitionResolver->addSchemaDefinitionForFilter($schemaDefinition, $module);
        
        return $schemaDefinition;
    }
}
