<?php

declare(strict_types=1);

namespace PoPAPI\API\ModuleProcessors;

use PoP\ComponentModel\App;
use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField;
use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\ModuleFieldInterface;
use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\RelationalModuleField;
use PoP\ComponentModel\ModuleProcessors\AbstractQueryDataModuleProcessor;

abstract class AbstractRelationalFieldQueryDataModuleProcessor extends AbstractQueryDataModuleProcessor
{
    /**
     * @return ModuleFieldInterface[]
     */
    protected function getFields(array $module, ?array $moduleAtts): array
    {
        // If it is a virtual module, the fields are coded inside the virtual module atts
        if ($moduleAtts !== null) {
            return $moduleAtts['fields'];
        }

        /**
         * If it is a normal module, it is the first added,
         * then simply get the fields from the application state.
         */
        return App::getState('executable-query') ?? [];
    }

    /**
     * Property fields: Those fields which have a numeric key only
     * @return LeafModuleField[]
     */
    protected function getPropertyFields(array $module): array
    {
        $moduleAtts = $module[2] ?? null;
        $fields = $this->getFields($module, $moduleAtts);
        return array_values(array_filter(
            $fields,
            fn (string|int $key) => is_numeric($key),
            ARRAY_FILTER_USE_KEY
        ));
    }

    /**
     * Nested fields: Those fields which have a field as key and an array of submodules as value
     * @return RelationalModuleField[]
     */
    protected function getFieldsWithNestedSubfields(array $module): array
    {
        $moduleAtts = $module[2] ?? null;
        $fields = $this->getFields($module, $moduleAtts);
        return array_filter(
            $fields,
            fn (string|int $key) => !is_numeric($key),
            ARRAY_FILTER_USE_KEY
        );
    }

    /**
     * @return LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        /**
         * The fields which have a numeric key only are the data-fields
         * for the current module level.
         */
        return $this->getPropertyFields($module);
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getDomainSwitchingSubmodules(array $module): array
    {
        $ret = parent::getDomainSwitchingSubmodules($module);

        // The fields which are not numeric are the keys from which to switch database domain
        $fieldNestedFields = $this->getFieldsWithNestedSubfields($module);

        // Create a "virtual" module with the fields corresponding to the next level module
        foreach ($fieldNestedFields as $field => $nestedFields) {
            $ret[$field] = array(
                [
                    $module[0],
                    $module[1],
                    ['fields' => $nestedFields]
                ],
            );
        }
        return $ret;
    }
}
