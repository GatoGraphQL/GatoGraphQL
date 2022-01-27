<?php

declare(strict_types=1);

namespace PoPAPI\API\ModuleProcessors;

use PoP\Root\App;
use PoP\ComponentModel\ModuleProcessors\AbstractQueryDataModuleProcessor;
use PoP\FieldQuery\QueryHelpers;
use PoP\GraphQLParser\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Parser\Ast\LeafField;

abstract class AbstractRelationalFieldQueryDataModuleProcessor extends AbstractQueryDataModuleProcessor
{
    /**
     * @return LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        /**
         * The fields which have a numeric key only are the data-fields
         * for the current module level.
         * Process only the fields without "skip output if null".
         * Those will be processed on function `getConditionalOnDataFieldSubmodules`
         */
        return array_filter(
            $this->getPropertyFields($module),
            fn (LeafField $field) => !$field->isSkipOutputIfNull(),
        );
    }

    /**
     * Property fields: Those fields which have a numeric key only
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
     * @return FieldInterface[]
     */
    protected function getFields(array $module, $moduleAtts): array
    {
        // If it is a virtual module, the fields are coded inside the virtual module atts
        if (!is_null($moduleAtts)) {
            return $moduleAtts['fields'];
        }
        // If it is a normal module, it is the first added, then simply get the fields from the application state
        return App::getState('executable-query') ?? [];
    }

    public function getDomainSwitchingSubmodules(array $module): array
    {
        $ret = parent::getDomainSwitchingSubmodules($module);

        // The fields which are not numeric are the keys from which to switch database domain
        $fieldNestedFields = $this->getFieldsWithNestedSubfields($module);

        // Process only the fields without "skip output if null". Those will be processed on function `getConditionalOnDataFieldDomainSwitchingSubmodules`
        $fieldNestedFields = array_filter(
            $this->getFieldsWithNestedSubfields($module),
            fn (FieldInterface $field) => !$field->isSkipOutputIfNull(),
            ARRAY_FILTER_USE_KEY
        );

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

    /**
     * Nested fields: Those fields which have a field as key and an array of submodules as value
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

    public function getConditionalOnDataFieldSubmodules(array $module): array
    {
        $ret = parent::getConditionalOnDataFieldSubmodules($module);

        // Calculate the property fields with "skip output if null" on true
        $propertyFields = array_filter(
            $this->getPropertyFields($module),
            fn (FieldInterface $field) => $field->isSkipOutputIfNull(),
        );
        $relationalFields = array_keys(array_filter(
            $this->getFieldsWithNestedSubfields($module),
            fn (FieldInterface $field) => $field->isSkipOutputIfNull(),
            ARRAY_FILTER_USE_KEY
        ));
        $fields = array_values(array_unique(array_merge(
            $propertyFields,
            $relationalFields
        )));

        // Create a "virtual" module with the fields corresponding to the next level module
        foreach ($fields as $field) {
            $conditionField = $this->getNotIsEmptyConditionField($field);
            $conditionalField = $this->getFieldQueryInterpreter()->removeSkipOuputIfNullFromField($field);
            $ret[$conditionField][] = [
                $module[0],
                $module[1],
                ['fields' => [$conditionalField]]
            ];
        }

        return $ret;
    }

    /**
     * Given a field, return its corresponding "not(isEmpty($field))
     */
    protected function getNotIsEmptyConditionField(string $field): string
    {
        $conditionFieldAlias = null;
        // Convert the field into its "not is null" version
        if ($fieldAlias = $this->getFieldQueryInterpreter()->getFieldAlias($field)) {
            $conditionFieldAlias = 'not-isnull-' . $fieldAlias;
        }
        return $this->getFieldQueryInterpreter()->getField(
            'not',
            [
                'value' => $this->getFieldQueryInterpreter()->getField(
                    'isNull',
                    [
                        'value' => $this->getFieldQueryInterpreter()->composeField(
                            $this->getFieldQueryInterpreter()->getFieldName($field),
                            $this->getFieldQueryInterpreter()->getFieldArgs($field) ?? QueryHelpers::getEmptyFieldArgs()
                        ),
                    ]
                ),
            ],
            $conditionFieldAlias,
            false,
            $this->getFieldQueryInterpreter()->getDirectives($field)
        );
    }

    public function getConditionalOnDataFieldDomainSwitchingSubmodules(array $module): array
    {
        $ret = parent::getConditionalOnDataFieldDomainSwitchingSubmodules($module);

        // Calculate the nested fields with "skip output if null" on true
        $fieldNestedFields = array_filter(
            $this->getFieldsWithNestedSubfields($module),
            fn (FieldInterface $field) => $field->isSkipOutputIfNull(),
            ARRAY_FILTER_USE_KEY
        );

        foreach ($fieldNestedFields as $field => $nestedFields) {
            $conditionField = $this->getNotIsEmptyConditionField($field);
            $conditionalField = $this->getFieldQueryInterpreter()->removeSkipOuputIfNullFromField($field);
            $ret[$conditionField][$conditionalField] = array(
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
