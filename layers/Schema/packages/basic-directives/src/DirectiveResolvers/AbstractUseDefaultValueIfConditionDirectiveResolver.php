<?php

declare(strict_types=1);

namespace PoPSchema\BasicDirectives\DirectiveResolvers;

use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoPSchema\BasicDirectives\Enums\DefaultConditionEnum;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractSchemaDirectiveResolver;

abstract class AbstractUseDefaultValueIfConditionDirectiveResolver extends AbstractSchemaDirectiveResolver
{
    protected function getDefaultValue()
    {
        return null;
    }

    public function resolveDirective(
        TypeResolverInterface $typeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$resultIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$dbNotices,
        array &$dbTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {
        // Replace all the NULL results with the default value
        $fieldOutputKeyCache = [];
        foreach ($idsDataFields as $id => $dataFields) {
            // Use either the default value passed under param "value" or, if this is NULL, use a predefined value
            $expressions = $this->getExpressionsForResultItem($id, $variables, $messages);
            $resultItem = $resultIDItems[$id];
            list(
                $resultItemValidDirective,
                $resultItemDirectiveName,
                $resultItemDirectiveArgs
            ) = $this->dissectAndValidateDirectiveForResultItem($typeResolver, $resultItem, $variables, $expressions, $dbErrors, $dbWarnings, $dbDeprecations);
            // Check that the directive is valid. If it is not, $dbErrors will have the error already added
            if (is_null($resultItemValidDirective)) {
                continue;
            }
            // Take the default value from the directiveArgs
            $defaultValue = $resultItemDirectiveArgs['value'] ?? null;
            $condition = $resultItemDirectiveArgs['condition'];
            if (!is_null($defaultValue)) {
                foreach ($dataFields['direct'] as $field) {
                    // Get the fieldOutputKey from the cache, or calculate it
                    if (!isset($fieldOutputKeyCache[$field])) {
                        $fieldOutputKeyCache[$field] = $this->fieldQueryInterpreter->getFieldOutputKey($field);
                    }
                    $fieldOutputKey = $fieldOutputKeyCache[$field];
                    // If it is null, replace it with the default value
                    if ($this->matchesCondition($condition, $dbItems[$id][$fieldOutputKey])) {
                        $dbItems[$id][$fieldOutputKey] = $defaultValue;
                    }
                }
            }
        }
    }
    /**
     * Indicate if the value matches the condition under which to inject the default value
     */
    protected function matchesCondition(string $condition, mixed $value): bool
    {
        switch ($condition) {
            case DefaultConditionEnum::IS_NULL:
                return is_null($value);
            case DefaultConditionEnum::IS_EMPTY:
                return empty($value);
        }
        return false;
    }
    public function getSchemaDirectiveDescription(TypeResolverInterface $typeResolver): ?string
    {
        $defaultValue = $this->getDefaultValue();
        if (is_null($defaultValue)) {
            return $this->translationAPI->__('If the value of the field is `NULL` (or empty), replace it with the value provided under argument \'value\'', 'basic-directives');
        }
        return $this->translationAPI->__('If the value of the field is `NULL` (or empty), replace it with either the value provided under argument \'value\', or with a default value configured in the directive resolver', 'basic-directives');
    }
    public function getSchemaDirectiveArgs(TypeResolverInterface $typeResolver): array
    {
        /**
         * @var DefaultConditionEnum
         */
        $defaultConditionEnum = $this->instanceManager->getInstance(DefaultConditionEnum::class);
        $schemaDirectiveArg = [
            SchemaDefinition::ARGNAME_NAME => 'value',
            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_MIXED,
            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('If the value of the field is `NULL`, replace it with the value from this argument', 'basic-directives'),
        ];
        $defaultValue = $this->getDefaultValue();
        if (is_null($defaultValue)) {
            $schemaDirectiveArg[SchemaDefinition::ARGNAME_MANDATORY] = true;
        } else {
            $schemaDirectiveArg[SchemaDefinition::ARGNAME_DEFAULT_VALUE] = $defaultValue;
        }
        return [
            $schemaDirectiveArg,
            [
                SchemaDefinition::ARGNAME_NAME => 'condition',
                SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Condition under which using the default value kicks in', 'basic-directives'),
                SchemaDefinition::ARGNAME_ENUM_NAME => $defaultConditionEnum->getName(),
                SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                    $defaultConditionEnum->getValues()
                ),
                SchemaDefinition::ARGNAME_DEFAULT_VALUE => $this->getDefaultCondition(),
            ]
        ];
    }

    protected function getDefaultCondition(): string
    {
        return DefaultConditionEnum::IS_NULL;
    }
}
