<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveTypes;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\ScalarType\ScalarTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use Symfony\Contracts\Service\Attribute\Required;

final class SerializeScalarTypeValuesInDBItemsDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    protected DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver;

    #[Required]
    final public function autowireSerializeScalarTypeValuesInDBItemsDirectiveResolver(
        DangerouslyDynamicScalarTypeResolver $dangerouslyDynamicScalarTypeResolver,
    ): void {
        $this->dangerouslyDynamicScalarTypeResolver = $dangerouslyDynamicScalarTypeResolver;
    }

    public function getDirectiveName(): string
    {
        return 'serializeScalarTypeValuesInDBItems';
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
        $unionTypeResolver = null;
        $targetObjectTypeResolver = null;
        $isUnionTypeResolver = $relationalTypeResolver instanceof UnionTypeResolverInterface;
        if ($isUnionTypeResolver) {
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
        } else {
            /** @var ObjectTypeResolverInterface */
            $targetObjectTypeResolver = $relationalTypeResolver;
        }

        foreach (array_keys($idsDataFields) as $id) {
            // Obtain its ID and the required data-fields for that ID
            $object = $objectIDItems[$id];
            // It could be that the object is NULL. For instance: a post has a location stored a meta value, and the corresponding location object was deleted, so the ID is pointing to a non-existing object
            // In that case, simply return a dbError, and set the result as an empty array
            if ($object === null) {
                continue;
            }
            if ($isUnionTypeResolver) {
                $targetObjectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
            }
            $dataFields = $idsDataFields[(string)$id]['direct'];
            foreach ($dataFields as $field) {
                $fieldSchemaDefinition = $targetObjectTypeResolver->getFieldSchemaDefinition($field);
                $fieldTypeResolver = $fieldSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
                if (!($fieldTypeResolver instanceof ScalarTypeResolverInterface)) {
                    continue;
                }

                /** @var ScalarTypeResolverInterface */
                $fieldScalarTypeResolver = $fieldTypeResolver;
                $fieldOutputKey = $this->fieldQueryInterpreter->getUniqueFieldOutputKeyByObjectTypeResolver(
                    $targetObjectTypeResolver,
                    $field,
                );
                $value = $dbItems[(string)$id][$fieldOutputKey] ?? null;
                if ($value === null) {
                    continue;
                }
                // Serialize the scalar value stored in $dbItems
                $dbItems[(string)$id][$fieldOutputKey] = $this->serializeScalarTypeValue(
                    $fieldScalarTypeResolver,
                    $fieldSchemaDefinition,
                    $value
                );
            }
        }
    }

    /**
     * The response for Custom Scalar Types must be serialized.
     * The response type is the same as in the ScalarType's
     * `serialize` method.
     *
     * @param array<string, mixed> $fieldScalarSchemaDefinition
     */
    private function serializeScalarTypeValue(
        ScalarTypeResolverInterface $fieldScalarTypeResolver,
        array $fieldScalarSchemaDefinition,
        mixed $value,
    ): string|int|float|bool|array {
        $fieldScalarTypeResolver = $fieldScalarSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];
        /**
         * `DangerouslyDynamic` is a special scalar type which is not coerced or validated.
         * In particular, it does not need to validate if it is an array or not,
         * as according to the applied WrappingType.
         */
        if ($fieldScalarTypeResolver === $this->dangerouslyDynamicScalarTypeResolver) {
            /**
             * Array is not supported by `serialize`, but can still be handled
             * by DangerouslyDynamic. So convert it into stdClass
             */
            if (is_array($value)) {
                $value = (object) $value;
            }
            return $value === null ? null : $fieldScalarTypeResolver->serialize($value);
        }

        // If the value is an array of arrays, then serialize each subelement to the item type
        if ($fieldScalarSchemaDefinition[SchemaDefinition::IS_ARRAY_OF_ARRAYS] ?? false) {
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
        if ($fieldScalarSchemaDefinition[SchemaDefinition::IS_ARRAY] ?? false) {
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
