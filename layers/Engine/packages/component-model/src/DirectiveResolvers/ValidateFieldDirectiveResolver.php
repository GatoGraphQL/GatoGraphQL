<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\ComponentModel\Engine\EngineIterationFieldSet;
use PoP\ComponentModel\Feedback\EngineIterationFeedbackStore;
use PoP\ComponentModel\QueryResolution\FieldDataAccessProviderInterface;
use PoP\ComponentModel\StaticHelpers\MethodHelpers;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\PipelinePositions;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\UnionType\UnionTypeResolverInterface;
use PoP\ComponentModel\TypeSerialization\TypeSerializationServiceInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use SplObjectStorage;

final class ValidateFieldDirectiveResolver extends AbstractGlobalFieldDirectiveResolver
{
    private ?TypeSerializationServiceInterface $typeSerializationService = null;

    final protected function getTypeSerializationService(): TypeSerializationServiceInterface
    {
        if ($this->typeSerializationService === null) {
            /** @var TypeSerializationServiceInterface */
            $typeSerializationService = $this->instanceManager->getInstance(TypeSerializationServiceInterface::class);
            $this->typeSerializationService = $typeSerializationService;
        }
        return $this->typeSerializationService;
    }

    public function getDirectiveName(): string
    {
        return 'validate';
    }

    /**
     * This is a system directive
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::SYSTEM;
    }

    /**
     * This directive must be the first one of its group
     */
    public function getPipelinePosition(): string
    {
        return PipelinePositions::AFTER_VALIDATE;
    }

    /**
     * Validate at the schema level first that Fields
     * exist, and RelationalFields are indeed relational
     * in the resolver.
     *
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<FieldDataAccessProviderInterface> $succeedingPipelineFieldDataAccessProviders
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,mixed>>> $previouslyResolvedIDFieldValues
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     * @param array<FieldDirectiveResolverInterface> $succeedingPipelineFieldDirectiveResolvers
     * @param array<string|int,object> $idObjects
     * @param array<string,array<string|int,SplObjectStorage<FieldInterface,array<string|int>>>> $unionTypeOutputKeyIDs
     * @param array<string,mixed> $messages
     */
    public function resolveDirective(
        RelationalTypeResolverInterface $relationalTypeResolver,
        array $idFieldSet,
        FieldDataAccessProviderInterface $fieldDataAccessProvider,
        array $succeedingPipelineFieldDirectiveResolvers,
        array $idObjects,
        array $unionTypeOutputKeyIDs,
        array $previouslyResolvedIDFieldValues,
        array &$succeedingPipelineIDFieldSet,
        array &$succeedingPipelineFieldDataAccessProviders,
        array &$resolvedIDFieldValues,
        array &$messages,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {
        if ($relationalTypeResolver instanceof UnionTypeResolverInterface) {
            /** @var UnionTypeResolverInterface */
            $unionTypeResolver = $relationalTypeResolver;
            /** @var SplObjectStorage<ObjectTypeResolverInterface,FieldInterface[]> */
            $processedObjectTypeResolverFields = new SplObjectStorage();
            foreach ($idFieldSet as $id => $fieldSet) {
                $object = $idObjects[$id];
                $targetObjectTypeResolver = $unionTypeResolver->getTargetObjectTypeResolver($object);
                if ($targetObjectTypeResolver === null) {
                    continue;
                }
                $processedFields = $processedObjectTypeResolverFields[$targetObjectTypeResolver] ?? [];
                foreach ($fieldSet->fields as $field) {
                    if (in_array($field, $processedFields)) {
                        continue;
                    }
                    $processedFields[] = $field;
                    $this->executeValidationForField(
                        $targetObjectTypeResolver,
                        $field,
                        $idFieldSet,
                        $succeedingPipelineIDFieldSet,
                        $resolvedIDFieldValues,
                        $engineIterationFeedbackStore,
                    );
                }
                $processedObjectTypeResolverFields[$targetObjectTypeResolver] = $processedFields;
            }
            return;
        }

        /** @var ObjectTypeResolverInterface */
        $objectTypeResolver = $relationalTypeResolver;
        $fields = MethodHelpers::getFieldsFromIDFieldSet($idFieldSet);
        foreach ($fields as $field) {
            $this->executeValidationForField(
                $objectTypeResolver,
                $field,
                $idFieldSet,
                $succeedingPipelineIDFieldSet,
                $resolvedIDFieldValues,
                $engineIterationFeedbackStore,
            );
        }
    }

    /**
     * @param array<string|int,EngineIterationFieldSet> $idFieldSet
     * @param array<array<string|int,EngineIterationFieldSet>> $succeedingPipelineIDFieldSet
     * @param array<string|int,SplObjectStorage<FieldInterface,mixed>> $resolvedIDFieldValues
     */
    protected function executeValidationForField(
        ObjectTypeResolverInterface $objectTypeResolver,
        FieldInterface $field,
        array $idFieldSet,
        array &$succeedingPipelineIDFieldSet,
        array &$resolvedIDFieldValues,
        EngineIterationFeedbackStore $engineIterationFeedbackStore,
    ): void {

        $feedbackItemResolution = null;

        /**
         * Validation that the field exists will have been done
         * when parsing the Field Data, but possibly not so
         * for dynamic variables, so execute the validation here too.
         *
         * @see layers/Engine/packages/component-model/src/TypeResolvers/ObjectType/AbstractObjectTypeResolver.php:doGetObjectTypeResolverObjectFieldData
         * @see dynamic-variable-type-casting.gql
         */
        $objectTypeFieldResolver = $objectTypeResolver->getExecutableObjectTypeFieldResolverForField($field);
        if ($objectTypeFieldResolver === null) {
            $feedbackItemResolution = new FeedbackItemResolution(
                GraphQLSpecErrorFeedbackItemProvider::class,
                GraphQLSpecErrorFeedbackItemProvider::E_5_3_1,
                [
                    $field->getName(),
                    $objectTypeResolver->getMaybeNamespacedTypeName(),
                ]
            );
        } elseif (
            $field instanceof RelationalField
            && !($objectTypeFieldResolver->getFieldTypeResolver($objectTypeResolver, $field->getName()) instanceof RelationalTypeResolverInterface)
        ) {
            /**
             * Validate that a RelationalField in the AST is not actually
             * a leaf field in the resolver.
             *
             * Eg: field "id" is built as RelationalField in the AST, but it is
             * not a connection:
             *
             *   ```
             *   {
             *     id {
             *       __typename
             *     }
             *   }
             *   ```
             */
            $feedbackItemResolution = new FeedbackItemResolution(
                GraphQLSpecErrorFeedbackItemProvider::class,
                GraphQLSpecErrorFeedbackItemProvider::E_5_3_3,
                [
                    $field->getOutputKey(),
                    $objectTypeResolver->getMaybeNamespacedTypeName(),
                ]
            );
        }

        if ($feedbackItemResolution !== null) {
            $this->processSchemaFailure(
                $objectTypeResolver,
                $feedbackItemResolution,
                MethodHelpers::filterFieldsInIDFieldSet($idFieldSet, [$field]),
                $succeedingPipelineIDFieldSet,
                $field,
                $resolvedIDFieldValues,
                $engineIterationFeedbackStore,
            );
        }
    }

    public function getDirectiveDescription(RelationalTypeResolverInterface $relationalTypeResolver): ?string
    {
        return $this->__('Validate the fields before they are resolved. This directive is already included by the engine, since its execution is mandatory', 'component-model');
    }
}
