<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;

final class SerializeScalarTypeDBItemsDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    public function getDirectiveName(): string
    {
        return 'serializeScalarTypeDBItems';
    }

    /**
     * This is a system directive
     */
    public function getDirectiveType(): string
    {
        return DirectiveTypes::SYSTEM;
    }

    /**
     * Execute it at the very end, after everything else,
     * to allow other directives to deal with the unserialized value
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::END;
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
        if (!$objectIDItems) {
            return;
        }
        if (!($relationalTypeResolver instanceof ObjectTypeResolverInterface)) {
            return;
        }
        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;
        
        foreach (array_keys($idsDataFields) as $id) {
            $dataFields = $idsDataFields[(string)$id]['direct'];
            foreach ($dataFields as $field) {
                $fieldSchemaDefinition = $objectTypeResolver->getFieldSchemaDefinition($field);
                $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
                if (!($fieldTypeResolver instanceof ScalarTypeResolverInterface)) {
                    continue;
                }

                /** @var ScalarTypeResolverInterface */
                $fieldScalarTypeResolver = $fieldTypeResolver;
                $fieldOutputKey = $this->fieldQueryInterpreter->getUniqueFieldOutputKeyByObjectTypeResolver(
                    $objectTypeResolver,
                    $field,
                );
                $value = $dbItems[(string)$id][$fieldOutputKey];
                $dbItems[(string)$id][$fieldOutputKey] = $this->serializeScalarTypeValue(
                    $fieldScalarTypeResolver,
                    $value
                );
            }
        }
    }

    /**
     * The response for Custom Scalar Types must be serialized
     */
    protected function serializeScalarTypeValue(
        ScalarTypeResolverInterface $fieldScalarTypeResolver,
        mixed $value,
    ): mixed {
        // If the value is an array of arrays, then serialize each subelement to the item type
        if ($fieldSchemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false) {
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
        if ($fieldSchemaDefinition[SchemaDefinition::IS_ARRAY] ?? false) {
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
        return $this->translationAPI->__('Serialize the results for fields of Scalar Type. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
