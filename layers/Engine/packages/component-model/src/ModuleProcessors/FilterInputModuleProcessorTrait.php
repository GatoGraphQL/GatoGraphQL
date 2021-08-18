<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ModuleProcessors;

use PoP\ComponentModel\Schema\SchemaDefinition;

trait FilterInputModuleProcessorTrait
{
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

    public function getFilterInputSchemaDefinitionResolver(array $module): ?DataloadQueryArgsSchemaFilterInputModuleProcessorInterface
    {
        return null;
    }

    /**
     * Documentation for the module
     */
    protected function getFilterInputSchemaDefinition(array $module): array
    {
        $schemaDefinition = [
            SchemaDefinition::ARGNAME_NAME => $this->getName($module),
        ];
        if ($filterSchemaDefinitionResolver = $this->getFilterInputSchemaDefinitionResolver($module)) {
            $type = $filterSchemaDefinitionResolver->getSchemaFilterInputType($module);
            $schemaDefinition[SchemaDefinition::ARGNAME_TYPE] = $type;
            if ($description = $filterSchemaDefinitionResolver->getSchemaFilterInputDescription($module)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DESCRIPTION] = $description;
            }
            // If setting the "array of arrays" flag, there's no need to set the "array" flag
            $isArrayOfArrays = $filterSchemaDefinitionResolver->getSchemaFilterInputIsArrayOfArraysType($module);
            if (
                $filterSchemaDefinitionResolver->getSchemaFilterInputIsArrayType($module)
                || $isArrayOfArrays
            ) {
                $schemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] = true;
                if ($filterSchemaDefinitionResolver->getSchemaFilterInputIsNonNullableItemsInArrayType($module)) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY] = true;
                }
                if ($isArrayOfArrays) {
                    $schemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] = true;
                    if ($filterSchemaDefinitionResolver->getSchemaFilterInputIsNonNullableItemsInArrayOfArraysType($module)) {
                        $schemaDefinition[SchemaDefinition::ARGNAME_IS_NON_NULLABLE_ITEMS_IN_ARRAY_OF_ARRAYS] = true;
                    }
                }
            }
            $defaultValue = $filterSchemaDefinitionResolver->getSchemaFilterInputDefaultValue($module);
            if ($defaultValue !== null) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DEFAULT_VALUE] = $defaultValue;
            }
            if ($filterSchemaDefinitionResolver->getSchemaFilterInputMandatory($module)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_MANDATORY] = true;
            }
            if ($deprecationDescription = $filterSchemaDefinitionResolver->getSchemaFilterInputDeprecationDescription($module)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATED] = true;
                $schemaDefinition[SchemaDefinition::ARGNAME_DEPRECATIONDESCRIPTION] = $deprecationDescription;
            }
            $filterSchemaDefinitionResolver->addSchemaDefinitionForFilter($schemaDefinition, $module);
        }
        return $schemaDefinition;
    }
}
