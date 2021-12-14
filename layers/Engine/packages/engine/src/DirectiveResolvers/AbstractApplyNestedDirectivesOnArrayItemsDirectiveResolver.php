<?php

declare(strict_types=1);

namespace PoP\Engine\DirectiveResolvers;

use PoP\ComponentModel\DirectivePipeline\DirectivePipelineServiceInterface;
use PoP\ComponentModel\DirectiveResolvers\AbstractGlobalDirectiveResolver;
use PoP\ComponentModel\Error\Error;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\AbstractRelationalTypeResolver;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\ComponentConfiguration;
use PoP\Engine\Dataloading\Expressions;
use PoP\FieldQuery\QueryHelpers;
use PoP\FieldQuery\QuerySyntax;
use stdClass;

abstract class AbstractApplyNestedDirectivesOnArrayItemsDirectiveResolver extends AbstractGlobalDirectiveResolver
{
    /**
     * Use a value that can't be part of a fieldName, that's legible, and that conveys the meaning of sublevel. The value "." is adequate
     */
    public const PROPERTY_SEPARATOR = '.';

    private ?DirectivePipelineServiceInterface $directivePipelineService = null;

    final public function setDirectivePipelineService(DirectivePipelineServiceInterface $directivePipelineService): void
    {
        $this->directivePipelineService = $directivePipelineService;
    }
    final protected function getDirectivePipelineService(): DirectivePipelineServiceInterface
    {
        return $this->directivePipelineService ??= $this->instanceManager->getInstance(DirectivePipelineServiceInterface::class);
    }

    public function getDirectiveArgNameTypeResolvers(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        if (!ComponentConfiguration::enablePassingExpressionsByArgInNestedDirectives()) {
            return [];
        }
        return [
            'addExpressions' => $this->getDangerouslyDynamicScalarTypeResolver(),
            'appendExpressions' => $this->getDangerouslyDynamicScalarTypeResolver(),
        ];
    }

    public function getDirectiveArgDescription(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): ?string
    {
        return match ($directiveArgName) {
            'addExpressions' => sprintf(
                $this->getTranslationAPI()->__('Expressions to inject to the composed directive. The value of the affected field can be provided under special expression `%s`', 'component-model'),
                QueryHelpers::getExpressionQuery(Expressions::NAME_VALUE)
            ),
            'appendExpressions' => sprintf(
                $this->getTranslationAPI()->__('Append a value to an expression which must be an array, to inject to the composed directive. If the array has not been set, it is initialized as an empty array. The value of the affected field can be provided under special expression `%s`', 'component-model'),
                QueryHelpers::getExpressionQuery(Expressions::NAME_VALUE)
            ),
            default => parent::getDirectiveArgDescription($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgDefaultValue(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): mixed
    {
        return match ($directiveArgName) {
            default => parent::getDirectiveArgDefaultValue($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveArgTypeModifiers(RelationalTypeResolverInterface $relationalTypeResolver, string $directiveArgName): int
    {
        return match ($directiveArgName) {
            'addExpressions',
            'appendExpressions'
                => SchemaTypeModifiers::IS_ARRAY,
            default
                => parent::getDirectiveArgTypeModifiers($relationalTypeResolver, $directiveArgName),
        };
    }

    public function getDirectiveExpressions(RelationalTypeResolverInterface $relationalTypeResolver): array
    {
        return [
            Expressions::NAME_VALUE => sprintf(
                $this->getTranslationAPI()->__('Value of the array element from the current iteration, available for params \'%s\' and \'%s\'', 'component-model'),
                'addExpressions',
                'appendExpressions'
            ),
        ];
    }

    /**
     * Execute directive <transformProperty> to each of the elements on the affected field, which must be an array
     * This is achieved by executing the following logic:
     * 1. Unpack the elements of the array into a temporary property for each, in the current object
     * 2. Execute <transformProperty> on each property
     * 3. Pack into the array, once again, and remove all temporary properties
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$idsDataFields,
        array &$succeedingPipelineIDsDataFields,
        array &$succeedingPipelineDirectiveResolverInstances,
        array &$objectIDItems,
        array &$unionDBKeyIDs,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations,
        array &$schemaNotices,
        array &$schemaTraces
    ): void {

        // If there are no composed directives to execute, then nothing to do
        if (!$this->nestedDirectivePipelineData) {
            $schemaWarnings[] = [
                Tokens::PATH => [$this->directive],
                Tokens::MESSAGE => $this->getTranslationAPI()->__('No composed directives were provided, so nothing to do for this directive', 'component-model'),
            ];
            return;
        }

        /**
         * Collect all ID => dataFields for the arrayItems
         */
        $arrayItemIdsProperties = [];
        $dbKey = $relationalTypeResolver->getTypeOutputDBKey();
        /**
         * Execute composed directive only if the validations do not fail
         */
        $execute = false;

        // 1. Unpack all elements of the array into a property for each
        // By making the property "propertyName:key", the "key" can be extracted and passed under expression `%key%` to the function
        foreach ($idsDataFields as $id => $dataFields) {
            $object = $objectIDItems[$id];
            foreach ($dataFields['direct'] as $field) {
                $fieldOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $field, $object);

                // Validate that the property exists
                $isValueInDBItems = array_key_exists($fieldOutputKey, $dbItems[(string)$id] ?? []);
                if (!$isValueInDBItems && !array_key_exists($fieldOutputKey, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                    if ($fieldOutputKey != $field) {
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->getTranslationAPI()->__('Field \'%s\' (under property \'%s\') hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'),
                                $field,
                                $fieldOutputKey,
                                $id
                            ),
                        ];
                    } else {
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->getTranslationAPI()->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'),
                                $fieldOutputKey,
                                $id
                            ),
                        ];
                    }
                    continue;
                }

                $value = $isValueInDBItems ?
                    $dbItems[(string)$id][$fieldOutputKey] :
                    $previousDBItems[$dbKey][(string)$id][$fieldOutputKey];

                // If the array is null or empty, nothing to do
                if (!$value) {
                    continue;
                }

                // Validate that the value is an array or stdClass
                if (!(is_array($value) || ($value instanceof stdClass))) {
                    if ($fieldOutputKey != $field) {
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->getTranslationAPI()->__('The value for field \'%s\' (under property \'%s\') is not an array, so execution of this directive can\'t continue', 'component-model'),
                                $field,
                                $fieldOutputKey,
                                $id
                            ),
                        ];
                    } else {
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->getTranslationAPI()->__('The value for field \'%s\' is not an array, so execution of this directive can\'t continue', 'component-model'),
                                $fieldOutputKey,
                                $id
                            ),
                        ];
                    }
                    continue;
                }

                // Obtain the elements composing the field, to re-create a new field for each arrayItem
                $fieldParts = $this->getFieldQueryInterpreter()->listField($field);
                $fieldName = $fieldParts[0];
                $fieldArgs = $fieldParts[1];
                $fieldAlias = $fieldParts[2];
                $fieldSkipOutputIfNull = $fieldParts[3];
                $fieldDirectives = $fieldParts[4];

                // The value is an array or an stdClass. Unpack all the elements into their own property
                $array = (array) $value;
                if ($arrayItems = $this->getArrayItems($array, $id, $field, $relationalTypeResolver, $objectIDItems, $dbItems, $previousDBItems, $variables, $messages, $objectErrors, $objectWarnings, $objectDeprecations)) {
                    $execute = true;
                    foreach ($arrayItems as $key => &$value) {
                        // Add into the $idsDataFields object for the array items
                        // Watch out: function `regenerateAndExecuteFunction` receives `$idsDataFields` and not `$idsDataFieldOutputKeys`, so then re-create the "field" assigning a new alias
                        // If it has an alias, use it. If not, use the fieldName
                        $arrayItemAlias = $this->createPropertyForArrayItem($fieldAlias ? $fieldAlias : QuerySyntax::SYMBOL_FIELDALIAS_PREFIX . $fieldName, (string) $key);
                        $arrayItemProperty = $this->getFieldQueryInterpreter()->composeField(
                            $fieldName,
                            $fieldArgs,
                            $arrayItemAlias,
                            $fieldSkipOutputIfNull,
                            $fieldDirectives
                        );
                        $arrayItemPropertyOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $arrayItemProperty, $object);
                        // Place into the current object
                        $dbItems[(string)$id][$arrayItemPropertyOutputKey] = $value;
                        // Place it into list of fields to process
                        $arrayItemIdsProperties[(string)$id]['direct'][] = $arrayItemProperty;
                    }
                    $arrayItemIdsProperties[(string)$id]['conditional'] = [];

                    $this->addExpressionsForObject($relationalTypeResolver, $id, $field, $objectIDItems, $dbItems, $previousDBItems, $variables, $messages, $objectErrors, $objectWarnings, $objectDeprecations, $schemaErrors, $schemaWarnings, $schemaDeprecations);
                }
            }
        }

        if ($execute) {
            // Build the directive pipeline
            $directiveResolverInstances = array_map(
                function ($pipelineStageData) {
                    return $pipelineStageData['instance'];
                },
                $this->nestedDirectivePipelineData
            );
            $nestedDirectivePipeline = $this->getDirectivePipelineService()->getDirectivePipeline($directiveResolverInstances);
            // Fill the idsDataFields for each directive in the pipeline
            $pipelineArrayItemIdsProperties = [];
            for ($i = 0; $i < count($directiveResolverInstances); $i++) {
                $pipelineArrayItemIdsProperties[] = $arrayItemIdsProperties;
            }
            // 2. Execute the composed directive pipeline on all arrayItems
            $nestedSchemaErrors = $nestedIDObjectErrors = [];
            $nestedDirectivePipeline->resolveDirectivePipeline(
                $relationalTypeResolver,
                $pipelineArrayItemIdsProperties, // Here we pass the properties to the array elements!
                $directiveResolverInstances,
                $objectIDItems,
                $unionDBKeyIDs,
                $dbItems,
                $previousDBItems,
                $variables,
                $messages,
                $nestedIDObjectErrors,
                $objectWarnings,
                $objectDeprecations,
                $objectNotices,
                $objectTraces,
                $nestedSchemaErrors,
                $schemaWarnings,
                $schemaDeprecations,
                $schemaNotices,
                $schemaTraces
            );

            // If there was an error, prepend the path
            if ($nestedSchemaErrors) {
                $schemaError = [
                    Tokens::PATH => [$this->directive],
                    Tokens::MESSAGE => $this->getTranslationAPI()->__('The nested directive has produced errors', 'component-model'),
                ];
                foreach ($nestedSchemaErrors as $nestedSchemaError) {
                    array_unshift($nestedSchemaError[Tokens::PATH], $this->directive);
                    $this->prependPathOnNestedErrors($nestedSchemaError);
                    $schemaError[Tokens::EXTENSIONS][Tokens::NESTED][] = $nestedSchemaError;
                }
                $schemaErrors[] = $schemaError;
            }
            if ($nestedIDObjectErrors) {
                foreach ($nestedIDObjectErrors as $id => $nestedObjectErrors) {
                    foreach ($nestedObjectErrors as &$nestedDBError) {
                        array_unshift($nestedDBError[Tokens::PATH], $this->directive);
                        $this->prependPathOnNestedErrors($nestedDBError);
                    }
                    $objectErrors[(string) $id] = $nestedObjectErrors;
                }
            }

            // 3. Compose the array from the results for each array item
            foreach ($idsDataFields as $id => $dataFields) {
                $object = $objectIDItems[$id];
                foreach ($dataFields['direct'] as $field) {
                    $fieldOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $field, $object);
                    $isValueInDBItems = array_key_exists($fieldOutputKey, $dbItems[(string)$id] ?? []);
                    $value = $isValueInDBItems ?
                        $dbItems[(string)$id][$fieldOutputKey] :
                        $previousDBItems[$dbKey][(string)$id][$fieldOutputKey];

                    // If the array is null or empty, nothing to do
                    if (!$value) {
                        continue;
                    }
                    if (!(is_array($value) || ($value instanceof stdClass))) {
                        continue;
                    }

                    // Obtain the elements composing the field, to re-create a new field for each arrayItem
                    $fieldParts = $this->getFieldQueryInterpreter()->listField($field);
                    $fieldName = $fieldParts[0];
                    $fieldArgs = $fieldParts[1];
                    $fieldAlias = $fieldParts[2];
                    $fieldSkipOutputIfNull = $fieldParts[3];
                    $fieldDirectives = $fieldParts[4];

                    // If there are errors, it will return null. Don't add the errors again
                    $arrayItemObjectErrors = $arrayItemObjectWarnings = $arrayItemObjectDeprecations = [];
                    $array = (array) $value;
                    $arrayItems = $this->getArrayItems($array, $id, $field, $relationalTypeResolver, $objectIDItems, $dbItems, $previousDBItems, $variables, $messages, $arrayItemObjectErrors, $arrayItemObjectWarnings, $arrayItemObjectDeprecations);
                    // The value is an array. Unpack all the elements into their own property
                    foreach ($arrayItems as $key => &$value) {
                        $arrayItemAlias = $this->createPropertyForArrayItem($fieldAlias ? $fieldAlias : QuerySyntax::SYMBOL_FIELDALIAS_PREFIX . $fieldName, (string) $key);
                        $arrayItemProperty = $this->getFieldQueryInterpreter()->composeField(
                            $fieldName,
                            $fieldArgs,
                            $arrayItemAlias,
                            $fieldSkipOutputIfNull,
                            $fieldDirectives
                        );
                        // Place the result of executing the function on the array item
                        $arrayItemPropertyOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $arrayItemProperty, $object);
                        $arrayItemValue = $dbItems[(string)$id][$arrayItemPropertyOutputKey];
                        // Remove this temporary property from $dbItems
                        unset($dbItems[(string)$id][$arrayItemPropertyOutputKey]);
                        // Validate it's not an error
                        if (GeneralUtils::isError($arrayItemValue)) {
                            /** @var Error */
                            $error = $arrayItemValue;
                            $objectErrors[(string)$id][] = [
                                Tokens::PATH => [$this->directive],
                                Tokens::MESSAGE => sprintf(
                                    $this->getTranslationAPI()->__('Transformation of element with key \'%s\' on array from property \'%s\' on object with ID \'%s\' failed due to error: %s', 'component-model'),
                                    $key,
                                    $fieldOutputKey,
                                    $id,
                                    $error->getMessageOrCode()
                                ),
                            ];
                            continue;
                        }
                        // Place the result for the array in the original property
                        $this->addProcessedItemBackToDBItems($relationalTypeResolver, $dbItems, $objectErrors, $objectWarnings, $objectDeprecations, $objectNotices, $objectTraces, $id, $fieldOutputKey, $key, $arrayItemValue);
                    }
                }
            }
        }
    }
    /**
     * Place the result for the array in the original property
     */
    protected function addProcessedItemBackToDBItems(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$dbItems,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$objectNotices,
        array &$objectTraces,
        $id,
        string $fieldOutputKey,
        int|string $arrayItemKey,
        $arrayItemValue
    ): void {
        if (is_array($dbItems[(string)$id][$fieldOutputKey])) {
            $dbItems[(string)$id][$fieldOutputKey][$arrayItemKey] = $arrayItemValue;
        } else {
            // stdClass
            $dbItems[(string)$id][$fieldOutputKey]->$arrayItemKey = $arrayItemValue;
        }
    }

    /**
     * Return the items to iterate on
     */
    abstract protected function getArrayItems(array &$array, int | string $id, string $field, RelationalTypeResolverInterface $relationalTypeResolver, array &$objectIDItems, array &$dbItems, array &$previousDBItems, array &$variables, array &$messages, array &$objectErrors, array &$objectWarnings, array &$objectDeprecations): ?array;

    /**
     * Create a property for storing the array item in the current object
     */
    protected function createPropertyForArrayItem(string $fieldAliasOrName, string $key): string
    {
        return implode(self::PROPERTY_SEPARATOR, [$fieldAliasOrName, $key]);
    }

    // protected function extractElementsFromArrayItemProperty(string $arrayItemProperty): array
    // {
    //     // Notice that we may be nesting several directives, such as <forEach<forEach<transform>>
    //     // Then, the property will contain several instances of unpacking arrayItems
    //     // For this reason, when extracting the property, obtain the right-side value from the last instance of the separator
    //     // $pos = QueryUtils::findLastSymbolPosition($arrayItemProperty, self::PROPERTY_SEPARATOR);
    //     return explode(self::PROPERTY_SEPARATOR, $arrayItemProperty);
    // }
    protected function addExpressionsForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        string $field,
        array &$objectIDItems,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations
    ): void {
        // Enable the query to provide variables to pass down
        $addExpressions = $this->directiveArgsForSchema['addExpressions'] ?? [];
        $appendExpressions = $this->directiveArgsForSchema['appendExpressions'] ?? [];
        if ($addExpressions || $appendExpressions) {
            // The expressions may need `$value`, so add it
            $object = $objectIDItems[$id];
            $fieldOutputKey = $this->getFieldQueryInterpreter()->getUniqueFieldOutputKey($relationalTypeResolver, $field, $object);
            $isValueInDBItems = array_key_exists($fieldOutputKey, $dbItems[(string)$id] ?? []);
            $dbKey = $relationalTypeResolver->getTypeOutputDBKey();
            $value = $isValueInDBItems ?
                $dbItems[(string)$id][$fieldOutputKey] :
                $previousDBItems[$dbKey][(string)$id][$fieldOutputKey];
            $this->addExpressionForObject($id, Expressions::NAME_VALUE, $value, $messages);
            $expressions = $this->getExpressionsForObject($id, $variables, $messages);

            $options = [
                AbstractRelationalTypeResolver::OPTION_VALIDATE_SCHEMA_ON_RESULT_ITEM => true,
            ];
            foreach ($addExpressions as $key => $value) {
                // Evaluate the $value, since it may be a function
                if ($this->getFieldQueryInterpreter()->isFieldArgumentValueAField($value)) {
                    $resolvedValue = $relationalTypeResolver->resolveValue($objectIDItems[(string)$id], $value, $variables, $expressions, $options);
                    // Merge the objectWarnings, if any
                    if ($storedObjectWarnings = $this->getFeedbackMessageStore()->retrieveAndClearObjectWarnings($id)) {
                        $objectWarnings[$id] = array_merge(
                            $objectWarnings[$id] ?? [],
                            $storedObjectWarnings
                        );
                    }
                    if (GeneralUtils::isError($resolvedValue)) {
                        // Show the error message, and return nothing
                        /** @var Error */
                        $error = $resolvedValue;
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->getTranslationAPI()->__('Executing field \'%s\' on object with ID \'%s\' produced error: %s. Setting expression \'%s\' was ignored', 'pop-component-model'),
                                $value,
                                $id,
                                $error->getMessageOrCode(),
                                $key
                            ),
                        ];
                        continue;
                    }
                    $value = $resolvedValue;
                }
                $this->addExpressionForObject($id, (string) $key, $value, $messages);
            }
            foreach ($appendExpressions as $key => $value) {
                $existingValue = $this->getExpressionForObject($id, (string) $key, $messages) ?? [];
                // Evaluate the $value, since it may be a function
                if ($this->getFieldQueryInterpreter()->isFieldArgumentValueAField($value)) {
                    $resolvedValue = $relationalTypeResolver->resolveValue($objectIDItems[(string)$id], $value, $variables, $expressions, $options);
                    // Merge the objectWarnings, if any
                    if ($storedObjectWarnings = $this->getFeedbackMessageStore()->retrieveAndClearObjectWarnings($id)) {
                        $objectWarnings[$id] = array_merge(
                            $objectWarnings[$id] ?? [],
                            $storedObjectWarnings
                        );
                    }
                    if (GeneralUtils::isError($resolvedValue)) {
                        // Show the error message, and return nothing
                        /** @var Error */
                        $error = $resolvedValue;
                        $objectErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->getTranslationAPI()->__('Executing field \'%s\' on object with ID \'%s\' produced error: %s. Setting expression \'%s\' was ignored', 'pop-component-model'),
                                $value,
                                $id,
                                $error->getMessageOrCode(),
                                $key
                            ),
                        ];
                        continue;
                    }
                    $existingValue[] = $resolvedValue;
                }
                $this->addExpressionForObject($id, (string) $key, $existingValue, $messages);
            }
        }
    }
}
