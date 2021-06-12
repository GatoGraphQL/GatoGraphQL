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
            if ($filterSchemaDefinitionResolver->getSchemaFilterInputIsArrayType($module)) {
                $schemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] = true;
            }
            /**
             * This value will not be used with GraphQL, but can be used by PoP.
             * 
             * While GraphQL has a strong type system, PoP takes a more lenient approach,
             * enabling fields to maybe be an array, maybe not.
             * 
             * Eg: `echo(object: ...)` will print back whatever provided,
             * whether `String` or `[String]`. Its input is `Mixed`, which can comprise
             * an `Object`, so it could be provided as an array, or also `String`, which
             * will not be an array.
             * 
             * Whenever `MAY_BE_ARRAY` flag is on, the server will skip validations
             * concerning an input being array or not.
             */
            if (in_array($type, [
                SchemaDefinition::TYPE_INPUT_OBJECT,
                SchemaDefinition::TYPE_OBJECT,
                SchemaDefinition::TYPE_MIXED,
            ])) {
                $schemaDefinition[SchemaDefinition::ARGNAME_MAY_BE_ARRAY] = true;
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
