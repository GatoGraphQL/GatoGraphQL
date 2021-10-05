<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;

final class ResolveValueAndMergeDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    public function getDirectiveName(): string
    {
        return 'resolveValueAndMerge';
    }

    /**
     * This is a system directive
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SYSTEM;
    }

    /**
     * This directive must be the first one of its group
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_RESOLVE;
    }

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
        // Iterate data, extract into final results
        if ($objectIDItems) {
            $this->resolveValueForObjects($relationalTypeResolver, $objectIDItems, $idsDataFields, $dbItems, $previousDBItems, $variables, $messages, $objectErrors, $objectWarnings, $objectDeprecations, $schemaErrors, $schemaWarnings, $schemaDeprecations);
        }
    }

    protected function resolveValueForObjects(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array &$objectIDItems,
        array &$idsDataFields,
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
        $enqueueFillingObjectsFromIDs = [];
        foreach (array_keys($idsDataFields) as $id) {
            // Obtain its ID and the required data-fields for that ID
            $object = $objectIDItems[$id];
            // It could be that the object is NULL. For instance: a post has a location stored a meta value, and the corresponding location object was deleted, so the ID is pointing to a non-existing object
            // In that case, simply return a dbError, and set the result as an empty array
            if (is_null($object)) {
                $objectErrors[(string)$id][] = [
                    Tokens::PATH => ['id'],
                    Tokens::MESSAGE => sprintf(
                        $this->translationAPI->__('Corrupted data: Object with ID \'%s\' doesn\'t exist', 'component-model'),
                        $id
                    ),
                ];
                // This is currently pointing to NULL and returning this entry in the database. Remove it
                // (this will also avoid errors in the Engine, which expects this result to be an array and can't be null)
                unset($dbItems[(string)$id]);
                continue;
            }

            $expressions = $this->getExpressionsForObject($id, $variables, $messages);
            $this->resolveValuesForObject($relationalTypeResolver, $id, $object, $idsDataFields[(string)$id]['direct'], $dbItems, $previousDBItems, $variables, $expressions, $objectErrors, $objectWarnings, $objectDeprecations);

            // Add the conditional data fields
            // If the conditionalDataFields are empty, we already reached the end of the tree. Nothing else to do
            foreach (array_filter($idsDataFields[$id]['conditional']) as $conditionDataField => $conditionalDataFields) {
                // Check if the condition field has value `true`
                // All 'conditional' fields must have their own key as 'direct', then simply look for this element on $dbItems
                $conditionFieldOutputKey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey($relationalTypeResolver, $conditionDataField, $object);
                if (isset($dbItems[$id]) && array_key_exists($conditionFieldOutputKey, $dbItems[$id])) {
                    $conditionSatisfied = (bool)$dbItems[$id][$conditionFieldOutputKey];
                } else {
                    $conditionSatisfied = false;
                }
                if ($conditionSatisfied) {
                    $enqueueFillingObjectsFromIDs[(string)$id]['direct'] = array_unique(array_merge(
                        $enqueueFillingObjectsFromIDs[(string)$id]['direct'] ?? [],
                        array_keys($conditionalDataFields)
                    ));
                    foreach ($conditionalDataFields as $nextConditionDataField => $nextConditionalDataFields) {
                        $enqueueFillingObjectsFromIDs[(string)$id]['conditional'][$nextConditionDataField] = array_merge_recursive(
                            $enqueueFillingObjectsFromIDs[(string)$id]['conditional'][$nextConditionDataField] ?? [],
                            $nextConditionalDataFields
                        );
                    }
                }
            }
        }
        // Enqueue items for the next iteration
        if ($enqueueFillingObjectsFromIDs) {
            $relationalTypeResolver->enqueueFillingObjectsFromIDs($enqueueFillingObjectsFromIDs);
        }
    }

    protected function resolveValuesForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        array $dataFields,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$expressions,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations
    ): void {
        foreach ($dataFields as $field) {
            $this->resolveValueForObject($relationalTypeResolver, $id, $object, $field, $dbItems, $previousDBItems, $variables, $expressions, $objectErrors, $objectWarnings, $objectDeprecations);
        }
    }

    protected function resolveValueForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        string $field,
        array &$dbItems,
        array &$previousDBItems,
        array &$variables,
        array &$expressions,
        array &$objectErrors,
        array &$objectWarnings,
        array &$objectDeprecations
    ): void {
        // Get the value, and add it to the database
        $value = $this->resolveFieldValue($relationalTypeResolver, $id, $object, $field, $previousDBItems, $variables, $expressions, $objectWarnings, $objectDeprecations);
        $this->addValueForObject($relationalTypeResolver, $id, $object, $field, $value, $dbItems, $objectErrors);
    }

    protected function resolveFieldValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        $id,
        object $object,
        string $field,
        array &$previousDBItems,
        array &$variables,
        array &$expressions,
        array &$objectWarnings,
        array &$objectDeprecations
    ) {
        $value = $relationalTypeResolver->resolveValue($object, $field, $variables, $expressions);
        // Merge the objectWarnings and objectDeprecations, if any
        if ($storedObjectWarnings = $this->feedbackMessageStore->retrieveAndClearObjectWarnings($id)) {
            $objectWarnings[$id] = array_merge(
                $objectWarnings[$id] ?? [],
                $storedObjectWarnings
            );
        }
        if ($storedObjectDeprecations = $this->feedbackMessageStore->retrieveAndClearObjectDeprecations($id)) {
            $objectDeprecations[$id] = array_merge(
                $objectDeprecations[$id] ?? [],
                $storedObjectDeprecations
            );
        }

        return $value;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getErrorOutput(Error $error): array
    {
        $errorOutput = [
            Tokens::MESSAGE => $error->getMessageOrCode(),
            Tokens::EXTENSIONS => $error->getData(),
        ];
        foreach ($error->getNestedErrors() as $nestedError) {
            $errorOutput[Tokens::EXTENSIONS][Tokens::NESTED][] = $this->getErrorOutput($nestedError);
        }
        return $errorOutput;
    }

    protected function addValueForObject(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string | int $id,
        object $object,
        string $field,
        mixed $value,
        array &$dbItems,
        array &$objectErrors,
    ): void {
        // The dataitem can contain both rightful values and also errors (eg: when the field doesn't exist, or the field validation fails)
        // Extract the errors and add them on the other array
        if (GeneralUtils::isError($value)) {
            // Extract the error message
            /** @var Error */
            $error = $value;
            $objectErrors[(string)$id][] = array_merge(
                [
                    Tokens::PATH => [$field],
                ],
                $this->getErrorOutput($error)
            );

            // For GraphQL, set the response for the failing field as null
            if (ComponentConfiguration::setFailingFieldResponseAsNull()) {
                $fieldOutputKey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey(
                    $relationalTypeResolver,
                    $field,
                    $object,
                );
                $dbItems[(string)$id][$fieldOutputKey] = null;
            }
            return;
        }
        // If there is an alias, store the results under this. Otherwise, on the fieldName+fieldArgs
        $fieldOutputKey = $this->fieldQueryInterpreter->getUniqueFieldOutputKey(
            $relationalTypeResolver,
            $field,
            $object,
        );
        // Custom Scalar Types must be serialized
        $value = $this->maybeSerializeValue($relationalTypeResolver, $field, $value);
        $dbItems[(string)$id][$fieldOutputKey] = $value;
    }

    /**
     * The response for Custom Scalar Types must be serialized
     */
    protected function maybeSerializeValue(
        RelationalTypeResolverInterface $relationalTypeResolver,
        string $field,
        mixed $value,
    ): mixed {
        if (!($relationalTypeResolver instanceof ObjectTypeResolverInterface)) {
            return $value;
        }

        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;
        $fieldSchemaDefinition = $objectTypeResolver->getFieldSchemaDefinition($field);
        $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::ARGNAME_TYPE_RESOLVER];
        if (!($fieldTypeResolver instanceof ScalarTypeResolverInterface)) {
            return $value;
        }

        /** @var ScalarTypeResolverInterface */
        $fieldScalarTypeResolver = $fieldTypeResolver;

        // If the value is an array of arrays, then serialize each subelement to the item type
        if ($fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY_OF_ARRAYS] ?? false) {
            return $value === null ? null : array_map(
                // If it contains a null value, return it as is
                fn (?array $arrayValueElem) => $arrayValueElem === null ? null : array_map(
                    fn (mixed $arrayOfArraysValueElem) => $arrayOfArraysValueElem === null ? null : $fieldScalarTypeResolver->serialize($arrayOfArraysValueElem),
                    $arrayValueElem
                ),
                $value
            );
        }

        // If the value is an array, then serialize each element to the item type
        if ($fieldSchemaDefinition[SchemaDefinition::ARGNAME_IS_ARRAY] ?? false) {
            return $value === null ? null : array_map(
                fn (mixed $arrayValueElem) => $arrayValueElem === null ? null : $fieldScalarTypeResolver->serialize($arrayValueElem),
                $value
            );
        }

        // Otherwise, simply serialize the given value directly
        return $value === null ? null : $fieldScalarTypeResolver->serialize($value);
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->translationAPI->__('Resolve the value of the field and merge it into results. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
