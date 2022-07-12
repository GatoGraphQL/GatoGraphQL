<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\LeafOutputTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use SplObjectStorage;

final class SerializeLeafOutputTypeValuesDirectiveResolver extends AbstractGlobalDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    public function getDirectiveName(): string
    {
        return 'serializeLeafOutputTypeValues';
    }

    /**
     * This is a system directive
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::SYSTEM;
    }

    /**
     * Execute it at the very end, after everything else,
     * to allow other directives to deal with the unserialized value
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_SERIALIZE;
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$variables,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        if (!$idObjects) {
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

        foreach ($idFieldSet as $id => $fieldSet) {
            // Obtain its ID and the required data-fields for that ID
            $object = $idObjects[$id];
            /**
             * It could be that the object is NULL. In that case,
             * simply return a dbError, and set the result as an empty array.
             *
             * For instance: a post has a location stored a meta value,
             * and the corresponding location object was deleted,
             * so the ID is pointing to a non-existing object.
             */
            if ($object === null) {
                continue;
            }
            if ($isUnionTypeResolver) {
                $targetObjectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
            }
            foreach ($fieldSet->fields as $field) {
                $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
                $fieldTypeResolver = $targetObjectTypeResolver->getFieldTypeResolver($field, $variables, $separateObjectTypeFieldResolutionFeedbackStore);
                $engineIterationFeedbackStore->objectFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
                    $separateObjectTypeFieldResolutionFeedbackStore,
                    $targetObjectTypeResolver,
                    $this->directive,
                    [$id => new EngineIterationFieldSet([$field])]
                );
                if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    continue;
                }
                if (!($fieldTypeResolver instanceof LeafOutputTypeResolverInterface)) {
                    continue;
                }

                /** @var LeafOutputTypeResolverInterface */
                $fieldLeafOutputTypeResolver = $fieldTypeResolver;
                $value = $resolvedIDFieldValues[$id][$field] ?? null;
                if ($value === null) {
                    continue;
                }

                /** @var int */
                $fieldTypeModifiers = $targetObjectTypeResolver->getFieldTypeModifiers($field, $variables, $separateObjectTypeFieldResolutionFeedbackStore);
                $engineIterationFeedbackStore->objectFeedbackStore->incorporateFromObjectTypeFieldResolutionFeedbackStore(
                    $separateObjectTypeFieldResolutionFeedbackStore,
                    $targetObjectTypeResolver,
                    $this->directive,
                    [$id => new EngineIterationFieldSet([$field])]
                );
                if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
                    continue;
                }
                $fieldLeafOutputTypeIsArrayOfArrays = ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
                $fieldLeafOutputTypeIsArray = ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
                // Serialize the scalar/enum value stored in $resolvedIDFieldValues
                $resolvedIDFieldValues[$id][$field] = $this->serializeLeafOutputTypeValue(
                    $value,
                    $fieldLeafOutputTypeResolver,
                    $fieldLeafOutputTypeIsArrayOfArrays,
                    $fieldLeafOutputTypeIsArray,
                );
            }
        }
    }

    /**
     * The response for Scalar Types and Enum types must be serialized.
     * The response type is the same as in the type's `serialize` method.
     */
    private function serializeLeafOutputTypeValue(
        mixed $value,
        LeafOutputTypeResolverInterface $fieldLeafOutputTypeResolver,
        bool $fieldLeafOutputTypeIsArrayOfArrays,
        bool $fieldLeafOutputTypeIsArray,
    ): string|int|float|bool|array {
        /**
         * `DangerouslyNonSpecificScalar` is a special scalar type which is not coerced or validated.
         * In particular, it does not need to validate if it is an array or not,
         * as according to the applied WrappingType.
         */
        if ($fieldLeafOutputTypeResolver === $this->getDangerouslyNonSpecificScalarTypeScalarTypeResolver()) {
            /**
             * Array is not supported by `serialize`, but can still be handled
             * by DangerouslyNonSpecificScalar. So convert it into stdClass
             */
            if (is_array($value)) {
                $value = (object) $value;
            }
            return $fieldLeafOutputTypeResolver->serialize($value);
        }

        // If the value is an array of arrays, then serialize each subelement to the item type
        // To make sure the array is not associative (on which case it should be treated
        // as a JSONObject instead of an array), also apply `array_values`
        if ($fieldLeafOutputTypeIsArrayOfArrays) {
            return array_values(array_map(
                // If it contains a null value, return it as is
                fn (?array $arrayValueElem) => $arrayValueElem === null ? null : array_values(array_map(
                    fn (mixed $arrayOfArraysValueElem) => $arrayOfArraysValueElem === null ? null : $fieldLeafOutputTypeResolver->serialize($arrayOfArraysValueElem),
                    $arrayValueElem
                )),
                $value
            ));
        }

        // If the value is an array, then serialize each element to the item type
        if ($fieldLeafOutputTypeIsArray) {
            return array_values(array_map(
                fn (mixed $arrayValueElem) => $arrayValueElem === null ? null : $fieldLeafOutputTypeResolver->serialize($arrayValueElem),
                $value
            ));
        }

        // Otherwise, simply serialize the given value directly
        return $fieldLeafOutputTypeResolver->serialize($value);
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Serialize the results for fields of Scalar and Enum Types. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
