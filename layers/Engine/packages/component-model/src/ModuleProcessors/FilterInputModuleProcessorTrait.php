<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Schema\SchemaDefinition;

trait FilterInputModuleProcessorTrait
{
    /**
     * Return an array of elements, instead of a single element, to enable filters with several inputs (such as "date", with inputs "date-from" and "date-to") to document all of them
     *
     * @param array $module
     * @return array
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
     *
     * @param array $schemaDefinitionItems
     * @param array $module
     * @return void
     */
    protected function modifyFilterSchemaDefinitionItems(array &$schemaDefinitionItems, array $module)
    {
    }

    public function getFilterInputSchemaDefinitionResolver(array $module): ?DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
    {
        return null;
    }

    /**
     * Documentation for the module
     *
     * @param array $module
     * @return array
     */
    protected function getFilterInputSchemaDefinition(array $module): array
    {
        $schemaDefinition = [
            SchemaDefinition::ARGNAME_NAME => $this->getName($module),
        ];
        if ($filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module)) {
            if ($type = $filterSchemaDefinitionResolver->getSchemaFilterInputType($module)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_TYPE] = $type;
            }
            if ($description = $filterSchemaDefinitionResolver->getSchemaFilterInputDescription($module)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
            }
            if ($filterSchemaDefinitionResolver->getSchemaFilterInputMandatory($module)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_MANDATORY] = true;
            }
            if ($deprecationDescription = $filterSchemaDefinitionResolver->getSchemaFilterInputDeprecationDescription($module)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] = true;
                $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
            }
        }
        return $schemaDefinition;
    }
}
