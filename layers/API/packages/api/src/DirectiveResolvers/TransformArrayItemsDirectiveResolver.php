<?php

declare(strict_types=1);

namespace PoP\API\DirectiveResolvers;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\DirectiveResolvers\ApplyFunctionDirectiveResolver;
use PoP\Engine\DirectiveResolvers\ForEachDirectiveResolver;
use PoP\FieldQuery\QuerySyntax;

class TransformArrayItemsDirectiveResolver extends ApplyFunctionDirectiveResolver
{
    protected const PROPERTY_SEPARATOR = ':';
    public function getDirectiveName(): string
    {
        return 'transformArrayItems';
    }

    /**
     * This is a "Scripting" type directive
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SCRIPTING;
    }

    /**
     * No need to use this function anymore
     */
    public function getSchemaDirectiveDeprecationDescription(TypeResolverInterface $typeResolver): ?string
    {
        /** @var DirectiveResolverInterface */
        $forEachDirectiveResolver = $this->instanceManager->getInstance(ForEachDirectiveResolver::class);
        /** @var DirectiveResolverInterface */
        $applyFunctionDirectiveResolver = $this->instanceManager->getInstance(ApplyFunctionDirectiveResolver::class);
        return sprintf(
            $this->translationAPI->__('Use %s instead', 'component-model'),
            $this->fieldQueryInterpreter->getFieldDirectivesAsString([
                [
                    $forEachDirectiveResolver->getDirectiveName(),
                    '',
                    $this->fieldQueryInterpreter->getFieldDirectivesAsString([
                        [
                            $applyFunctionDirectiveResolver->getDirectiveName(),
                        ],
                    ]),
                ],
            ])
        );
    }

    /**
     * Execute directive <transformProperty> to each of the elements on the affected field, which must be an array
     * This is achieved by executing the following logic:
     * 1. Unpack the elements of the array into a temporary property for each, in the current object
     * 2. Execute <transformProperty> on each property
     * 3. Pack into the array, once again, and remove all temporary properties
     */
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
        $dbKey = $typeResolver->getTypeOutputName();
        /**
         * Collect all ID => dataFields for the arrayItems
         */
        $arrayItemIdsProperties = [];

        // 1. Unpack all elements of the array into a property for each
        // By making the property "propertyName:key", the "key" can be extracted and passed under expression `%key%` to the function
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                $fieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($field);

                // Validate that the property exists
                $isValueInDBItems = array_key_exists($fieldOutputKey, $dbItems[(string)$id] ?? []);
                if (!$isValueInDBItems && !array_key_exists($fieldOutputKey, $previousDBItems[$dbKey][(string)$id] ?? [])) {
                    if ($fieldOutputKey != $field) {
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('Field \'%s\' (under property \'%s\') hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'),
                                $field,
                                $fieldOutputKey,
                                $id
                            ),
                        ];
                    } else {
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('Field \'%s\' hadn\'t been set for object with ID \'%s\', so it can\'t be transformed', 'component-model'),
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

                // Validate that the value is an array
                if (!is_array($value)) {
                    if ($fieldOutputKey != $field) {
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('The value for field \'%s\' (under property \'%s\') is not an array, so execution of this directive can\'t continue', 'component-model'),
                                $field,
                                $fieldOutputKey,
                                $id
                            ),
                        ];
                    } else {
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('The value for field \'%s\' is not an array, so execution of this directive can\'t continue', 'component-model'),
                                $fieldOutputKey,
                                $id
                            ),
                        ];
                    }
                    continue;
                }

                // Obtain the elements composing the field, to re-create a new field for each arrayItem
                $fieldParts = $this->fieldQueryInterpreter->listField($field);
                $fieldName = $fieldParts[0];
                $fieldArgs = $fieldParts[1];
                $fieldAlias = $fieldParts[2];
                $fieldSkipOutputIfNull = $fieldParts[3];
                $fieldDirectives = $fieldParts[4];

                // The value is an array. Unpack all the elements into their own property
                $array = $value;
                foreach ($array as $key => $value) {
                    // Add into the $idsDataFields object for the array items
                    // Watch out: function `regenerateAndExecuteFunction` receives `$idsDataFields` and not `$idsDataFieldOutputKeys`, so then re-create the "field" assigning a new alias
                    // If it has an alias, use it. If not, use the fieldName
                    $arrayItemAlias = $this->createPropertyForArrayItem($fieldAlias ? $fieldAlias : QuerySyntax::SYMBOL_FIELDALIAS_PREFIX . $fieldName, (string) $key);
                    $arrayItemProperty = $this->fieldQueryInterpreter->composeField(
                        $fieldName,
                        $fieldArgs,
                        $arrayItemAlias,
                        $fieldSkipOutputIfNull,
                        $fieldDirectives
                    );
                    $arrayItemPropertyOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($arrayItemProperty);
                    // Place into the current object
                    $dbItems[(string)$id][$arrayItemPropertyOutputKey] = $value;
                    // Place it into list of fields to process
                    $arrayItemIdsProperties[(string)$id]['direct'][] = $arrayItemProperty;
                }
            }
        }
        // 2. Execute the function for all arrayItems
        $this->regenerateAndExecuteFunction($typeResolver, $resultIDItems, $arrayItemIdsProperties, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        // 3. Composer the array from the results for each array item
        foreach ($idsDataFields as $id => $dataFields) {
            foreach ($dataFields['direct'] as $field) {
                $fieldOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($field);
                $isValueInDBItems = array_key_exists($fieldOutputKey, $dbItems[(string)$id] ?? []);
                $value = $isValueInDBItems ?
                    $dbItems[(string)$id][$fieldOutputKey] :
                    $previousDBItems[$dbKey][(string)$id][$fieldOutputKey];

                // If the array is null or empty, nothing to do
                if (!$value) {
                    continue;
                }
                if (!is_array($value)) {
                    continue;
                }

                // Obtain the elements composing the field, to re-create a new field for each arrayItem
                $fieldParts = $this->fieldQueryInterpreter->listField($field);
                $fieldName = $fieldParts[0];
                $fieldArgs = $fieldParts[1];
                $fieldAlias = $fieldParts[2];
                $fieldSkipOutputIfNull = $fieldParts[3];
                $fieldDirectives = $fieldParts[4];

                // The value is an array. Unpack all the elements into their own property
                $arrayValue = [];
                $array = $value;
                foreach ($array as $key => $value) {
                    $arrayItemAlias = $this->createPropertyForArrayItem($fieldAlias ? $fieldAlias : QuerySyntax::SYMBOL_FIELDALIAS_PREFIX . $fieldName, (string) $key);
                    $arrayItemProperty = $this->fieldQueryInterpreter->composeField(
                        $fieldName,
                        $fieldArgs,
                        $arrayItemAlias,
                        $fieldSkipOutputIfNull,
                        $fieldDirectives
                    );
                    // Place the result of executing the function on the array item
                    $arrayItemPropertyOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($arrayItemProperty);
                    $arrayItemValue = $dbItems[(string)$id][$arrayItemPropertyOutputKey];
                    // Remove this temporary property from $dbItems
                    unset($dbItems[(string)$id][$arrayItemPropertyOutputKey]);
                    // Validate it's not an error
                    if (GeneralUtils::isError($arrayItemValue)) {
                        $error = $arrayItemValue;
                        $dbErrors[(string)$id][] = [
                            Tokens::PATH => [$this->directive],
                            Tokens::MESSAGE => sprintf(
                                $this->translationAPI->__('Transformation of element with key \'%s\' on array from property \'%s\' on object with ID \'%s\' failed due to error: %s', 'component-model'),
                                $key,
                                $fieldOutputKey,
                                $id,
                                $error->getErrorMessage()
                            ),
                        ];
                        continue;
                    }

                    $arrayValue[$key] = $arrayItemValue;
                }

                // Finally, place the results for all items in the array in the original property
                $dbItems[(string)$id][$fieldOutputKey] = $arrayValue;
            }
        }
    }

    /**
     * Create a property for storing the array item in the current object
     */
    protected function createPropertyForArrayItem(string $fieldAliasOrName, string $key): string
    {
        return implode(self::PROPERTY_SEPARATOR, [$fieldAliasOrName, $key]);
    }

    protected function extractElementsFromArrayItemProperty(string $arrayItemProperty): array
    {
        return explode(self::PROPERTY_SEPARATOR, $arrayItemProperty);
    }

    protected function addExpressionsForResultItem(
        TypeResolverInterface $typeResolver,
        $id,
        string $field,
        array &$resultIDItems,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$messages,
        array &$dbErrors,
        array &$dbWarnings,
        array &$dbDeprecations,
        array &$schemaErrors,
        array &$schemaWarnings,
        array &$schemaDeprecations
    ): void {
        // First let the parent add $value, then also add $key, which can be deduced from the fieldOutputKey
        parent::addExpressionsForResultItem($typeResolver, $id, $field, $resultIDItems, $dbItems, $previousDBItems, $variables, $messages, $dbErrors, $dbWarnings, $dbDeprecations, $schemaErrors, $schemaWarnings, $schemaDeprecations);

        $arrayItemPropertyOutputKey = $this->fieldQueryInterpreter->getFieldOutputKey($field);
        $arrayItemPropertyElems = $this->extractElementsFromArrayItemProperty($arrayItemPropertyOutputKey);
        $key = $arrayItemPropertyElems[1];
        $this->addExpressionForResultItem($id, 'key', $key, $messages);
    }
}
